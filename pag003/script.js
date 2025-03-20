// abre e fecha o menu ao clicar no ícone
document.addEventListener('DOMContentLoaded', function() {
    const profileIcon = document.querySelector('.profile-icon');
    const infoTab = document.getElementById('infoTab');

    profileIcon.addEventListener('click', function() {
        infoTab.classList.toggle('active');
    });

    // fecha o menu ao clicar fora
    document.addEventListener('click', function(event) {
        if (!infoTab.contains(event.target) && !profileIcon.contains(event.target)) {
            infoTab.classList.remove('active');
        }
    });
});