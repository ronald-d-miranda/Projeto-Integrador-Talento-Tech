<?php

class Medico extends Pessoa {
    private $id;
    private $id_pessoa;
    private $registro_crf;
    private $matricula;
    private $especializacao;
    private $consultas = array();
    private $pessoa;

    // Getters
    public function getIdPessoa() { 
        return $this->id_pessoa;
    }
    public function getPessoa() { 
        return $this->pessoa;
    }
    public function getRegistroCrf() {
        return $this->registro_crf;
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function getEspecializacao() {
        return $this->especializacao;
    }

    public function getConsultas() {
        return $this->consultas;
    }

    // Setters
    public function setIdPessoa($id_pessoa) { 
        $this->id_pessoa = $id_pessoa;
        return $this;    
    }
    public function setPessoa(Pessoa $pessoa) {
        $this->pessoa = $pessoa;
        return $this;
    }
    public function setRegistroCrf($registro_crf) {
        $this->registro_crf = $registro_crf;
        return $this;
    }

    public function setMatricula($matricula) {
        $this->matricula = $matricula;
        return $this;
    }

    public function setEspecializacao($especializacao) {
        $this->especializacao = $especializacao;
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
}
?>
