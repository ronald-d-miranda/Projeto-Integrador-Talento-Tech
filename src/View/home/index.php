<?php
$title = 'Início - Clínica Saúde Mais';
ob_start();
?>

<div class="container-fluid py-4">
    <header class="header">
        <h1>Olá, <span id="usuario-nome">Usuário</span> 👋</h1>
        <p>Bem-vindo à Clínica Saúde Mais. Aqui, sua saúde é prioridade!</p>
    </header>

    <?php if (isset($usuario)): ?>
        <?php if ($usuario['tipo'] === 'medico'): ?>
            <!-- Dashboard para Médicos -->
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Consultas Hoje</h5>
                            <h2 class="card-text"><?= $consultasHoje ?? 0 ?></h2>
                            <p class="card-text"><small>Agendadas para hoje</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Pacientes</h5>
                            <h2 class="card-text"><?= $totalPacientes ?? 0 ?></h2>
                            <p class="card-text"><small>Total de pacientes</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Exames</h5>
                            <h2 class="card-text"><?= $examesPendentes ?? 0 ?></h2>
                            <p class="card-text"><small>Exames pendentes</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Próxima Consulta</h5>
                            <h2 class="card-text"><?= $proximaConsulta ?? 'Nenhuma' ?></h2>
                            <p class="card-text"><small>Horário da próxima consulta</small></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Consultas de Hoje</h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($consultasHojeLista) && count($consultasHojeLista) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Horário</th>
                                                <th>Paciente</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($consultasHojeLista as $consulta): ?>
                                                <tr>
                                                    <td><?= date('H:i', strtotime($consulta['data'])) ?></td>
                                                    <td><?= $consulta['nome_paciente'] ?></td>
                                                    <td>
                                                        <?php if ($consulta['diagnostico']): ?>
                                                            <span class="badge bg-success">Realizada</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning">Pendente</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="/consultas/<?= $consulta['id'] ?>" class="btn btn-sm btn-primary">
                                                            <i class="bi bi-eye"></i> Ver
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-center">Nenhuma consulta agendada para hoje.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Ações Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="/consultas" class="btn btn-primary">
                                    <i class="bi bi-calendar-check"></i> Ver Todas as Consultas
                                </a>
                                <a href="/exames" class="btn btn-success">
                                    <i class="bi bi-file-medical"></i> Gerenciar Exames
                                </a>
                                <a href="/pacientes" class="btn btn-info text-white">
                                    <i class="bi bi-people"></i> Ver Pacientes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        <?php elseif ($usuario['tipo'] === 'paciente'): ?>
            <!-- Dashboard para Pacientes -->
            <div class="row">
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Próxima Consulta</h5>
                            <h2 class="card-text"><?= $proximaConsulta ?? 'Nenhuma' ?></h2>
                            <p class="card-text"><small>Data e hora da próxima consulta</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Consultas</h5>
                            <h2 class="card-text"><?= $totalConsultas ?? 0 ?></h2>
                            <p class="card-text"><small>Total de consultas realizadas</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Exames</h5>
                            <h2 class="card-text"><?= $totalExames ?? 0 ?></h2>
                            <p class="card-text"><small>Total de exames realizados</small></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Próximas Consultas</h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($proximasConsultas) && count($proximasConsultas) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Horário</th>
                                                <th>Médico</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($proximasConsultas as $consulta): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($consulta['data'])) ?></td>
                                                    <td><?= date('H:i', strtotime($consulta['data'])) ?></td>
                                                    <td><?= $consulta['nome_medico'] ?></td>
                                                    <td>
                                                        <a href="/consultas/<?= $consulta['id'] ?>" class="btn btn-sm btn-primary">
                                                            <i class="bi bi-eye"></i> Ver
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-center">Nenhuma consulta agendada.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Ações Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="/consultas/agendar" class="btn btn-primary">
                                    <i class="bi bi-calendar-plus"></i> Agendar Consulta
                                </a>
                                <a href="/consultas" class="btn btn-success">
                                    <i class="bi bi-calendar-check"></i> Minhas Consultas
                                </a>
                                <a href="/exames" class="btn btn-info text-white">
                                    <i class="bi bi-file-medical"></i> Meus Exames
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
    <!-- Página inicial para usuários não autenticados -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h2>Bem-vindo a Clínica Saúde Mais</h2>
                    <p class="lead">Uma plataforma completa para gerenciamento de consultas médicas, exames e histórico clínico.</p>
                    <p>Faça login ou cadastre-se para acessar todas as funcionalidades do sistema.</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a href="/auth/login" class="btn btn-primary me-md-2">Entrar</a>
                        <a href="/auth/register" class="btn btn-outline-primary">Cadastrar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h3>Funcionalidades</h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="bi bi-calendar-check"></i> Agendamento de consultas
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-file-medical"></i> Registro de exames
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-clipboard-pulse"></i> Histórico clínico completo
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-shield-check"></i> Acesso seguro aos dados
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner -->
    <section class="banner">
        <div class="container">
            <h2>Cuidamos da sua saúde com excelência</h2>
            <p>Na Clínica Saúde Mais, acreditamos que saúde vai muito além do tratamento de doenças — é sobre bem-estar, prevenção e qualidade de vida. Com uma equipe multidisciplinar de profissionais experientes e dedicados, oferecemos atendimento humanizado, tecnologia de ponta e um ambiente acolhedor para você e sua família.</p>
            <h3>Sobre nossos Serviços</h3>
            <p>Na Saúde Mais, você encontra tudo o que precisa em um só lugar. Oferecemos consultas com especialistas, exames laboratoriais e de imagem, atendimento odontológico, psicologia, nutrição e muito mais. Com estrutura moderna e profissionais qualificados, garantimos um cuidado completo, com agilidade, conforto e segurança.</p>
        </div>
    </section>

    <!-- Sobre -->
    <section id="sobre" class="sobre">
        <div class="container">
            <div class="section-header">
                <h2>Sobre a Clínica</h2>
            </div>
            <img src="img/clinicaimg.jpg" alt="Equipe médica" class="sobre-img">
            <p>
                Fundada com o propósito de oferecer atendimento médico de qualidade e humanizado, a Clínica Vida Saudável atua há mais de 10 anos promovendo saúde e bem-estar.
                Contamos com uma equipe especializada e estrutura moderna para garantir o melhor cuidado aos nossos pacientes.
            </p>
        </div>
    </section>
<?php endif; ?>
</div>

<?php
$content = ob_get_clean();

$scripts = <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se o usuário está autenticado
    const token = localStorage.getItem('token');
    const usuario = JSON.parse(localStorage.getItem('usuario') || '{}');
    
    if (token && usuario) {
        // Carregar dados do dashboard
        fetch('/api/dashboard', {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                // Atualizar elementos do dashboard com os dados recebidos
                // Implementação depende dos dados retornados pela API
            }
        })
        .catch(error => {
            console.error('Erro ao carregar dashboard:', error);
        });
    }
});
</script>
HTML;

include __DIR__ . '/../layouts/main.php';
?>

