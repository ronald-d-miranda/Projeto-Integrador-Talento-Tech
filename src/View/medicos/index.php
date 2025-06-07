<?php
$title = 'Médicos - Clínica Saúde Mais';
ob_start();
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Médicos</h1>
        <?php if (isset($usuario) && $usuario['tipo'] === 'admin'): // Apenas admin pode cadastrar médicos diretamente ?>
        <a href="/auth/register" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Cadastrar Médico
        </a>
        <?php endif; ?>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Lista de Médicos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CRM</th>
                            <th>Especialização</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaMedicos">
                        <!-- Os dados serão carregados via JavaScript -->
                    </tbody>
                </table>
            </div>
            <div id="semMedicos" class="text-center py-3 d-none">
                <p>Nenhum médico encontrado.</p>
            </div>
            <div id="carregando" class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p>Carregando médicos...</p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$scripts = <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function() {
    const token = localStorage.getItem('token');
    const usuario = JSON.parse(localStorage.getItem('usuario') || '{}');
    
    if (!token || !usuario) {
        window.location.href = '/auth/login';
        return;
    }
    
    const tabelaMedicos = document.getElementById('tabelaMedicos');
    const semMedicos = document.getElementById('semMedicos');
    const carregando = document.getElementById('carregando');
    
    // Função para carregar médicos
    function carregarMedicos() {
        tabelaMedicos.innerHTML = '';
        semMedicos.classList.add('d-none');
        carregando.classList.remove('d-none');
        
        fetch('/medicos', {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        })
        .then(response => response.json())
        .then(data => {
            carregando.classList.add('d-none');
            
            if (data.sucesso && data.dados && data.dados.length > 0) {
                data.dados.forEach(medico => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>
                            <a href="/medicos/\${medico.id}" class="text-decoration-none">
                                \${medico.nome}
                            </a>
                        </td>
                        <td>\${medico.crm}</td>
                        <td>\${medico.especializacao}</td>
                        <td>\${medico.email}</td>
                        <td>\${medico.telefone}</td>
                        <td>
                            <a href="/medicos/\${medico.id}" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                            \${usuario.tipo === 'admin' ? `
                            <button class="btn btn-sm btn-danger ms-1" onclick="excluirMedico(\${medico.id})">
                                <i class="bi bi-trash"></i> Excluir
                            </button>` : ''}
                        </td>
                    `;
                    tabelaMedicos.appendChild(tr);
                });
            } else {
                semMedicos.classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar médicos:', error);
            carregando.classList.add('d-none');
            semMedicos.classList.remove('d-none');
            semMedicos.innerHTML = '<p>Erro ao carregar médicos. Tente novamente mais tarde.</p>';
        });
    }
    
    // Carregar médicos iniciais
    carregarMedicos();
    
    // Função para excluir médico
    window.excluirMedico = function(id) {
        if (confirm('Tem certeza que deseja excluir este médico?')) {
            fetch(`/api/medicos/\${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    alert('Médico excluído com sucesso!');
                    carregarMedicos();
                } else {
                    alert(data.mensagem || 'Erro ao excluir médico.');
                }
            })
            .catch(error => {
                console.error('Erro ao excluir médico:', error);
                alert('Erro ao excluir médico. Tente novamente mais tarde.');
            });
        }
    };
});
</script>
HTML;

include __DIR__ . '/../layouts/main.php';
?>

