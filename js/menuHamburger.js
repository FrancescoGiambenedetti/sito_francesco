function toggleMenu() {
    const menu = document.getElementById('sideMenu');
    if (menu.style.width === '250px') {
        menu.style.width = '0';
    } else {
        menu.style.width = '250px';
    }
}
document.querySelectorAll('#sideMenu ul li a').forEach(link => {
    link.addEventListener('click', () => {
        toggleMenu();
    });
});