<?php
$title = 'Detalhes da Consulta - Clínica Saúde Mais';
ob_start();
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Detalhes da Consulta</h1>
        <a href="/consultas" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Voltar para Consultas
        </a>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Informações da Consulta</h5>
        </div>
        <div class="card-body">
            <div id="carregando" class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p>Carregando dados da consulta...</p>
            </div>
            
            <div id="dadosConsulta" class="d-none">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Data e Hora</h6>
                        <p id="dataHora" class="lead"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Tipo</h6>
                        <p id="tipo" class="lead"></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Médico</h6>
                        <p id="medico" class="lead"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Paciente</h6>
                        <p id="paciente" class="lead"></p>
                    </div>
                </div>
                
                <div id="resultadoConsulta">
                    <hr>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h6>Diagnóstico</h6>
                            <p id="diagnostico" class="lead"></p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h6>Receita</h6>
                            <p id="receita" class="lead"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Formulário de registro de consulta (apenas para médicos) -->
    <div id="formRegistroConsulta" class="card d-none">
        <div class="card-header">
            <h5 class="mb-0">Registrar Consulta</h5>
        </div>
        <div class="card-body">
            <form id="registrarConsultaForm">
                <div class="mb-3">
                    <label for="diagnostico_input" class="form-label">Diagnóstico</label>
                    <textarea class="form-control" id="diagnostico_input" name="diagnostico" rows="3" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="receita_input" class="form-label">Receita</label>
                    <textarea class="form-control" id="receita_input" name="receita" rows="3" required></textarea>
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="presencial_input" name="presencial" checked>
                    <label class="form-check-label" for="presencial_input">
                        Consulta presencial
                    </label>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Registrar Consulta</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Botão para cancelar consulta -->
    <div id="acoesBotoes" class="mt-4 d-none">
        <button id="btnCancelar" class="btn btn-danger">
            <i class="bi bi-x-circle"></i> Cancelar Consulta
        </button>
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
    
    // Obter ID da consulta da URL
    const urlParts = window.location.pathname.split('/');
    const consultaId = urlParts[urlParts.length - 1];
    
    if (!consultaId || isNaN(consultaId)) {
        window.location.href = '/consultas';
        return;
    }
    
    const carregando = document.getElementById('carregando');
    const dadosConsulta = document.getElementById('dadosConsulta');
    const resultadoConsulta = document.getElementById('resultadoConsulta');
    const formRegistroConsulta = document.getElementById('formRegistroConsulta');
    const acoesBotoes = document.getElementById('acoesBotoes');
    const btnCancelar = document.getElementById('btnCancelar');
    
    // Carregar dados da consulta
    fetch(`/api/consultas/\${consultaId}`, {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => response.json())
    .then(data => {
        carregando.classList.add('d-none');
        
        if (data.sucesso && data.dados) {
            dadosConsulta.classList.remove('d-none');
            
            const consulta = data.dados;
            const dataConsulta = new Date(consulta.data);
            const dataFormatada = dataConsulta.toLocaleDateString('pt-BR');
            const horaFormatada = dataConsulta.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
            
            document.getElementById('dataHora').textContent = `\${dataFormatada} às \${horaFormatada}`;
            document.getElementById('tipo').textContent = consulta.presencial ? 'Presencial' : 'Teleconsulta';
            document.getElementById('medico').textContent = consulta.nome_medico;
            document.getElementById('paciente').textContent = consulta.nome_paciente;
            
            // Verificar se a consulta já foi realizada
            if (consulta.diagnostico) {
                document.getElementById('diagnostico').textContent = consulta.diagnostico;
                document.getElementById('receita').textContent = consulta.receita;
                resultadoConsulta.classList.remove('d-none');
            } else {
                resultadoConsulta.classList.add('d-none');
                
                // Mostrar formulário de registro apenas para médicos
                if (usuario.tipo === 'medico' && usuario.id == consulta.medico_id) {
                    formRegistroConsulta.classList.remove('d-none');
                }
                
                // Mostrar botão de cancelar
                const hoje = new Date();
                hoje.setHours(0, 0, 0, 0);
                const dataConsultaSemHora = new Date(dataConsulta);
                dataConsultaSemHora.setHours(0, 0, 0, 0);
                
                if (dataConsultaSemHora >= hoje) {
                    acoesBotoes.classList.remove('d-none');
                }
            }
            
            // Configurar formulário de registro
            if (usuario.tipo === 'medico') {
                const registrarForm = document.getElementById('registrarConsultaForm');
                registrarForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const diagnostico = document.getElementById('diagnostico_input').value;
                    const receita = document.getElementById('receita_input').value;
                    const presencial = document.getElementById('presencial_input').checked;
                    
                    fetch(`/api/consultas/\${consultaId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify({
                            diagnostico: diagnostico,
                            receita: receita,
                            presencial: presencial
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.sucesso) {
                            alert('Consulta registrada com sucesso!');
                            window.location.reload();
                        } else {
                            alert(data.mensagem || 'Erro ao registrar consulta.');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao registrar consulta:', error);
                        alert('Erro ao registrar consulta. Tente novamente mais tarde.');
                    });
                });
            }
            
            // Configurar botão de cancelar
            btnCancelar.addEventListener('click', function() {
                if (confirm('Tem certeza que deseja cancelar esta consulta?')) {
                    fetch(`/api/consultas/\${consultaId}`, {
                        method: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + token
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.sucesso) {
                            alert('Consulta cancelada com sucesso!');
                            window.location.href = '/consultas';
                        } else {
                            alert(data.mensagem || 'Erro ao cancelar consulta.');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao cancelar consulta:', error);
                        alert('Erro ao cancelar consulta. Tente novamente mais tarde.');
                    });
                }
            });
        } else {
            alert('Erro ao carregar dados da consulta: ' + (data.mensagem || 'Consulta não encontrada.'));
            window.location.href = '/consultas';
        }
    })
    .catch(error => {
        console.error('Erro ao carregar consulta:', error);
        carregando.classList.add('d-none');
        alert('Erro ao carregar dados da consulta. Tente novamente mais tarde.');
    });
});
</script>
HTML;

include __DIR__ . '/../layouts/main.php';
?>

