const $ = id => document.getElementById(id),
            api = '/api';
        let token = localStorage.getItem('teknotemu_token');

        function msg(t, err = false) {
            $('message').innerHTML = '<div class="msg ' + (err ? 'err' : '') + '">' + t + '</div>';
            setTimeout(() => $('message').innerHTML = '', 4000)
        }

        function show() {
            const loggedIn = !!token;
            document.body.classList.toggle('logged-out', !loggedIn);
            $('login').classList.toggle('hide', loggedIn);
            $('app').classList.toggle('hide', !loggedIn);
            if (loggedIn) load()
        }
        async function call(url, opt = {}) {
            opt.headers = {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                Authorization: 'Bearer ' + token,
                ...opt.headers
            };
            const r = await fetch(api + url, opt);
            const d = await r.json();
            if (r.status === 401) {
                token = null;
                localStorage.removeItem('teknotemu_token');
                show();
                throw Error('Sesi berakhir')
            }
            if (!r.ok) throw Error(d.message || 'Request gagal');
            return d
        }
        let page = 1;
        async function load() {
            try {
                const params = new URLSearchParams({
                    page,
                    per_page: 10
                });
                if ($('productSearch').value) params.set('search', $('productSearch').value);
                if ($('statusFilter').value) params.set('status', $('statusFilter').value);
                if ($('categoryFilter').value) params.set('category', $('categoryFilter').value);
                const d = await call('/products?' + params);
                const rows = d.data || d;
                $('rows').innerHTML = rows.map(p => '<tr><td><b>' + p.name + '</b><br><small class="muted">' + (p.brand || '') + '</small></td><td>' + p.category + '</td><td>Rp ' + Number(p.price_idr).toLocaleString('id-ID') + '</td><td><span class="status">' + p.status + '</span></td><td><div class="actions"><button class="action" data-edit="' + p.id + '">Edit</button><button class="action danger" data-delete="' + p.id + '">Hapus</button></div></td></tr>').join('');
                rows.forEach(p => {
                    const e = document.querySelector('[data-edit="' + p.id + '"]');
                    if (e) e.onclick = () => openEdit(p);
                    const d = document.querySelector('[data-delete="' + p.id + '"]');
                    if (d) d.onclick = () => removeProduct(p)
                });
                $('totalProducts').textContent = d.total ?? rows.length;
                const published = await call('/products?status=published&per_page=1');
                $('publishedProducts').textContent = published.total ?? 0;
                $('pageInfo').textContent = 'Halaman ' + (d.current_page ?? page) + ' dari ' + (d.last_page ?? 1);
                $('prevPage').disabled = (d.current_page ?? page) <= 1;
                $('nextPage').disabled = (d.current_page ?? page) >= (d.last_page ?? 1)
            } catch (e) {
                msg(e.message, true)
            }
        }

        function showView(view) {
            const dashboard = view === 'dashboard',
                products = view === 'products';
            $('dashboardView').classList.toggle('hide', !dashboard);
            $('productsView').classList.toggle('hide', !products);
            $('masterView').classList.toggle('hide', dashboard || products);
            document.querySelectorAll('[data-view]').forEach(x => x.classList.toggle('active', x.dataset.view === view));
            $('pageHeading').textContent = dashboard ? 'Dashboard' : products ? 'Produk' : 'Master Data';
            $('pageSubheading').textContent = dashboard ? 'Ringkasan katalog TeknoTemu' : products ? 'Kelola produk dan media katalog' : 'Kelola kategori dan collections';
            if (!dashboard && !products) loadTaxonomies()
        }
        document.querySelectorAll('[data-view]').forEach(x => x.onclick = e => {
            e.preventDefault();
            showView(x.dataset.view)
            document.body.classList.remove('mobile-menu-open');
            $('mobileMenuToggle').setAttribute('aria-expanded', 'false');
        });
        $('mobileMenuToggle').onclick = () => {
            const isOpen = document.body.classList.toggle('mobile-menu-open');
            $('mobileMenuToggle').setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        };
        $('mobileMenuBackdrop').onclick = () => {
            document.body.classList.remove('mobile-menu-open');
            $('mobileMenuToggle').setAttribute('aria-expanded', 'false');
        };
        $('loginForm').onsubmit = async e => {
            e.preventDefault();
            try {
                const r = await fetch(api + '/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json'
                    },
                    body: JSON.stringify({
                        email: $('email').value,
                        password: $('password').value
                    })
                });
                const d = await r.json();
                if (!r.ok || !d.token) throw Error(d.message || 'Login gagal');
                token = d.token;
                localStorage.setItem('teknotemu_token', token);
                show()
            } catch (e) {
                msg(e.message, true)
            }
        };
        async function loadTaxonomies() {
            for (const type of ['categories', 'collections']) {
                const rows = await call('/' + type);
                const target = $(type === 'categories' ? 'categoryRows' : 'collectionRows');
                target.innerHTML = rows.map(x => '<tr><td><b>' + x.name + '</b></td>' + (type === 'collections' ? '<td>' + ((x.description || '')) + '</td>' : '') + '<td><div class=\"actions\"><button class=\"action\" data-tax-edit=\"' + type + '\" data-tax-id=\"' + x.id + '\">Edit</button><button class=\"action danger\" data-tax-delete=\"' + type + '\" data-tax-id=\"' + x.id + '\">Hapus</button></div></td></tr>').join('');
                target.querySelectorAll('[data-tax-edit]').forEach(b => b.onclick = () => {
                    const x = rows.find(v => v.id == b.dataset.taxId);
                    openTax(type, x)
                });
                target.querySelectorAll('[data-tax-delete]').forEach(b => b.onclick = () => deleteTax(type, b.dataset.taxId))
            }
        }

        function openTax(type, item = null) {
            $('taxType').value = type;
            $('taxId').value = item?.id || '';
            $('taxName').value = item?.name || '';
            $('taxDescription').value = item?.description || '';
            $('taxDescriptionLabel').classList.toggle('hide', type === 'categories');
            $('taxTitle').textContent = (item ? 'Edit ' : 'Tambah ') + (type === 'categories' ? 'kategori' : 'collection');
            $('taxModal').classList.remove('hide')
        }

        function closeTax() {
            $('taxModal').classList.add('hide');
            $('taxForm').reset()
        }
        async function deleteTax(type, id) {
            if (!confirm('Hapus data ini?')) return;
            try {
                await call('/' + type + '/' + id, {
                    method: 'DELETE'
                });
                msg('Data dihapus');
                loadTaxonomies()
            } catch (e) {
                msg(e.message, true)
            }
        }
        document.querySelectorAll('[data-add-tax]').forEach(b => b.onclick = () => openTax(b.dataset.addTax));
        $('closeTax').onclick = closeTax;
        $('taxModal').onclick = e => {
            if (e.target.id === 'taxModal') closeTax()
        };
        $('taxForm').onsubmit = async e => {
            e.preventDefault();
            const type = $('taxType').value,
                id = $('taxId').value;
            const body = {
                name: $('taxName').value,
                description: $('taxDescription').value
            };
            try {
                await call('/' + type + (id ? '/' + id : ''), {
                    method: id ? 'PUT' : 'POST',
                    body: JSON.stringify(body)
                });
                closeTax();
                msg('Data tersimpan');
                loadTaxonomies()
            } catch (e) {
                msg(e.message, true)
            }
        };
        let editingId = null;

        function openModal() {
            document.querySelector('#productModal').classList.remove('hide')
        }

        function closeModal() {
            document.querySelector('#productModal').classList.add('hide');
            $('productForm').reset();
            editingId = null;
            $('modalTitle').textContent = 'Tambah produk'
        }

        function openEdit(p) {
            editingId = p.id;
            $('modalTitle').textContent = 'Edit produk';
            ['name', 'brand', 'price_idr', 'category', 'collection_name', 'spec', 'affiliate_url', 'status'].forEach(k => $(k).value = p[k] || '');
            openModal()
        }
        async function removeProduct(p) {
            if (!confirm('Arsipkan produk ' + p.name + '?')) return;
            try {
                await call('/products/' + p.id, {
                    method: 'DELETE'
                });
                msg('Produk diarsipkan');
                load()
            } catch (e) {
                msg(e.message, true)
            }
        }
        $('addProduct').onclick = openModal;
        $('closeModal').onclick = closeModal;
        $('productModal').onclick = e => {
            if (e.target.id === 'productModal') closeModal()
        };
        $('productForm').onsubmit = async e => {
            e.preventDefault();
            try {
                const data = new FormData();
                ['name', 'brand', 'price_idr', 'category', 'collection_name', 'spec', 'affiliate_url', 'status'].forEach(k => data.append(k, $(k).value));
                [...$('photos').files].forEach(file => data.append('photos[]', file));
                if ($('video').files[0]) data.append('video', $('video').files[0]);
                if (editingId) data.append('_method', 'PATCH');
                const r = await fetch(api + '/products' + (editingId ? '/' + editingId : ''), {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        Authorization: 'Bearer ' + token
                    },
                    body: data
                });
                const d = await r.json();
                if (!r.ok) throw Error(d.message || 'Produk gagal disimpan');
                closeModal();
                msg(editingId ? 'Produk diperbarui' : 'Produk tersimpan');
                load()
            } catch (e) {
                msg(e.message, true)
            }
        };
        $('applyFilters').onclick = () => {
            page = 1;
            load()
        };
        $('clearFilters').onclick = () => {
            $('productSearch').value = '';
            $('statusFilter').value = '';
            $('categoryFilter').value = '';
            page = 1;
            load()
        };
        $('productSearch').onkeydown = e => {
            if (e.key === 'Enter') {
                page = 1;
                load()
            }
        };
        $('prevPage').onclick = () => {
            if (page > 1) {
                page--;
                load()
            }
        };
        $('nextPage').onclick = () => {
            page++;
            load()
        };
        $('importFile').onchange = async e => {
            if (!e.target.files[0]) return;
            const data = new FormData();
            data.append('file', e.target.files[0]);
            try {
                const r = await fetch(api + '/products/import', {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        Authorization: 'Bearer ' + token
                    },
                    body: data
                });
                const d = await r.json();
                if (!r.ok) throw Error(d.message || 'Import gagal');
                msg(d.imported + ' produk berhasil diimport');
                e.target.value = '';
                page = 1;
                load()
            } catch (err) {
                msg(err.message, true)
            }
        };
        $('refresh').onclick = load;
        $('logout').onclick = async () => {
            try {
                if (token) await fetch(api + '/auth/logout', {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        Authorization: 'Bearer ' + token
                    }
                })
            } finally {
                token = null;
                localStorage.removeItem('teknotemu_token');
                window.location.href = '/'
            }
        };
        show();
