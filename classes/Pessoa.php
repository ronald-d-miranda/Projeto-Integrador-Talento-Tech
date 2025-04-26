<?php

class Pessoa {
    private $nome;
    private $rg;
    private $cpf;
    private $dataNasc;
    private $sexo;
    private $email;
    private $senha;
    private $telefone;

    // Getters
    public function getNome() {
        return $this->nome;
    }

    public function getRg() {
        return $this->rg;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getDataNasc() {
        return $this->dataNasc;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    // Setters
    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    public function setRg($rg) {
        $this->rg = $rg;
        return $this;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
        return $this;
    }

    public function setDataNasc($dataNasc) {
        $this->dataNasc = $dataNasc;
        return $this;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
        return $this;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
        return $this;
    }
}
?>
