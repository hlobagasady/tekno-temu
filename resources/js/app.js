const adminLink = document.getElementById('adminLink');
const searchForm = document.getElementById('search');
const searchInput = document.getElementById('query');
const searchSuggestions = document.getElementById('searchSuggestions');

let suggestionController;
let suggestionTimer;

const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, (character) => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    "'": '&#039;',
    '"': '&quot;',
}[character]));

function closeSearchSuggestions() {
    searchSuggestions.hidden = true;
    searchSuggestions.innerHTML = '';
}

function renderSearchSuggestions(products) {
    searchSuggestions.innerHTML = products.map((product) => `<button class="search-suggestion" type="button" role="option" data-search-value="${escapeHtml(product.name)}"><img src="${escapeHtml(product.image_url || 'https://placehold.co/80x80/F5F0E6/5363B6?text=Produk')}" alt=""><span>${escapeHtml(product.name)}</span></button>`).join('');
    searchSuggestions.hidden = products.length === 0;

    searchSuggestions.querySelectorAll('[data-search-value]').forEach((suggestion) => {
        suggestion.onclick = () => {
            searchInput.value = suggestion.dataset.searchValue;
            closeSearchSuggestions();
            searchForm.requestSubmit();
        };
    });
}

async function loadSearchSuggestions() {
    const search = searchInput.value.trim();

    suggestionController?.abort();
    closeSearchSuggestions();

    if (search.length < 2) return;

    suggestionController = new AbortController();

    try {
        const response = await fetch('/api/products?' + new URLSearchParams({ search, per_page: '5' }), {
            signal: suggestionController.signal,
        });
        if (!response.ok) return;

        const result = await response.json();
        renderSearchSuggestions(result.data || result);
    } catch (error) {
        if (error.name !== 'AbortError') closeSearchSuggestions();
    }
}

if (searchForm && searchInput && searchSuggestions) {
    searchInput.addEventListener('input', () => {
        clearTimeout(suggestionTimer);
        suggestionTimer = setTimeout(loadSearchSuggestions, 180);
    });
    searchInput.addEventListener('focus', loadSearchSuggestions);
    searchInput.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeSearchSuggestions();
    });
    document.addEventListener('click', (event) => {
        if (!searchForm.contains(event.target)) closeSearchSuggestions();
    });
}

if (adminLink) {
    const syncAdminLink = () => {
        const loggedIn = Boolean(localStorage.getItem('affiliateku_token'));

        adminLink.textContent = loggedIn ? 'Dashboard Admin' : 'Login Admin';
    };

    syncAdminLink();
    window.addEventListener('storage', syncAdminLink);
}
