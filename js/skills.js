function getBarColor(level) {
    if (level <= 25) {
        return 'rgb(244, 67, 54)';
    } else if (level <= 50) {
        return 'rgb(255, 152, 0)';
    } else if (level <= 75) {
        return 'rgb(255, 235, 59)';
    } else {
        return 'rgb(76, 175, 80)';
    }
}
document.addEventListener("DOMContentLoaded", function () { /* aspetta il caricamento della pagina html per eseguire */
    const skills = document.querySelectorAll('.skill-item');
    skills.forEach(skill => {
        const level = parseInt(skill.getAttribute('data-level'));
        const progressBar = skill.querySelector('.progress-bar');
        progressBar.style.backgroundColor = getBarColor(level);
    });
});