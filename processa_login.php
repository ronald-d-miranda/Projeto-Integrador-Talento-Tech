<?php
session_start();
require_once 'classes/dao/PessoaDAO.php';
require_once 'classes/dao/MedicoDAO.php';
require_once 'classes/dao/PacienteDAO.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

$pessoaDAO = new PessoaDAO();
$pessoa = $pessoaDAO->findByEmail($email);

if ($pessoa && $pessoa->getSenha() === $senha) {
    $medicoDAO = new MedicoDAO();
    $medico = $medicoDAO->findByPessoaId($pessoa->getId());
    if ($medico) {
        $_SESSION['tipo'] = 'medico';
        $_SESSION['id'] = $medico->getId();
        header('Location: medico_dashboard.php');
        exit();
    }

    $pacienteDAO = new PacienteDAO();
    $paciente = $pacienteDAO->findByPessoaId($pessoa->getId());
    if ($paciente) {
        $_SESSION['tipo'] = 'paciente';
        $_SESSION['id'] = $paciente->getId();
        header('Location: paciente_dashboard.php');
        exit();
    }

    // Se não encontrou tipo
    echo "Tipo de usuário não reconhecido.";
} else {
    echo "Credenciais inválidas.";
}
?>