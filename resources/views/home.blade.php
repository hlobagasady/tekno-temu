<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="Etalase terkurasi elektronik dan perangkat IT.">
    <title>TeknoTemu — Etalase Elektronik</title>
    @vite(["resources/css/home.css","resources/css/app.css","resources/js/app.js","resources/js/home.js"])
</head>

<body class="home-page">@include('partials.site-header')<main>
        <section class="hero">
            <div class="hero-backgrounds" id="heroBackgrounds" aria-hidden="true"></div>
            <div>
                <h1>Temukan perangkat<br><em>yang tepat.</em></h1>
                <p>Pilihan terkurasi dengan spesifikasi jelas. Bandingkan dulu, beli di marketplace pilihan Anda.</p><a class="button" href="#products">Lihat pilihan</a>
            </div>
            <div class="art" id="heroSlider" role="region" aria-label="Foto produk pilihan">
                <div class="hero-slides" id="heroSlides"></div>
                <div class="hero-dots" id="heroDots" aria-label="Pilih foto produk"></div>
            </div>
        </section>
        <section>
            <div class="section-head">
                <div>
                    <h2>Koleksi pilihan</h2>
                </div>
            </div>
            @php($isMarquee = $collections->isNotEmpty())
            <div class="collections {{ $isMarquee ? 'marquee' : '' }}">
                <div class="collections-track">
                    @foreach($collections as $collection)
                    <a class="collection-card collection-card-{{ $loop->iteration }}" href="#products" data-collection="{{ $collection->name }}">
                        <span class="collection-visual collection-visual-{{ $loop->iteration }}" @if($collection->image_url) style="--collection-image: url('{{ $collection->image_url }}')" @endif aria-hidden="true"></span>
                        <span class="collection-copy"><small class="collection-kicker">KOLEKSI {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</small><b>{{ $collection->name }}</b><small>{{ $collection->description }}</small><strong>Lihat koleksi <span aria-hidden="true">→</span></strong></span>
                        <span class="collection-indicator" aria-hidden="true"><i></i><i></i><i></i></span>
                    </a>
                    @endforeach
                    @if($isMarquee)
                    @foreach($collections as $collection)
                    <a class="collection-card collection-card-{{ $loop->iteration }}" href="#products" data-collection="{{ $collection->name }}" aria-hidden="true" tabindex="-1">
                        <span class="collection-visual collection-visual-{{ $loop->iteration }}" @if($collection->image_url) style="--collection-image: url('{{ $collection->image_url }}')" @endif aria-hidden="true"></span>
                        <span class="collection-copy"><small class="collection-kicker">KOLEKSI {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</small><b>{{ $collection->name }}</b><small>{{ $collection->description }}</small><strong>Lihat koleksi <span aria-hidden="true">→</span></strong></span>
                    </a>
                    @endforeach
                    @endif
                </div>
            </div>
        </section>
        <section id="products">
            <div class="section-head">
                <div>
                    <h2>Produk pilihan</h2>
                </div>
            </div>
            @include('partials.site-nav')
            <div class="grid" id="grid">
                <p>Memuat produk...</p>
            </div>
            <button class="button load-more" id="loadMore" type="button" hidden>Muat lagi</button>
        </section>
    </main>@include('partials.site-footer')</body>

</html>
