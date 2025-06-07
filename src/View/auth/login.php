<?php
$title = 'Login - Clínica Saúde Mais';
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card mt-5">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Login</h4>
            </div>
            <div class="card-body">
                <form id="loginForm" method="post" action="/api/auth/login">
                    <div class="mb-3">
                        <label for="credencial" class="form-label">E-mail ou CPF</label>
                        <input type="text" class="form-control" id="credencial" name="credencial" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">Não tem uma conta? <a href="/auth/register">Cadastre-se</a></p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$scripts = <<<HTML
<script>
function aplicarMascaraCPF(cpf) {
    return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
}

document.addEventListener('DOMContentLoaded', function() {
    const credencialInput = document.getElementById('credencial');
    
    // Adiciona um evento para aplicar a máscara enquanto o usuário digita
    credencialInput.addEventListener('input', function(e) {
        let valor = e.target.value.replace(/\D/g, ''); // Remove qualquer caractere não numérico
        
        if (valor.length === 11 && /^\d{11}$/.test(valor)) {  // Verifica se tem exatamente 11 números
            e.target.value = aplicarMascaraCPF(valor);
        } else {
            e.target.value = e.target.value;  // Caso contrário, assume que é um e-mail e não aplica a máscara
        }
    });
    const loginForm = document.getElementById('loginForm');
    
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const credencial = document.getElementById('credencial').value;
        const senha = document.getElementById('senha').value;
        
        // Enviar requisição AJAX
        fetch('/api/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                credencial: credencial,
                senha: senha
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                // Redirecionar para a página inicial. O token será salvo em um cookie HTTP-only pelo backend.
                localStorage.setItem('token', data.token);
                localStorage.setItem('usuario', JSON.stringify(data.usuario));  
                window.location.href = '/home';
            } else {
                // Exibir mensagem de erro
                alert(data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Ocorreu um erro ao tentar fazer login. Tente novamente.');
        });
    });
});
</script>
HTML;

include __DIR__ . '/../layouts/main.php';
?>


