const $ = id => document.getElementById(id),
            api = '/api';
        let token = localStorage.getItem('teknotemu_token');

        function msg(t, err = false) {
            const message = $('message');
            const notice = document.createElement('div');
            const title = document.createElement('strong');
            const detail = document.createElement('span');
            const close = document.createElement('button');

            notice.className = 'msg ' + (err ? 'err' : 'success');
            notice.setAttribute('role', 'alert');
            title.textContent = err ? 'Gagal' : 'Berhasil';
            detail.textContent = t;
            close.className = 'msg-close';
            close.type = 'button';
            close.setAttribute('aria-label', 'Tutup notifikasi');
            close.textContent = '×';
            close.onclick = () => notice.remove();
            notice.append(title, detail, close);
            message.replaceChildren(notice);
            setTimeout(() => notice.remove(), 5000)
        }

        function errorMessage(data, fallback) {
            if (data?.errors) {
                return Object.values(data.errors).flat().join(' ');
            }

            return data?.message || fallback;
        }

        function setLoading(button, loading, label = 'Memproses...') {
            if (!button) return;

            if (loading) {
                button.dataset.originalLabel = button.textContent;
                button.disabled = true;
                button.classList.add('is-loading');
                button.innerHTML = '<span class="loading-spinner" aria-hidden="true"></span>' + label;
                return;
            }

            button.disabled = false;
            button.classList.remove('is-loading');
            button.textContent = button.dataset.originalLabel || button.textContent;
            delete button.dataset.originalLabel;
        }

        function showLogin() {
            document.body.classList.add('logged-out');
            $('login').classList.remove('hide');
            $('app').classList.add('hide');
        }

        function showApp() {
            document.body.classList.remove('logged-out');
            $('login').classList.add('hide');
            $('app').classList.remove('hide');
        }

        function clearSession(message = '') {
            token = null;
            localStorage.removeItem('teknotemu_token');
            showLogin();
            if (message) msg(message, true);
        }

        async function show() {
            if (!token) {
                showLogin();
                return;
            }

            try {
                const response = await fetch(api + '/auth/me', {
                    headers: {
                        Accept: 'application/json',
                        Authorization: 'Bearer ' + token
                    }
                });

                if (!response.ok) throw Error('Sesi berakhir. Silakan login kembali.');

                showApp();
                showView(getViewFromHash());
                load();
            } catch (error) {
                clearSession(error.message);
            }
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
                clearSession('Sesi berakhir. Silakan login kembali.');
                throw Error('Sesi berakhir')
            }
            if (!r.ok) throw Error(errorMessage(d, 'Request gagal'));
            return d
        }
        let page = 1;

        function getViewFromHash() {
            const view = window.location.hash.slice(1);

            return ['products', 'master'].includes(view) ? view : 'dashboard';
        }

        async function load(loadingButton = null) {
            setLoading(loadingButton, true, 'Memuat...');
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
                    if (d) d.onclick = () => removeProduct(p, d)
                });
                $('totalProducts').textContent = d.total ?? rows.length;
                const published = await call('/products?status=published&per_page=1');
                $('publishedProducts').textContent = published.total ?? 0;
                $('pageInfo').textContent = 'Halaman ' + (d.current_page ?? page) + ' dari ' + (d.last_page ?? 1);
                $('prevPage').disabled = (d.current_page ?? page) <= 1;
                $('nextPage').disabled = (d.current_page ?? page) >= (d.last_page ?? 1)
            } catch (e) {
                msg(e.message, true)
            } finally {
                setLoading(loadingButton, false)
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
            window.history.replaceState(null, '', '#' + x.dataset.view);
            showView(x.dataset.view)
            document.body.classList.remove('mobile-menu-open');
            $('mobileMenuToggle').setAttribute('aria-expanded', 'false');
        });
        window.addEventListener('hashchange', () => showView(getViewFromHash()));
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
            const submitButton = e.submitter || $('loginForm').querySelector('button[type="submit"]');
            setLoading(submitButton, true, 'Memeriksa...');
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
                if (!r.ok || !d.token) throw Error(errorMessage(d, 'Login gagal'));
                token = d.token;
                localStorage.setItem('teknotemu_token', token);
                await show()
            } catch (e) {
                msg(e.message, true)
            } finally {
                setLoading(submitButton, false)
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
                target.querySelectorAll('[data-tax-delete]').forEach(b => b.onclick = () => deleteTax(type, b.dataset.taxId, b))
            }
        }

        function openTax(type, item = null) {
            $('taxType').value = type;
            $('taxId').value = item?.id || '';
            $('taxName').value = item?.name || '';
            $('taxDescription').value = item?.description || '';
            $('taxDescriptionLabel').classList.toggle('hide', type === 'categories');
            $('taxImageLabel').classList.toggle('hide', type === 'categories');
            $('taxImage').value = '';
            $('taxTitle').textContent = (item ? 'Edit ' : 'Tambah ') + (type === 'categories' ? 'kategori' : 'collection');
            $('taxModal').classList.remove('hide')
        }

        function closeTax() {
            $('taxModal').classList.add('hide');
            $('taxForm').reset()
        }
        async function deleteTax(type, id, button) {
            if (!confirm('Hapus data ini?')) return;
            setLoading(button, true, 'Menghapus...');
            try {
                await call('/' + type + '/' + id, {
                    method: 'DELETE'
                });
                msg('Data dihapus');
                loadTaxonomies()
            } catch (e) {
                msg(e.message, true)
            } finally {
                setLoading(button, false)
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
            const submitButton = e.submitter || $('taxForm').querySelector('button[type="submit"]');
            setLoading(submitButton, true, 'Menyimpan...');
            try {
                const body = new FormData();
                body.append('name', $('taxName').value);
                body.append('description', $('taxDescription').value);
                if (type === 'collections' && $('taxImage').files[0]) body.append('image', $('taxImage').files[0]);
                if (id) body.append('_method', 'PUT');

                const response = await fetch(api + '/' + type + (id ? '/' + id : ''), {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        Authorization: 'Bearer ' + token
                    },
                    body
                });
                const result = await response.json();
                if (!response.ok) throw Error(errorMessage(result, 'Request gagal'));
                closeTax();
                msg('Data tersimpan');
                loadTaxonomies()
            } catch (e) {
                msg(e.message, true)
            } finally {
                setLoading(submitButton, false)
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
        async function removeProduct(p, button) {
            if (!confirm('Arsipkan produk ' + p.name + '?')) return;
            setLoading(button, true, 'Mengarsipkan...');
            try {
                await call('/products/' + p.id, {
                    method: 'DELETE'
                });
                msg('Produk diarsipkan');
                load()
            } catch (e) {
                msg(e.message, true)
            } finally {
                setLoading(button, false)
            }
        }
        $('addProduct').onclick = openModal;
        $('closeModal').onclick = closeModal;
        $('productModal').onclick = e => {
            if (e.target.id === 'productModal') closeModal()
        };
        $('productForm').onsubmit = async e => {
            e.preventDefault();
            const submitButton = e.submitter || $('productForm').querySelector('button[type="submit"]');
            setLoading(submitButton, true, 'Menyimpan...');
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
                if (!r.ok) throw Error(errorMessage(d, 'Produk gagal disimpan'));
                closeModal();
                msg(editingId ? 'Produk diperbarui' : 'Produk tersimpan');
                load()
            } catch (e) {
                msg(e.message, true)
            } finally {
                setLoading(submitButton, false)
            }
        };
        $('applyFilters').onclick = () => {
            page = 1;
            load($('applyFilters'))
        };
        $('clearFilters').onclick = () => {
            $('productSearch').value = '';
            $('statusFilter').value = '';
            $('categoryFilter').value = '';
            page = 1;
            load($('clearFilters'))
        };
        $('productSearch').onkeydown = e => {
            if (e.key === 'Enter') {
                page = 1;
                load($('applyFilters'))
            }
        };
        $('prevPage').onclick = () => {
            if (page > 1) {
                page--;
                load($('prevPage'))
            }
        };
        $('nextPage').onclick = () => {
            page++;
            load($('nextPage'))
        };
        $('importFile').onchange = async e => {
            if (!e.target.files[0]) return;
            const data = new FormData();
            data.append('file', e.target.files[0]);
            const importButton = document.querySelector('label[for="importFile"]');
            setLoading(importButton, true, 'Mengimpor...');
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
                if (!r.ok) throw Error(errorMessage(d, 'Import gagal'));
                msg(d.imported + ' produk berhasil diimport');
                e.target.value = '';
                page = 1;
                load()
            } catch (err) {
                msg(err.message, true)
            } finally {
                setLoading(importButton, false);
            }
        };
        $('refresh').onclick = () => load($('refresh'));
        $('downloadImportTemplate').onclick = () => {
            const headers = ['name', 'brand', 'price_idr', 'category', 'collection_name', 'spec', 'affiliate_url', 'status'];
            const blob = new Blob(['\ufeff' + headers.join(',') + '\r\n'], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');

            link.href = URL.createObjectURL(blob);
            link.download = 'template-import-produk.csv';
            link.click();
            URL.revokeObjectURL(link.href);
        };
        $('logout').onclick = async () => {
            setLoading($('logout'), true, 'Keluar...');
            try {
                if (token) await fetch(api + '/auth/logout', {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        Authorization: 'Bearer ' + token
                    }
                })
            } finally {
                clearSession();
                window.location.href = '/'
            }
        };
        show();
