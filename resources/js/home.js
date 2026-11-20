import heroBackground1 from '../css/hero.jpeg';
import heroBackground2 from '../css/hero2.jfif';
import heroBackground3 from '../css/hero3.jfif';
import heroBackground4 from '../css/hero4.jfif';

const grid = document.getElementById('grid');
const query = document.getElementById('query');
const loadMore = document.getElementById('loadMore');
const heroSlider = document.getElementById('heroSlider');
const heroSlides = document.getElementById('heroSlides');
const heroDots = document.getElementById('heroDots');
const heroBackgrounds = document.getElementById('heroBackgrounds');
const pageSize = 10;
let products = [];
let activeCategory = '';
let activeCollection = '';
let currentPage = 1;
let hasMore = true;
let isLoading = false;
let requestController;
let heroIndex = 0;
let heroTimer;
let heroBackgroundIndex = 0;
let heroBackgroundTimer;
const money = (value) => 'Rp ' + Number(value).toLocaleString('id-ID');
const initialCategory = new URLSearchParams(window.location.search).get('category');

if (initialCategory) {
    activeCategory = initialCategory;
}

function setLoading(loading) {
    isLoading = loading;
    loadMore.disabled = loading;
    loadMore.textContent = loading ? 'Memuat...' : 'Muat lagi';
}

function render() {
    grid.innerHTML = products.length
        ? products.map((product) => `<article class="card"><a href="/products/${product.slug}"><img src="${product.image_url || 'https://placehold.co/600x600/F5F0E6/5363B6?text=Produk'}" alt="${product.name}" loading="lazy"></a>${product.is_top ? '<span class="badge">TOP</span>' : ''}<div class="info"><h3>${product.name}</h3><div class="meta">${product.brand || ''} · ${Number(product.sold_count || 0).toLocaleString('id-ID')} terjual</div><div class="price-tag">${money(product.price_idr)}</div><div class="spec">${product.spec || ''}</div></div></article>`).join('')
        : '<p>Produk tidak ditemukan.</p>';

    loadMore.hidden = !hasMore || products.length === 0;
}

function buildProductUrl() {
    const params = new URLSearchParams({
        page: currentPage,
        per_page: pageSize,
    });
    const search = query.value.trim();

    if (activeCategory) params.set('category', activeCategory);
    if (activeCollection) params.set('collection', activeCollection);
    if (search) params.set('search', search);

    return '/api/products?' + params;
}

async function loadProducts({ reset = false } = {}) {
    if (isLoading) return;

    if (reset) {
        requestController?.abort();
        currentPage = 1;
        products = [];
        hasMore = true;
    }

    setLoading(true);
    requestController = new AbortController();

    try {
        const response = await fetch(buildProductUrl(), {
            signal: requestController.signal,
        });
        if (!response.ok) throw new Error('Produk gagal dimuat.');

        const result = await response.json();
        const items = result.data || result;

        products = reset ? items : [...products, ...items];
        hasMore = Boolean(result.next_page_url);
        render();

        if (reset) initHeroSlider();
    } catch (error) {
        if (error.name === 'AbortError') return;

        if (reset) {
            grid.innerHTML = '<p>Produk belum tersedia.</p>';
            loadMore.hidden = true;
            initHeroSlider();
        }
    } finally {
        setLoading(false);
    }
}

function showHeroSlide(index) {
    const slides = [...heroSlides.children];
    const dots = [...heroDots.children];

    if (!slides.length) return;

    heroIndex = (index + slides.length) % slides.length;
    slides.forEach((element, slideIndex) => {
        element.classList.toggle('active', slideIndex === heroIndex);
        element.setAttribute('aria-hidden', slideIndex !== heroIndex);
    });
    dots.forEach((element, dotIndex) => {
        element.classList.toggle('active', dotIndex === heroIndex);
        if (dotIndex === heroIndex) element.setAttribute('aria-current', 'true');
        else element.removeAttribute('aria-current');
    });
}

function startHeroTimer() {
    clearInterval(heroTimer);
    heroTimer = setInterval(() => showHeroSlide(heroIndex + 1), 4000);
}

function initHeroSlider() {
    const photos = products.slice(0, 5).map((product) => ({
        src: product.image_url || 'https://placehold.co/800x600/F5F0E6/5363B6?text=Produk',
        name: product.name,
    }));

    while (photos.length < 5) {
        photos.push({
            src: 'https://placehold.co/800x600/F5F0E6/5363B6?text=Produk+' + (photos.length + 1),
            name: 'Produk ' + (photos.length + 1),
        });
    }

    heroSlides.innerHTML = photos.map((photo, index) => `<div class="hero-slide${index ? '' : ' active'}"${index ? ' aria-hidden="true"' : ''}><img src="${photo.src}" alt="Foto ${photo.name}" loading="${index ? 'lazy' : 'eager'}"></div>`).join('');
    heroDots.innerHTML = photos.map((_, index) => `<button type="button" class="hero-dot${index ? '' : ' active'}" data-slide="${index}" aria-label="Foto produk ${index + 1}"${index ? '' : ' aria-current="true"'}></button>`).join('');

    heroDots.querySelectorAll('[data-slide]').forEach((button) => {
        button.onclick = () => {
            showHeroSlide(Number(button.dataset.slide));
            startHeroTimer();
        };
    });

    heroSlider.onmouseenter = () => clearInterval(heroTimer);
    heroSlider.onmouseleave = startHeroTimer;
    heroSlider.onfocusin = () => clearInterval(heroTimer);
    heroSlider.onfocusout = startHeroTimer;

    if (!matchMedia('(prefers-reduced-motion: reduce)').matches && !heroSlider.matches(':hover')) {
        startHeroTimer();
    }
}

function showHeroBackground(index) {
    const backgrounds = [...heroBackgrounds.children];

    if (!backgrounds.length) return;

    heroBackgroundIndex = (index + backgrounds.length) % backgrounds.length;
    backgrounds.forEach((element, backgroundIndex) => {
        element.classList.toggle('active', backgroundIndex === heroBackgroundIndex);
    });
}

function startHeroBackgroundTimer() {
    clearInterval(heroBackgroundTimer);
    heroBackgroundTimer = setInterval(() => showHeroBackground(heroBackgroundIndex + 1), 5000);
}

function initHeroBackgroundSlider() {
    const images = [heroBackground1, heroBackground2, heroBackground3, heroBackground4];

    heroBackgrounds.innerHTML = images.map((image, index) => `<div class="hero-background${index ? '' : ' active'}" style="background-image: url('${image}')"></div>`).join('');

    if (!matchMedia('(prefers-reduced-motion: reduce)').matches) {
        startHeroBackgroundTimer();
    }
}

document.querySelectorAll('[data-category]').forEach((link) => {
    link.onclick = (event) => {
        event.preventDefault();
        activeCategory = link.dataset.category;
        activeCollection = '';
        document.querySelectorAll('[data-category]').forEach((item) => item.classList.toggle('active', item === link));
        document.querySelectorAll('[data-collection]').forEach((item) => item.classList.remove('active'));
        loadProducts({ reset: true });
    };
});

document.querySelectorAll('[data-collection]').forEach((link) => {
    link.onclick = (event) => {
        event.preventDefault();
        activeCollection = link.dataset.collection;
        activeCategory = '';
        document.querySelectorAll('[data-collection]').forEach((item) => item.classList.toggle('active', item.dataset.collection === activeCollection));
        document.querySelectorAll('[data-category]').forEach((item) => item.classList.remove('active'));
        loadProducts({ reset: true });
        document.getElementById('products').scrollIntoView({ behavior: 'smooth' });
    };
});

loadMore.onclick = () => {
    if (isLoading) return;

    currentPage += 1;
    loadProducts();
};

query.oninput = () => loadProducts({ reset: true });
document.querySelectorAll('[data-category]').forEach((link) => {
    link.classList.toggle('active', link.dataset.category === activeCategory);
});
initHeroBackgroundSlider();
loadProducts({ reset: true });

