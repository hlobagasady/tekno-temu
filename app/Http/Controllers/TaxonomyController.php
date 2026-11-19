<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Http\Request;
class TaxonomyController extends Controller
{
    public function index(Request $r, string $type) { return $this->model($type)::orderBy('name')->get(); }
    public function store(Request $r, string $type) { $d=$r->validate(['name'=>'required|string|max:100','description'=>'nullable|string|max:255']); return response()->json($this->model($type)::create($d),201); }
    public function update(Request $r, string $type, int $id) { $d=$r->validate(['name'=>'required|string|max:100','description'=>'nullable|string|max:255']); $item=$this->model($type)::findOrFail($id); $item->update($d); return $item->fresh(); }
    public function destroy(string $type, int $id) { $item=$this->model($type)::findOrFail($id); $used=$type==='categories' ? \App\Models\Product::where('category',$item->name)->exists() : \App\Models\Product::where('collection_name',$item->name)->exists(); abort_if($used,422,'Tidak bisa dihapus karena masih dipakai produk.'); $item->delete(); return ['message'=>'Berhasil dihapus.']; }
    private function model(string $type) { abort_unless(in_array($type,['categories','collections'],true),404); return $type==='categories' ? Category::class : Collection::class; }
}
