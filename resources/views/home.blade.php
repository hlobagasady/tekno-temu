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
            <div>
                <h1>Temukan perangkat<br><em>yang tepat.</em></h1>
                <p>Pilihan terkurasi dengan spesifikasi jelas. Bandingkan dulu, beli di marketplace pilihan Anda.</p><a class="button" href="#products">Lihat pilihan</a>
            </div>
            <div class="art" id="heroSlider" role="region" aria-label="Foto produk pilihan">
                <div class="hero-slides" id="heroSlides"></div>
                <div class="hero-dots" id="heroDots" aria-label="Pilih foto produk"></div>
            </div>
        </section>
        @include('partials.site-nav')
        <section>
            <div class="section-head">
                <div>
                    <p class="eyebrow">PILIH SESUAI KEBUTUHAN</p>
                    <h2>Koleksi pilihan</h2>
                </div>
            </div>
            <div class="collections">@foreach($collections as $collection)<a href="#products" data-collection="{{ $collection->name }}"><b>{{ $collection->name }}</b><small>{{ $collection->description }}</small></a>@endforeach</div>
        </section>
        <section id="products">
            <div class="section-head">
                <div>
                    <p class="eyebrow">KATALOG TERKURASI</p>
                    <h2>Produk pilihan</h2>
                </div>
            </div>
            <div class="grid" id="grid">
                <p>Memuat produk...</p>
            </div>
            <button class="button load-more" id="loadMore" type="button" hidden>Muat lagi</button>
        </section>
    </main>@include('partials.site-footer')</body>

</html>
