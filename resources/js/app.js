const adminLink = document.getElementById('adminLink');

if (adminLink) {
    const syncAdminLink = () => {
        const loggedIn = Boolean(localStorage.getItem('affiliateku_token'));

        adminLink.textContent = loggedIn ? 'Dashboard Admin' : 'Login Admin';
    };

    syncAdminLink();
    window.addEventListener('storage', syncAdminLink);
}
