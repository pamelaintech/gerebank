const ticker = document.querySelector('.ticker ul');

ticker.addEventListener('mouseover', () => {
    ticker.style.animationPlayState = 'paused';
});

ticker.addEventListener('mouseout', () => {
    ticker.style.animationPlayState = 'running';
});
// Modal de boas-vindas
const modal = document.getElementById('welcomeModal');
const span = document.getElementsByClassName('close')[0];

// Abre o modal após um curto atraso
window.onload = function() {
    setTimeout(() => {
        modal.style.display = 'block';
    }, 500); // 500ms de atraso
};

// Fecha o modal quando o usuário clica no "x"
span.onclick = function() {
    modal.style.display = 'none';
};

// Fecha o modal se o usuário clicar fora do conteúdo
window.onclick = function(event) {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
};
