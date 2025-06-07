<?php
$title = 'Agendar Consulta - Clínica Saúde Mais';
ob_start();
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Agendar Consulta</h1>
        <a href="/consultas" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Voltar para Consultas
        </a>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Formulário de Agendamento</h5>
        </div>
        <div class="card-body">
            <form id="agendarConsultaForm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="medico_id" class="form-label">Médico</label>
                        <select class="form-select" id="medico_id" name="medico_id" required>
                            <option value="">Selecione um médico</option>
                            <!-- Opções serão carregadas via JavaScript -->
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="especializacao" class="form-label">Especialização</label>
                        <input type="text" class="form-control" id="especializacao" readonly>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="data" class="form-label">Data</label>
                        <input type="date" class="form-control" id="data" name="data" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="hora" class="form-label">Hora</label>
                        <select class="form-select" id="hora" name="hora" required disabled>
                            <option value="">Selecione um horário</option>
                            <!-- Horários serão carregados via JavaScript -->
                        </select>
                    </div>
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="presencial" name="presencial" checked>
                    <label class="form-check-label" for="presencial">
                        Consulta presencial
                    </label>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Agendar Consulta</button>
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
    
    if (!token || !usuario || usuario.tipo !== 'paciente') {
        window.location.href = '/auth/login';
        return;
    }
    
    const medicoSelect = document.getElementById('medico_id');
    const especializacaoInput = document.getElementById('especializacao');
    const dataInput = document.getElementById('data');
    const horaSelect = document.getElementById('hora');
    const agendarForm = document.getElementById('agendarConsultaForm');
    
    // Carregar lista de médicos
    fetch('/api/medicos', {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.sucesso && data.dados) {
            data.dados.forEach(medico => {
                const option = document.createElement('option');
                option.value = medico.id;
                option.textContent = medico.nome;
                option.dataset.especializacao = medico.especializacao;
                medicoSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Erro ao carregar médicos:', error);
        alert('Erro ao carregar lista de médicos. Tente novamente mais tarde.');
    });
    
    // Atualizar especialização ao selecionar médico
    medicoSelect.addEventListener('change', function() {
        const selectedOption = medicoSelect.options[medicoSelect.selectedIndex];
        especializacaoInput.value = selectedOption.dataset.especializacao || '';
        
        // Resetar data e hora
        dataInput.value = '';
        horaSelect.innerHTML = '<option value="">Selecione um horário</option>';
        horaSelect.disabled = true;
    });
    
    // Carregar horários disponíveis ao selecionar data
    dataInput.addEventListener('change', function() {
        const medicoId = medicoSelect.value;
        const data = dataInput.value;
        
        if (!medicoId || !data) {
            horaSelect.innerHTML = '<option value="">Selecione um horário</option>';
            horaSelect.disabled = true;
            return;
        }
        
        // Limpar horários anteriores
        horaSelect.innerHTML = '<option value="">Carregando horários...</option>';
        horaSelect.disabled = true;
        
        // Horários padrão de atendimento (8h às 18h, a cada 30 minutos)
        const horarios = [];
        for (let hora = 8; hora < 18; hora++) {
            horarios.push(`\${hora.toString().padStart(2, '0')}:00`);
            horarios.push(`\${hora.toString().padStart(2, '0')}:30`);
        }
        
        // Verificar disponibilidade para cada horário
        const promises = horarios.map(hora => {
            return fetch(`/api/medicos/\${medicoId}/disponibilidade?data=\${data}&hora=\${hora}`, {
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            })
            .then(response => response.json())
            .then(data => {
                return {
                    hora: hora,
                    disponivel: data.sucesso && data.disponivel
                };
            })
            .catch(error => {
                console.error(`Erro ao verificar disponibilidade para \${hora}:`, error);
                return {
                    hora: hora,
                    disponivel: false
                };
            });
        });
        
        Promise.all(promises)
            .then(resultados => {
                horaSelect.innerHTML = '<option value="">Selecione um horário</option>';
                
                const horariosDisponiveis = resultados.filter(r => r.disponivel);
                
                if (horariosDisponiveis.length > 0) {
                    horariosDisponiveis.forEach(resultado => {
                        const option = document.createElement('option');
                        option.value = resultado.hora;
                        option.textContent = resultado.hora;
                        horaSelect.appendChild(option);
                    });
                    horaSelect.disabled = false;
                } else {
                    horaSelect.innerHTML = '<option value="">Nenhum horário disponível</option>';
                    horaSelect.disabled = true;
                }
            });
    });
    
    // Enviar formulário de agendamento
    agendarForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const medicoId = medicoSelect.value;
        const data = dataInput.value;
        const hora = horaSelect.value;
        const presencial = document.getElementById('presencial').checked;
        
        if (!medicoId || !data || !hora) {
            alert('Por favor, preencha todos os campos obrigatórios.');
            return;
        }
        
        const dadosConsulta = {
            medico_id: medicoId,
            paciente_id: usuario.id,
            data: data,
            hora: hora,
            presencial: presencial
        };
        
        fetch('/api/consultas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify(dadosConsulta)
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                alert('Consulta agendada com sucesso!');
                window.location.href = '/consultas';
            } else {
                alert(data.mensagem || 'Erro ao agendar consulta.');
            }
        })
        .catch(error => {
            console.error('Erro ao agendar consulta:', error);
            alert('Erro ao agendar consulta. Tente novamente mais tarde.');
        });
    });
});
</script>
HTML;

include __DIR__ . '/../layouts/main.php';
?>

