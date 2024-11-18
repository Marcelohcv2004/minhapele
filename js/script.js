// script.js

// Menu responsivo
const menuToggle = document.getElementById('mobile-menu');
const navList = document.querySelector('.nav-list');

menuToggle.addEventListener('click', function() {
    navList.classList.toggle('active');
});

// Copiar ID do Usuário ao Clicar no Nome
document.addEventListener('DOMContentLoaded', function() {
    const usernames = document.querySelectorAll('.username');

    usernames.forEach(function(username) {
        username.addEventListener('click', function(event) {
            event.preventDefault();
            const userId = this.getAttribute('data-user-id');
            copyToClipboard(userId);
            alert('ID do usuário copiado: ' + userId);
        });
    });
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        console.log('Texto copiado com sucesso');
    }, function(err) {
        console.error('Erro ao copiar o texto: ', err);
    });
}

