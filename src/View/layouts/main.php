<?php
error_reporting(E_ALL); // Relatar todos os tipos de erros
ini_set('display_errors', 1); // Exibir os erros na tela
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Clínica Saúde Mais' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>         
    body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f9fafb;
    color: #333;
    display: flex;
    flex-direction: column;
    padding-top: 90px;
    }

    /* Navbar */
    .navbar {
    background-color: #006d77;
    color: white;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    min-height: 80px;
    }

    .nav-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1100px;
    margin: 0 auto;
    padding: 15px 20px;
    }

    .nav-logo img {
    max-height: 50px;
    }

    .nav-links {
    list-style: none;
    display: flex;
    gap: 20px;
    }

    .nav-links li a {
    color: rgba(255, 255, 255, 0.65);
    text-decoration: none;
    font-weight: 500;
    }

    .nav-links li a:hover {
    color: white;
    text-decoration: underline;
    }

    .header {
  margin-bottom: 40px;
}

.header h1 {
  font-size: 2.5rem;
  color: #023047;
  margin-bottom: 10px;
}

.header p {
  font-size: 1.1rem;
  color: #555;
}

/* Banner */
.banner {
  background-color: #e0f7fa;
  padding: 40px 20px;
  text-align: center;
}

.banner h2 {
  font-size: 2rem;
  color: #006d77;
}

.banner p {
  font-size: 1.1rem;
  color: #555;
}

/* Seções */
section {
  margin-bottom: 40px;
}

section h2 {
  font-size: 1.8rem;
  color: #006d77;
  margin-bottom: 20px;
}
.sobre-img {
  max-width: 100%;
  border-radius: 8px;
  margin-bottom: 20px;
}

.sobre p {
  color: #4b5563;
  line-height: 1.6;
}

        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
        }
        .sidebar .nav-link {
            color: #495057 !important;
        }
        .sidebar .nav-link:hover {
            background-color: #e9ecef !important;
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd !important;
            color: white !important;
        }
        .content {
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .footer {
  background-color: #f3f4f6;
  text-align: center;
  padding: 20px 10px;
  color: #6b7280;
}
               
        .sobre-img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .sobre p {
            color: #4b5563;
            line-height: 1.6;
        }
        section {
            margin-bottom: 40px;
        }
        section h2 {
            font-size: 1.8rem;
            color: #006d77;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">      
            <div class="nav-logo">    
                <a class="navbar-brand" href="/"><img src="img/logo.png" alt="Logo Clínica Vida Saudável" /></a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (isset($usuario) && $usuario['tipo'] === 'medico'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/consultas">Consultas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/exames">Exames</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pacientes">Pacientes</a>
                    </li>
                    <?php elseif (isset($usuario) && $usuario['tipo'] === 'paciente'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/consultas">Minhas Consultas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/exames">Meus Exames</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/consultas/agendar">Agendar Consulta</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($usuario)): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?= $usuario['nome'] ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/perfil">Meu Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/auth/logout">Sair</a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/login">Entrar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/register">Cadastrar</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container-fluid">
        <div class="row">
            <?php if (isset($usuario)): ?>
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="/home">
                                <i class="bi bi-house-door"></i> Início
                            </a>
                        </li>
                        <?php if ($usuario['tipo'] === 'medico'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/consultas">
                                <i class="bi bi-calendar-check"></i> Consultas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/exames">
                                <i class="bi bi-file-medical"></i> Exames
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/pacientes">
                                <i class="bi bi-people"></i> Pacientes
                            </a>
                        </li>
                        <?php elseif ($usuario['tipo'] === 'paciente'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/consultas">
                                <i class="bi bi-calendar-check"></i> Minhas Consultas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/exames">
                                <i class="bi bi-file-medical"></i> Meus Exames
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/consultas/agendar">
                                <i class="bi bi-calendar-plus"></i> Agendar Consulta
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/perfil">
                                <i class="bi bi-person"></i> Meu Perfil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/auth/logout">
                                <i class="bi bi-box-arrow-right"></i> Sair
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <?php else: ?>
            <!-- Main content (full width when not logged in) -->
            <main class="col-12 content">
            <?php endif; ?>
                <?php if (isset($alertMessage) && isset($alertType)): ?>
                <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
                    <?= $alertMessage ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <?= $content ?>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-auto py-3">
        <div class="container">
            <span>Clínica Saúde Mais &copy; <?= date('Y') ?> - Todos os direitos reservados</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script>
        // Ativar o link atual no menu
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    <?php if (isset($scripts)): ?>
    <?= $scripts ?>
    <?php endif; ?>
</body>
</html>

