// Configuração do SweetAlert2
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    background: "var(--verde-mato)",
    color: "white",
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

// Contadores de caracteres
document.getElementById('articleTitle').addEventListener('input', function() {
    document.getElementById('titleCount').textContent = this.value.length;
});

document.getElementById('articleDescription').addEventListener('input', function() {
    document.getElementById('descCount').textContent = this.value.length;
});

// Validação e envio do formulário
document.getElementById('articleForm').addEventListener('submit', function(e) {

    // Validações
    const title = document.getElementById('articleTitle').value.trim();
    const description = document.getElementById('articleDescription').value.trim();
    const content = document.getElementById('articleContent').innerHTML.trim();
    const topics = document.querySelectorAll('.topic-container');

    if (!title) {
        Toast.fire({
            icon: "error",
            title: "Por favor, digite um título para o artigo"
        });
        return;
    }

    if (title.length < 10) {
        Toast.fire({
            icon: "error",
            title: "O título deve ter no mínimo 10 caracteres"
        });
        return;
    }

    if (!description) {
        Toast.fire({
            icon: "error",
            title: "Por favor, digite uma descrição para o artigo"
        });
        return;
    }

    if (description.length < 20) {
        Toast.fire({
            icon: "error",
            title: "A descrição deve ter no mínimo 20 caracteres"
        });
        return;
    }

    if (!content || content === '<br>') {
        Toast.fire({
            icon: "error",
            title: "Por favor, adicione conteúdo ao artigo"
        });
        return;
    }

    if (content.length < 100) {
        Toast.fire({
            icon: "error",
            title: "O conteúdo deve ter no mínimo 100 caracteres"
        });
        return;
    }
});

// Função para escapar HTML
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}