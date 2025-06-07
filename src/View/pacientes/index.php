<?php
$title = 'Pacientes - Clínica Saúde Mais';
ob_start();
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Pacientes</h1>
        <a href="/auth/register" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Cadastrar Paciente
        </a>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Lista de Pacientes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaPacientes">
                        <!-- Os dados serão carregados via JavaScript -->
                    </tbody>
                </table>
            </div>
            <div id="semPacientes" class="text-center py-3 d-none">
                <p>Nenhum paciente encontrado.</p>
            </div>
            <div id="carregando" class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p>Carregando pacientes...</p>
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
    
    if (!token || !usuario || usuario.tipo !== 'medico') {
        window.location.href = '/auth/login';
        return;
    }
    
    const tabelaPacientes = document.getElementById('tabelaPacientes');
    const semPacientes = document.getElementById('semPacientes');
    const carregando = document.getElementById('carregando');
    
    // Função para carregar pacientes
    function carregarPacientes() {
        tabelaPacientes.innerHTML = '';
        semPacientes.classList.add('d-none');
        carregando.classList.remove('d-none');
        
        fetch('/api/pacientes', {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        })
        .then(response => response.json())
        .then(data => {
            carregando.classList.add('d-none');
            
            if (data.sucesso && data.dados && data.dados.length > 0) {
                data.dados.forEach(paciente => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>
                            <a href="/pacientes/\${paciente.id}" class="text-decoration-none">
                                \${paciente.nome}
                            </a>
                        </td>
                        <td>\${paciente.cpf}</td>
                        <td>\${paciente.email}</td>
                        <td>\${paciente.telefone}</td>
                        <td>
                            <a href="/pacientes/\${paciente.id}" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                            <button class="btn btn-sm btn-danger ms-1" onclick="excluirPaciente(\${paciente.id})">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        </td>
                    `;
                    tabelaPacientes.appendChild(tr);
                });
            } else {
                semPacientes.classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar pacientes:', error);
            carregando.classList.add('d-none');
            semPacientes.classList.remove('d-none');
            semPacientes.innerHTML = '<p>Erro ao carregar pacientes. Tente novamente mais tarde.</p>';
        });
    }
    
    // Carregar pacientes iniciais
    carregarPacientes();
    
    // Função para excluir paciente
    window.excluirPaciente = function(id) {
        if (confirm('Tem certeza que deseja excluir este paciente?')) {
            fetch(`/api/pacientes/\${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    alert('Paciente excluído com sucesso!');
                    carregarPacientes();
                } else {
                    alert(data.mensagem || 'Erro ao excluir paciente.');
                }
            })
            .catch(error => {
                console.error('Erro ao excluir paciente:', error);
                alert('Erro ao excluir paciente. Tente novamente mais tarde.');
            });
        }
    };
});
</script>
HTML;

include __DIR__ . '/../layouts/main.php';
?>
