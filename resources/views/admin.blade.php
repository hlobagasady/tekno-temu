<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>TeknoTemu Admin</title>
    @vite(["resources/css/admin.css","resources/css/app.css","resources/js/app.js","resources/js/admin.js"])
</head>

<body class="logged-out">
    <aside class="sidebar">
        <div class="sidebar-brand"><i>A</i><span>TeknoTemu</span></div>
        <div class="side-label">Menu utama</div><a class="side-link active" href="#dashboard" data-view="dashboard"><strong class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="4" y="4" width="6" height="6" rx="1"></rect><rect x="14" y="4" width="6" height="6" rx="1"></rect><rect x="4" y="14" width="6" height="6" rx="1"></rect><rect x="14" y="14" width="6" height="6" rx="1"></rect></svg></strong><span>Dashboard</span></a><a class="side-link" href="#products" data-view="products"><strong class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 7.5 12 4l8 3.5v9L12 20l-8-3.5z"></path><path d="m4 7.5 8 4 8-4M12 11.5V20"></path></svg></strong><span>Produk</span></a>
        <div class="side-label">Master data</div><a class="side-link" href="#master" data-view="master"><strong class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 7h14M5 12h14M5 17h14"></path><circle cx="8" cy="7" r="1"></circle><circle cx="16" cy="12" r="1"></circle><circle cx="10" cy="17" r="1"></circle></svg></strong><span>Kategori & Collections</span></a>
        <div class="side-label">Sistem</div><a class="side-link" href="/"><strong class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M7 17 17 7M9 7h8v8"></path></svg></strong><span>Lihat website</span></a>
    </aside>
    <div class="mobile-menu-backdrop" id="mobileMenuBackdrop"></div>
    <header class="topbar">
        <button class="mobile-menu-toggle" id="mobileMenuToggle" type="button" aria-label="Buka menu" aria-expanded="false">☰</button><div><b id="pageHeading">Dashboard</b><small id="pageSubheading">Ringkasan katalog TeknoTemu</small></div><button id="logout">Keluar</button>
    </header>
    <main>
        <div id="message"></div>
        <section id="login" class="hide">
            <h1>Masuk ke dashboard</h1>
            <p>Kelola katalog produk dari satu tempat.</p>
            <form id="loginForm"><label>Email<input id="email" type="email" value="admin@example.com" autocomplete="username" required></label><label>Password<input id="password" type="password" placeholder="Masukkan password" autocomplete="current-password" required></label><button class="primary">Masuk ke dashboard</button></form>
        </section>
        <section id="app" class="hide">
            <div id="dashboardView">
                <div class="page-title" id="dashboard">
                    <div>
                        <h1>Dashboard</h1>
                        <p>Ringkasan katalog dan pengelolaan produk.</p>
                    </div><span class="crumb">Admin / Dashboard</span>
                </div>
                <div class="stats">
                    <div class="stat">
                        <div class="stat-icon">▦</div>
                        <div><small>Total produk</small><b id="totalProducts">—</b></div>
                    </div>
                    <div class="stat">
                        <div class="stat-icon">●</div>
                        <div><small>Produk terbit</small><b id="publishedProducts">—</b></div>
                    </div>
                    <div class="stat">
                        <div class="stat-icon">✓</div>
                        <div><small>Status sistem</small><b>Aktif</b></div>
                    </div>
                </div>
            </div>
            <div id="productsView" class="hide">
                <div class="page-title">
                    <div>
                        <h1>Produk</h1>
                        <p>Tambah dan kelola produk katalog.</p>
                    </div><span class="crumb">Admin / Produk</span>
                </div>
                <section class="panel">
                    <div class="panel-head">
                        <div>
                            <h2>Daftar produk</h2><span class="muted">Kelola katalog dari tabel ini</span>
                        </div>
                        <div class="toolbar"><button class="primary" id="addProduct">+ Tambah produk</button><button class="secondary" id="downloadImportTemplate" type="button">Download template</button><label class="secondary" for="importFile">Import CSV</label><input id="importFile" type="file" accept=".csv,text/csv" class="hide"><button class="secondary" id="refresh">Muat ulang</button></div>
                    </div>
                    <div class="filters"><input id="productSearch" placeholder="Cari nama, merek, kategori..."><select id="statusFilter">
                            <option value="">Semua status</option>
                            <option value="published">Terbit</option>
                            <option value="draft">Draf</option>
                            <option value="archived">Arsip</option>
                        </select><input id="categoryFilter" placeholder="Filter kategori"><button class="secondary" id="applyFilters">Terapkan</button><button class="secondary" id="clearFilters">Reset</button></div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="rows"></tbody>
                        </table>
                    </div>
                    <div class="pagination"><button class="secondary" id="prevPage">Sebelumnya</button><span id="pageInfo" class="muted">Halaman 1</span><button class="secondary" id="nextPage">Berikutnya</button></div>
                </section>
                <div id="productModal" class="modal hide" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
                    <div class="modal-card">
                        <div class="modal-head">
                            <h2 id="modalTitle">Tambah produk</h2><button class="modal-close" id="closeModal" type="button" aria-label="Tutup">×</button>
                        </div>
                        <div class="panel-body">
                            <form id="productForm" class="product-form"><input id="edit_id" type="hidden">
                                <div class="field"><label for="name">Nama produk</label><input id="name" placeholder="Contoh: Logitech MX Master 3S" required></div>
                                <div class="field"><label for="brand">Merek</label><input id="brand" placeholder="Logitech"></div>
                                <div class="field"><label for="price_idr">Harga (IDR)</label><input id="price_idr" type="number" min="0" placeholder="999000" required></div>
                                <div class="field"><label for="category">Kategori</label><input id="category" placeholder="Periferal" required></div>
                                <div class="field"><label for="collection_name">Koleksi</label><input id="collection_name" placeholder="WFH"></div>
                                <div class="field"><label for="status">Status</label><select id="status">
                                        <option value="published">Terbit</option>
                                        <option value="draft">Draf</option>
                                        <option value="archived">Arsip</option>
                                    </select></div>
                                <div class="field full"><label for="spec">Spesifikasi</label><input id="spec" placeholder="Wireless · USB-C · 8 tombol"></div>
                                <div class="field full"><label for="photos">Foto produk (bisa pilih banyak)</label><input id="photos" type="file" accept="image/jpeg,image/png,image/webp" multiple><small class="muted">Maksimal 12 foto, masing-masing 2 MB.</small></div>
                                <div class="field full"><label for="video">Video produk (opsional)</label><input id="video" type="file" accept="video/mp4,video/webm"><small class="muted">MP4/WebM, maksimal 6 MB.</small></div>
                                <div class="field full"><label for="affiliate_url">URL Shopee affiliate</label><input id="affiliate_url" type="url" placeholder="https://shopee.co.id/..." required></div>
                                <div><button class="primary" id="saveProduct">Simpan produk</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="masterView" class="hide">
                <div class="page-title">
                    <div>
                        <h1>Master Data</h1>
                        <p>Kelola kategori dan collections katalog.</p>
                    </div><span class="crumb">Admin / Master Data</span>
                </div>
                <div class="master-grid">
                    <section class="panel">
                        <div class="panel-head">
                            <div>
                                <h2>Kategori</h2><span class="muted">Dipakai untuk filter produk</span>
                            </div><button class="primary" data-add-tax="categories">+ Tambah</button>
                        </div>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="categoryRows"></tbody>
                            </table>
                        </div>
                    </section>
                    <section class="panel">
                        <div class="panel-head">
                            <div>
                                <h2>Collections</h2><span class="muted">Koleksi pilihan di landing page</span>
                            </div><button class="primary" data-add-tax="collections">+ Tambah</button>
                        </div>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="collectionRows"></tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
            <div id="taxModal" class="modal hide" role="dialog" aria-modal="true">
                <div class="modal-card">
                    <div class="modal-head">
                        <h2 id="taxTitle">Tambah master data</h2><button class="modal-close" id="closeTax" type="button">×</button>
                    </div>
                    <div class="panel-body">
                        <form id="taxForm"><input id="taxType" type="hidden"><input id="taxId" type="hidden"><label>Nama<input id="taxName" required maxlength="100"></label><label id="taxDescriptionLabel">Deskripsi<input id="taxDescription" maxlength="255"></label><label id="taxImageLabel" class="hide">Gambar koleksi<input id="taxImage" type="file" accept="image/jpeg,image/png,image/webp"><small class="muted">JPG, PNG, atau WebP, maksimal 2 MB.</small></label><button class="primary" type="submit">Simpan</button></form>
                    </div>
                </div>
            </div>
    </main>
</body>

</html>
