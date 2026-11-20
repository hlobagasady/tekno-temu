<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $product->name }} — TeknoTemu</title>
    @vite(["resources/css/product.css","resources/css/app.css","resources/js/app.js","resources/js/product.js"])
</head>

<body class="product-page">@include('partials.site-header')<div class="wrap"><nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ url('/') }}">Home</a><span aria-hidden="true">›</span><a href="{{ url('/?category='.urlencode($product->category)) }}#products">{{ $product->category }}</a>@if($product->collection_name)<span aria-hidden="true">›</span><a href="{{ url('/') }}#products">{{ $product->collection_name }}</a>@endif<span aria-hidden="true">›</span><span class="breadcrumb-current" aria-current="page">{{ $product->name }}</span></nav>
        <main class="product">@php $media = array_values(array_unique(array_filter(array_merge([$product->image_url], $product->gallery_urls ?? [])))); @endphp<div class="photo">
                <div class="gallery">
                    <div class="thumbs">@foreach($media as $i => $url)<button class="thumb {{ $i === 0 ? 'active' : '' }}" type="button" data-media="{{ $url }}" aria-label="Lihat foto {{ $i + 1 }}"><img src="{{ $url }}" alt=""></button>@endforeach @if ($product->video_url) <button class="thumb thumb-video-button" type="button" data-video="{{ $product->video_url }}" aria-label="Putar video produk"><video class="thumb-video" src="{{ $product->video_url }}" poster="{{ $media[0] ?? 'https://placehold.co/600x600/F5F0E6/5363B6?text=Video' }}" muted preload="auto" tabindex="-1"></video><span class="video-play-icon" aria-hidden="true">▶</span></button> @endif</div>
                    <div class="media-main"><img id="mainMedia" src="{{ $media[0] ?? 'https://placehold.co/600x600/F5F0E6/5363B6?text=Produk' }}" alt="{{ $product->name }}"><video id="mainVideo" controls poster="{{ $media[0] ?? 'https://placehold.co/600x600/F5F0E6/5363B6?text=Video' }}" hidden></video><small class="media-label">{{ count($media) }} foto{{ $product->video_url ? ' + video' : '' }}</small></div>
                </div>
            </div>
            <div>
                <p class="eyebrow">{{ $product->category }}</p>
                <h1 class="title">{{ $product->name }}</h1>
                <p class="muted">{{ $product->brand }} · {{ number_format($product->sold_count) }} terjual</p>
                <div class="price">Rp {{ number_format($product->price_idr, 0, ',', '.') }}</div>
                <div class="spec"><b>Spesifikasi pilihan</b><br>{{ $product->spec ?: 'Spesifikasi tersedia di marketplace.' }}</div><a class="buy" href="{{ $product->affiliate_url }}" target="_blank" rel="sponsored nofollow noopener">Buka di Aplikasi ↗</a>
                <p class="notice">Harga dan stok dapat berubah. Cek ulang di Shopee sebelum membeli. TeknoTemu dapat menerima komisi dari pembelian melalui link ini.</p>
            </div>
        </main>@if($recommendations->isNotEmpty())<section class="related">
            <h2>Rekomendasi sejenis</h2>
            <div class="related-grid">@foreach($recommendations as $item)<article class="related-card"><a href="{{ route('products.show', $item) }}"><img src="{{ $item->image_url ?: 'https://placehold.co/600x600/F5F0E6/5363B6?text=Produk' }}" alt="{{ $item->name }}" loading="lazy"></a>@if($item->is_top)<span class="badge">TOP</span>@endif
                    <div class="related-info"><h3>{{ $item->name }}</h3><div class="related-meta">{{ $item->brand }} · {{ number_format($item->sold_count) }} terjual</div><div class="related-price">Rp {{ number_format($item->price_idr, 0, ',', '.') }}</div><div class="related-spec">{{ $item->spec }}</div></div>
                </article>@endforeach</div>
        </section>@endif
    </div>@include('partials.site-footer')</body>

</html>
