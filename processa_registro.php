<?php
require_once 'classes/Pessoa.php';
require_once 'classes/Medico.php';
require_once 'classes/Paciente.php';
require_once 'classes/dao/PessoaDAO.php';
require_once 'classes/dao/MedicoDAO.php';
require_once 'classes/dao/PacienteDAO.php';

$tipo = $_POST['tipo'];

$pessoa = new Pessoa();
$pessoa->setNome($_POST['nome'])
       ->setEmail($_POST['email'])
       ->setTelefone($_POST['telefone'])
       ->setCpf($_POST['cpf'])
       ->setRg($_POST['rg'])
       ->setDataNasc($_POST['dataNasc'])
       ->setSexo($_POST['sexo'])
       ->setSenha($_POST['senha']);

$pessoaDAO = new PessoaDAO();
$pessoaId = $pessoaDAO->insert($pessoa);

if ($tipo === 'medico') {
    $medico = new Medico();
    $medico->setIdPessoa($pessoaId)
           ->setRegistroCrf($_POST['registro_crf'])
           ->setMatricula($_POST['matricula'])
           ->setEspecializacao($_POST['especializacao'])
           ->setPessoa($pessoa);

    $medicoDAO = new MedicoDAO();
    $medicoDAO->insert($medico);
} else {
    $paciente = new Paciente();
    $paciente->setIdPessoa($pessoaId)
             ->setMetodoPagamento($_POST['metodo_pagamento'])
             ->setLogradouro($_POST['logradouro'])
             ->setNumero($_POST['numero'])
             ->setBairro($_POST['bairro'])
             ->setCidade($_POST['cidade'])
             ->setUF($_POST['uf'])
             ->setPessoa($pessoa);

    $pacienteDAO = new PacienteDAO();
    $pacienteDAO->insert($paciente);
}

header('Location: login.php');
exit;
?>
