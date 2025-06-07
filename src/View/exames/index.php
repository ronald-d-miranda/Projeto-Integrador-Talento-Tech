<?php
$title = 'Exames - Clínica Saúde Mais';
ob_start();
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Exames</h1>
        <?php if (isset($usuario) && $usuario['tipo'] === 'medico'): ?>
        <a href="/exames/novo" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Registrar Exame
        </a>
        <?php endif; ?>
    </div>
    
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">Lista de Exames</h5>
                </div>
                <div class="col-md-6">
                    <form id="filtroForm" class="d-flex">
                        <input type="date" id="dataInicio" name="data_inicio" class="form-control me-2" placeholder="Data inicial">
                        <input type="date" id="dataFim" name="data_fim" class="form-control me-2" placeholder="Data final">
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
                            <th>Tipo</th>
                            <?php if (isset($usuario) && $usuario['tipo'] === 'medico'): ?>
                            <th>Paciente</th>
                            <?php endif; ?>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaExames">
                        <!-- Os dados serão carregados via JavaScript -->
                    </tbody>
                </table>
            </div>
            <div id="semExames" class="text-center py-3 d-none">
                <p>Nenhum exame encontrado.</p>
            </div>
            <div id="carregando" class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p>Carregando exames...</p>
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
    
    const tabelaExames = document.getElementById('tabelaExames');
    const semExames = document.getElementById('semExames');
    const carregando = document.getElementById('carregando');
    const filtroForm = document.getElementById('filtroForm');
    const dataInicio = document.getElementById('dataInicio');
    const dataFim = document.getElementById('dataFim');
    
    // Função para carregar exames
    function carregarExames(filtros = {}) {
        tabelaExames.innerHTML = '';
        semExames.classList.add('d-none');
        carregando.classList.remove('d-none');
        
        let url = '/exames';
        const queryParams = [];
        
        if (filtros.dataInicio) {
            queryParams.push(`data_inicio=\${filtros.dataInicio}`);
        }
        
        if (filtros.dataFim) {
            queryParams.push(`data_fim=\${filtros.dataFim}`);
        }
        
        if (queryParams.length > 0) {
            url += '?' + queryParams.join('&');
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
                data.dados.forEach(exame => {
                    const dataExame = new Date(exame.data);
                    const dataFormatada = dataExame.toLocaleDateString('pt-BR');
                    
                    const tr = document.createElement('tr');
                    
                    let html = `
                        <td>\${dataFormatada}</td>
                        <td>\${exame.tipo}</td>
                    `;
                    
                    if (usuario.tipo === 'medico') {
                        html += `<td>\${exame.nome_paciente}</td>`;
                    }
                    
                    html += `
                        <td>
                            <a href="/exames/\${exame.id}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                    `;
                    
                    if (usuario.tipo === 'medico') {
                        html += `
                            <button class="btn btn-sm btn-danger ms-1" onclick="excluirExame(\${exame.id})">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        `;
                    }
                    
                    html += `</td>`;
                    
                    tr.innerHTML = html;
                    tabelaExames.appendChild(tr);
                });
            } else {
                semExames.classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar exames:', error);
            carregando.classList.add('d-none');
            semExames.classList.remove('d-none');
            semExames.innerHTML = '<p>Erro ao carregar exames. Tente novamente mais tarde.</p>';
        });
    }
    
    // Carregar exames iniciais
    const urlParams = new URLSearchParams(window.location.search);
    const dataInicioParam = urlParams.get('data_inicio');
    const dataFimParam = urlParams.get('data_fim');
    
    if (dataInicioParam) {
        dataInicio.value = dataInicioParam;
    }
    
    if (dataFimParam) {
        dataFim.value = dataFimParam;
    }
    
    carregarExames({
        dataInicio: dataInicioParam,
        dataFim: dataFimParam
    });
    
    // Filtrar exames
    filtroForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const filtros = {
            dataInicio: dataInicio.value,
            dataFim: dataFim.value
        };
        
        carregarExames(filtros);
        
        // Atualizar URL com os filtros
        const url = new URL(window.location);
        
        if (filtros.dataInicio) {
            url.searchParams.set('data_inicio', filtros.dataInicio);
        } else {
            url.searchParams.delete('data_inicio');
        }
        
        if (filtros.dataFim) {
            url.searchParams.set('data_fim', filtros.dataFim);
        } else {
            url.searchParams.delete('data_fim');
        }
        
        window.history.pushState({}, '', url);
    });
    
    // Função para excluir exame
    window.excluirExame = function(id) {
        if (confirm('Tem certeza que deseja excluir este exame?')) {
            fetch(`/api/exames/\${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    alert('Exame excluído com sucesso!');
                    // Recarregar exames
                    carregarExames({
                        dataInicio: dataInicio.value,
                        dataFim: dataFim.value
                    });
                } else {
                    alert(data.mensagem || 'Erro ao excluir exame.');
                }
            })
            .catch(error => {
                console.error('Erro ao excluir exame:', error);
                alert('Erro ao excluir exame. Tente novamente mais tarde.');
            });
        }
    };
});
</script>
HTML;

include __DIR__ . '/../layouts/main.php';
?>

