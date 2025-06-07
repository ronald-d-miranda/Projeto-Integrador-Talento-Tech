<?php
$title = 'Registrar Exame - Clínica Saúde Mais';
ob_start();
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Registrar Exame</h1>
        <a href="/exames" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Voltar para Exames
        </a>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Formulário de Registro de Exame</h5>
        </div>
        <div class="card-body">
            <form id="registrarExameForm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="paciente_id" class="form-label">Paciente</label>
                        <select class="form-select" id="paciente_id" name="paciente_id" required>
                            <option value="">Selecione um paciente</option>
                            <!-- Opções serão carregadas via JavaScript -->
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="data" class="form-label">Data</label>
                        <input type="date" class="form-control" id="data" name="data" required max="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de Exame</label>
                    <select class="form-select" id="tipo" name="tipo" required>
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
                
                <div class="mb-3">
                    <label for="resultado" class="form-label">Resultado</label>
                    <textarea class="form-control" id="resultado" name="resultado" rows="5" required></textarea>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Registrar Exame</button>
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
    
    if (!token || !usuario || usuario.tipo !== 'medico') {
        window.location.href = '/auth/login';
        return;
    }
    
    const pacienteSelect = document.getElementById('paciente_id');
    const registrarForm = document.getElementById('registrarExameForm');
    
    // Carregar lista de pacientes
    fetch('/api/pacientes', {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.sucesso && data.dados) {
            data.dados.forEach(paciente => {
                const option = document.createElement('option');
                option.value = paciente.id;
                option.textContent = paciente.nome;
                pacienteSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Erro ao carregar pacientes:', error);
        alert('Erro ao carregar lista de pacientes. Tente novamente mais tarde.');
    });
    
    // Enviar formulário de registro
    registrarForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const pacienteId = pacienteSelect.value;
        const data = document.getElementById('data').value;
        const tipo = document.getElementById('tipo').value;
        const resultado = document.getElementById('resultado').value;
        
        if (!pacienteId || !data || !tipo || !resultado) {
            alert('Por favor, preencha todos os campos obrigatórios.');
            return;
        }
        
        const dadosExame = {
            paciente_id: pacienteId,
            data: data,
            tipo: tipo,
            resultado: resultado
        };
        
        fetch('/api/exames', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify(dadosExame)
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                alert('Exame registrado com sucesso!');
                window.location.href = '/exames';
            } else {
                alert(data.mensagem || 'Erro ao registrar exame.');
            }
        })
        .catch(error => {
            console.error('Erro ao registrar exame:', error);
            alert('Erro ao registrar exame. Tente novamente mais tarde.');
        });
    });
});
</script>
HTML;

include __DIR__ . '/../layouts/main.php';
?>

