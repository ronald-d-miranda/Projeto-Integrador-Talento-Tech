<?php

require_once 'Pessoa.php';

class Paciente extends Pessoa {
    private $metodo_pagamento;
    private $logradouro;
    private $numero;
    private $bairro;
    private $cidade;
    private $UF;
    private $consultas = array();
    private $exames = array();

    // Getters
    public function getMetodoPagamento() {
        return $this->metodo_pagamento;
    }

    public function getLogradouro() {
        return $this->logradouro;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function getUF() {
        return $this->UF;
    }

    public function getConsultas() {
        return $this->consultas;
    }

    public function getExames() {
        return $this->exames;
    }

    // Setters
    public function setMetodoPagamento($metodo_pagamento) {
        $this->metodo_pagamento = $metodo_pagamento;
        return $this;
    }

    public function setLogradouro($logradouro) {
        $this->logradouro = $logradouro;
        return $this;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
        return $this;
    }

    public function setBairro($bairro) {
        $this->bairro = $bairro;
        return $this;
    }

    public function setCidade($cidade) {
        $this->cidade = $cidade;
        return $this;
    }

    public function setUF($UF) {
        $this->UF = $UF;
        return $this;
    }

    // Métodos para gerenciar consultas
    public function adicionarConsulta($consulta) {
        $this->consultas[] = $consulta;
        return $this;
    }

    public function removerConsulta($consulta) {
        $key = array_search($consulta, $this->consultas, true);
        if ($key !== false) {
            unset($this->consultas[$key]);
        }
        return $this;
    }

    // Métodos para gerenciar exames
    public function adicionarExame($exame) {
        $this->exames[] = $exame;
        return $this;
    }

    public function removerExame($exame) {
        $key = array_search($exame, $this->exames, true);
        if ($key !== false) {
            unset($this->exames[$key]);
        }
        return $this;
    }
}
?>
