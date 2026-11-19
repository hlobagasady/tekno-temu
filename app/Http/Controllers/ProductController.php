<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ProductController extends Controller
{
    private function data(Request $r, ?Product $p = null): array
    {
        $d = $r->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'price_idr' => 'required|integer|min:0',
            'sold_count' => 'nullable|integer|min:0',
            'category' => 'required|string|max:100',
            'collection_name' => 'nullable|string|max:100',
            'spec' => 'nullable|string|max:255',
            'image_url' => 'nullable|url|max:2048',
            'gallery_urls' => 'nullable|array|max:12',
            'gallery_urls.*' => 'url|max:2048',
            'video_url' => 'nullable|url|max:2048',
            'photos' => 'nullable|array|max:12',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'video' => 'nullable|file|mimes:mp4,webm|max:6144',
            'affiliate_url' => 'required|url|max:2048',
            'is_top' => 'boolean',
            'status' => 'in:draft,published,archived',
        ]);

        $host = parse_url($d['affiliate_url'], PHP_URL_HOST);
        abort_unless($host && preg_match('/(^|\.)shopee\.co\.id$/i', $host), 422, 'Domain affiliate tidak diizinkan.');

        $uploaded = collect($r->file('photos', []))
            ->map(fn ($file) => $file->store('products', 'public'))
            ->map(fn ($path) => asset('storage/'.$path))
            ->values()
            ->all();

        if ($uploaded) {
            $d['image_url'] ??= $uploaded[0];
        }

        $d['gallery_urls'] = array_values(array_unique(array_merge(
            [$d['image_url'] ?? null],
            $d['gallery_urls'] ?? [],
            $uploaded,
        )));

        if ($r->hasFile('video')) {
            $d['video_url'] = asset('storage/'.$r->file('video')->store('products/videos', 'public'));
        }

        unset($d['photos'], $d['video']);
        $d['slug'] = Str::slug($d['name']);

        return $d;
    }

    public function index(Request $r)
    {
        $admin = $r->attributes->get('admin_authenticated') === true;
        if (! $admin && $r->bearerToken()) {
            try {
                JWTAuth::parseToken()->authenticate();
                $admin = true;
            } catch (\Throwable) {
            }
        }

        return Product::query()
            ->when($admin && $r->filled('status'), fn ($q) => $q->where('status', $r->status))
            ->when(! $admin, fn ($q) => $q->where('status', 'published'))
            ->when($r->filled('category'), fn ($q) => $q->where('category', $r->category))
            ->when($r->filled('collection'), fn ($q) => $q->where('collection_name', $r->collection))
            ->when($r->filled('search'), fn ($q) => $q->where(fn ($x) => $x
                ->where('name', 'like', '%'.$r->search.'%')
                ->orWhere('brand', 'like', '%'.$r->search.'%')
                ->orWhere('category', 'like', '%'.$r->search.'%')))
            ->latest()
            ->paginate(min(max((int) $r->get('per_page', 10), 1), 50));
    }

    public function import(Request $r)
    {
        $r->validate(['file' => 'required|file|mimes:csv,txt|max:5120']);
        $handle = fopen($r->file('file')->getRealPath(), 'r');
        $headers = array_map(fn ($v) => trim((string) $v), fgetcsv($handle));
        $allowed = ['name', 'brand', 'price_idr', 'category', 'collection_name', 'spec', 'affiliate_url', 'status'];
        abort_unless($headers && ! array_diff($allowed, $headers), 422, 'Kolom CSV wajib: '.implode(', ', $allowed));
        $created = 0;
        DB::transaction(function () use ($handle, $headers, $allowed, &$created) {
            while (($row = fgetcsv($handle)) !== false) {
                if (count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                    continue;
                }
                $values = array_combine($headers, array_pad($row, count($headers), null));
                $request = Request::create('/', 'POST', array_intersect_key($values, array_flip($allowed)));
                Product::create($this->data($request));
                $created++;
            }
        });
        fclose($handle);

        return ['imported' => $created];
    }

    public function store(Request $r)
    {
        return response()->json(Product::create($this->data($r)), 201);
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $r, Product $product)
    {
        $product->update($this->data($r, $product));

        return $product->fresh();
    }

    public function destroy(Product $product)
    {
        $product->update(['status' => 'archived']);

        return response()->json(['message' => 'Produk diarsipkan.']);
    }
}
