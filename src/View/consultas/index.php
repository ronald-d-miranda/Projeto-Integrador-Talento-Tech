<?php
$title = 'Consultas - Clínica Saúde Mais';
ob_start();
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Consultas</h1>
        <?php if (isset($usuario) && $usuario['tipo'] === 'paciente'): ?>
        <a href="/consultas/agendar" class="btn btn-primary">
            <i class="bi bi-calendar-plus"></i> Agendar Consulta
        </a>
        <?php endif; ?>
    </div>
    
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">Lista de Consultas</h5>
                </div>
                <div class="col-md-6">
                    <form id="filtroForm" class="d-flex">
                        <input type="date" id="filtroData" name="data" class="form-control me-2" value="<?= isset($_GET['data']) ? $_GET['data'] : date('Y-m-d') ?>">
                        <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Horário</th>
                            <?php if (isset($usuario) && $usuario['tipo'] === 'medico'): ?>
                            <th>Paciente</th>
                            <?php else: ?>
                            <th>Médico</th>
                            <?php endif; ?>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaConsultas">
                        <!-- Os dados serão carregados via JavaScript -->
                    </tbody>
                </table>
            </div>
            <div id="semConsultas" class="text-center py-3 d-none">
                <p>Nenhuma consulta encontrada para esta data.</p>
            </div>
            <div id="carregando" class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p>Carregando consultas...</p>
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
    
    const tabelaConsultas = document.getElementById('tabelaConsultas');
    const semConsultas = document.getElementById('semConsultas');
    const carregando = document.getElementById('carregando');
    const filtroForm = document.getElementById('filtroForm');
    const filtroData = document.getElementById('filtroData');
    
    // Função para carregar consultas
    function carregarConsultas(data = null) {
        tabelaConsultas.innerHTML = '';
        semConsultas.classList.add('d-none');
        carregando.classList.remove('d-none');
        
        let url = '/api/consultas';
        if (data) {
            url += '?data=' + data;
        }
        
        fetch(url, {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        })
        .then(response => response.json())
        .then(data => {
            carregando.classList.add('d-none');
            
            if (data.sucesso && data.dados && data.dados.length > 0) {
                data.dados.forEach(consulta => {
                    const dataConsulta = new Date(consulta.data);
                    const dataFormatada = dataConsulta.toLocaleDateString('pt-BR');
                    const horaFormatada = dataConsulta.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                    
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>\${dataFormatada}</td>
                        <td>\${horaFormatada}</td>
                        <td>\${usuario.tipo === 'medico' ? consulta.nome_paciente : consulta.nome_medico}</td>
                        <td>
                            \${consulta.diagnostico ? 
                                '<span class="badge bg-success">Realizada</span>' : 
                                '<span class="badge bg-warning">Pendente</span>'}
                        </td>
                        <td>
                            <a href="/consultas/\${consulta.id}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                            \${!consulta.diagnostico ? 
                                `<button class="btn btn-sm btn-danger ms-1" onclick="cancelarConsulta(\${consulta.id})">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </button>` : ''}
                        </td>
                    `;
                    tabelaConsultas.appendChild(tr);
                });
            } else {
                semConsultas.classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar consultas:', error);
            carregando.classList.add('d-none');
            semConsultas.classList.remove('d-none');
            semConsultas.innerHTML = '<p>Erro ao carregar consultas. Tente novamente mais tarde.</p>';
        });
    }
    
    // Carregar consultas iniciais
    const urlParams = new URLSearchParams(window.location.search);
    const dataFiltro = urlParams.get('data');
    if (dataFiltro) {
        filtroData.value = dataFiltro;
        carregarConsultas(dataFiltro);
    } else {
        carregarConsultas();
    }
    
    // Filtrar consultas por data
    filtroForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const data = filtroData.value;
        carregarConsultas(data);
        
        // Atualizar URL com o filtro
        const url = new URL(window.location);
        url.searchParams.set('data', data);
        window.history.pushState({}, '', url);
    });
    
    // Função para cancelar consulta
    window.cancelarConsulta = function(id) {
        if (confirm('Tem certeza que deseja cancelar esta consulta?')) {
            fetch(`/api/consultas/\${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    alert('Consulta cancelada com sucesso!');
                    // Recarregar consultas
                    carregarConsultas(filtroData.value);
                } else {
                    alert(data.mensagem || 'Erro ao cancelar consulta.');
                }
            })
            .catch(error => {
                console.error('Erro ao cancelar consulta:', error);
                alert('Erro ao cancelar consulta. Tente novamente mais tarde.');
            });
        }
    };
});
</script>
HTML;

include __DIR__ . '/../layouts/main.php';
?>

