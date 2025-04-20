function setTheme(themeName) {
    localStorage.setItem('theme', themeName);
    document.documentElement.setAttribute('data-theme', themeName);
}

function toggleTheme() {
    const currentTheme = localStorage.getItem('theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    setTheme(newTheme);
    document.getElementById('theme-switch').checked = newTheme === 'dark';
}

// Initialize theme on page load
(function () {
    const savedTheme = localStorage.getItem('theme') || 'light';
    setTheme(savedTheme);
    document.getElementById('theme-switch').checked = savedTheme === 'dark';
})();