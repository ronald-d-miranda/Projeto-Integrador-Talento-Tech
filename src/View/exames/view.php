<?php
$title = 'Detalhes do Exame - Clínica Saúde Mais';
ob_start();
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Detalhes do Exame</h1>
        <a href="/exames" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Voltar para Exames
        </a>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Informações do Exame</h5>
        </div>
        <div class="card-body">
            <div id="carregando" class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p>Carregando dados do exame...</p>
            </div>
            
            <div id="dadosExame" class="d-none">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Data</h6>
                        <p id="data" class="lead"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Tipo</h6>
                        <p id="tipo" class="lead"></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Paciente</h6>
                        <p id="paciente" class="lead"></p>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h6>Resultado</h6>
                        <div id="resultado" class="p-3 bg-light rounded"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Botões de ação (apenas para médicos) -->
    <div id="acoesBotoes" class="mt-4 d-none">
        <button id="btnEditar" class="btn btn-primary me-2">
            <i class="bi bi-pencil"></i> Editar Exame
        </button>
        <button id="btnExcluir" class="btn btn-danger">
            <i class="bi bi-trash"></i> Excluir Exame
        </button>
    </div>
    
    <!-- Formulário de edição (inicialmente oculto) -->
    <div id="formEdicaoExame" class="card mt-4 d-none">
        <div class="card-header">
            <h5 class="mb-0">Editar Exame</h5>
        </div>
        <div class="card-body">
            <form id="editarExameForm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="edit_data" class="form-label">Data</label>
                        <input type="date" class="form-control" id="edit_data" name="data" required max="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="edit_tipo" class="form-label">Tipo de Exame</label>
                        <select class="form-select" id="edit_tipo" name="tipo" required>
                            <option value="">Selecione o tipo de exame</option>
                            <option value="Hemograma">Hemograma</option>
                            <option value="Glicemia">Glicemia</option>
                            <option value="Colesterol">Colesterol</option>
                            <option value="Triglicerídeos">Triglicerídeos</option>
                            <option value="Urina">Urina</option>
                            <option value="Fezes">Fezes</option>
                            <option value="Raio-X">Raio-X</option>
                            <option value="Ultrassonografia">Ultrassonografia</option>
                            <option value="Tomografia">Tomografia</option>
                            <option value="Ressonância Magnética">Ressonância Magnética</option>
                            <option value="Eletrocardiograma">Eletrocardiograma</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="edit_resultado" class="form-label">Resultado</label>
                    <textarea class="form-control" id="edit_resultado" name="resultado" rows="5" required></textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="button" id="btnCancelarEdicao" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
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
    
    // Obter ID do exame da URL
    const urlParts = window.location.pathname.split('/');
    const exameId = urlParts[urlParts.length - 1];
    
    if (!exameId || isNaN(exameId)) {
        window.location.href = '/exames';
        return;
    }
    
    const carregando = document.getElementById('carregando');
    const dadosExame = document.getElementById('dadosExame');
    const acoesBotoes = document.getElementById('acoesBotoes');
    const btnEditar = document.getElementById('btnEditar');
    const btnExcluir = document.getElementById('btnExcluir');
    const formEdicaoExame = document.getElementById('formEdicaoExame');
    const btnCancelarEdicao = document.getElementById('btnCancelarEdicao');
    const editarExameForm = document.getElementById('editarExameForm');
    
    let exameAtual = null;
    
    // Carregar dados do exame
    fetch(`/api/exames/\${exameId}`, {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => response.json())
    .then(data => {
        carregando.classList.add('d-none');
        
        if (data.sucesso && data.dados) {
            dadosExame.classList.remove('d-none');
            
            exameAtual = data.dados;
            const dataExame = new Date(exameAtual.data);
            const dataFormatada = dataExame.toLocaleDateString('pt-BR');
            
            document.getElementById('data').textContent = dataFormatada;
            document.getElementById('tipo').textContent = exameAtual.tipo;
            document.getElementById('paciente').textContent = exameAtual.nome_paciente;
            document.getElementById('resultado').textContent = exameAtual.resultado;
            
            // Mostrar botões de ação apenas para médicos
            if (usuario.tipo === 'medico') {
                acoesBotoes.classList.remove('d-none');
                
                // Preencher formulário de edição
                document.getElementById('edit_data').value = exameAtual.data.split('T')[0];
                document.getElementById('edit_tipo').value = exameAtual.tipo;
                document.getElementById('edit_resultado').value = exameAtual.resultado;
            }
        } else {
            alert('Erro ao carregar dados do exame: ' + (data.mensagem || 'Exame não encontrado.'));
            window.location.href = '/exames';
        }
    })
    .catch(error => {
        console.error('Erro ao carregar exame:', error);
        carregando.classList.add('d-none');
        alert('Erro ao carregar dados do exame. Tente novamente mais tarde.');
    });
    
    // Botão Editar
    btnEditar.addEventListener('click', function() {
        dadosExame.classList.add('d-none');
        acoesBotoes.classList.add('d-none');
        formEdicaoExame.classList.remove('d-none');
    });
    
    // Botão Cancelar Edição
    btnCancelarEdicao.addEventListener('click', function() {
        formEdicaoExame.classList.add('d-none');
        dadosExame.classList.remove('d-none');
        acoesBotoes.classList.remove('d-none');
    });
    
    // Botão Excluir
    btnExcluir.addEventListener('click', function() {
        if (confirm('Tem certeza que deseja excluir este exame?')) {
            fetch(`/api/exames/\${exameId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    alert('Exame excluído com sucesso!');
                    window.location.href = '/exames';
                } else {
                    alert(data.mensagem || 'Erro ao excluir exame.');
                }
            })
            .catch(error => {
                console.error('Erro ao excluir exame:', error);
                alert('Erro ao excluir exame. Tente novamente mais tarde.');
            });
        }
    });
    
    // Formulário de Edição
    editarExameForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const data = document.getElementById('edit_data').value;
        const tipo = document.getElementById('edit_tipo').value;
        const resultado = document.getElementById('edit_resultado').value;
        
        fetch(`/api/exames/\${exameId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify({
                data: data,
                tipo: tipo,
                resultado: resultado
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                alert('Exame atualizado com sucesso!');
                window.location.reload();
            } else {
                alert(data.mensagem || 'Erro ao atualizar exame.');
            }
        })
        .catch(error => {
            console.error('Erro ao atualizar exame:', error);
            alert('Erro ao atualizar exame. Tente novamente mais tarde.');
        });
    });
});
</script>
HTML;

include __DIR__ . '/../layouts/main.php';
?>

