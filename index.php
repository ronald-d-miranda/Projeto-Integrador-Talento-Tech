<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['tipo']) || !isset($_SESSION['id'])) {
    // Se não estiver logado, redireciona para o login
    header('Location: login.php');
    exit();
}

// Redireciona para a dashboard correta
if ($_SESSION['tipo'] === 'medico') {
    header('Location: medico_dashboard.php');
    exit();
} elseif ($_SESSION['tipo'] === 'paciente') {
    header('Location: paciente_dashboard.php');
    exit();
} else {
    echo "Tipo de usuário inválido.";
}
?>
