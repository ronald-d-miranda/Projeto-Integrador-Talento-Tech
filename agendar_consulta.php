<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Agende consultas médicas online com Fubica Corporation. Escolha especialidades, médicos, datas e horários disponíveis.">
    <meta name="keywords" content="agendamento médico, consulta online, Fubica Corporation, especialidades médicas, marcar consulta">
    <meta name="author" content="Fubica CORPORATION">
    <meta name="robots" content="index,follow">
    
    <meta property="og:title" content="Agendamento de Consulta | Fubica Corporation">
    <meta property="og:description" content="Agende consultas médicas online com Fubica Corporation. Escolha especialidades, médicos, datas e horários disponíveis.">
    <meta property="og:url" content="https://fubi.ca/agendamento">
    <meta property="og:site_name" content="Fubica Corporation">
    <meta property="og:type" content="website">
    
    <title>Agendamento de Consulta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $especialidade = $_POST['especialidade'] ?? '';
        $medico = $_POST['medico'] ?? '';
        $data = $_POST['data'] ?? '';
        $horario = $_POST['horario'] ?? '';
        $nome = $_POST['nome'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $email = $_POST['email'] ?? '';
        $convenio = $_POST['convenio'] ?? '';
    }
    ?>

    <div class="container mt-5">
        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST') { ?>
            <h2 class="mb-4">Agendamento de Consulta</h2>
            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Especialidade:</label>
                    <select class="form-select" name="especialidade" required>
                        <option value="">Selecione</option>
                        <option>Clínica Geral</option>
                        <option>Cardiologia</option>
                        <option>Dermatologia</option>
                        <option>Ginecologia</option>
                        <option>Ortopedia</option>
                    </select>
                </div>
                <!-- Repeat similar structure for other fields -->
                <!-- Example for médico field -->
                <div class="col-md-6">
                    <label class="form-label">Médico:</label>
                    <select class="form-select" name="medico" required>
                        <option value="">Selecione</option>
                        <option>Dr. João Silva</option>
                        <option>Dra. Marina Costa</option>
                        <option>Dr. Pedro Alves</option>
                    </select>
                </div>
                <!-- Data field -->
                <div class="col-md-6">
                    <label class="form-label">Data:</label>
                    <input type="date" class="form-control" name="data" required>
                </div>
                <!-- Horário radio buttons -->
                <div class="col-12">
                    <label class="form-label">Horário:</label>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="horario" value="08:00" id="horario08" required>
                        <label class="form-check-label" for="horario08">08:00</label>
                    </div>
                    <!-- Repeat for other radio options -->
                </div>
                <!-- Nome, telefone, email, convenio fields -->
                <div class="col-md-6">
                    <label class="form-label">Nome completo:</label>
                    <input type="text" class="form-control" name="nome" required>
                </div>
                <!-- Repeat for other fields -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">CONFIRMAR AGENDAMENTO</button>
                </div>
            </form>
        <?php } else { ?>
            <div class="alert alert-success mt-5">
                <h3>Consulta Agendada com Sucesso!</h3>
                <p><strong>Local:</strong> Clínica Saúde Bem</p>
                <p><strong>Especialidade:</strong> <?= htmlspecialchars($especialidade) ?></p>
                <p><strong>Médico:</strong> <?= htmlspecialchars($medico) ?></p>
                <p><strong>Data:</strong> <?= htmlspecialchars($data) ?></p>
                <p><strong>Horário:</strong> <?= htmlspecialchars($horario) ?></p>
                <p><strong>Paciente:</strong> <?= htmlspecialchars($nome) ?></p>
            </div>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
