<?php

require_once 'Medico.php';
require_once 'Paciente.php';

class Consulta {
    private $data;
    private $medico;
    private $paciente;
    private $diagnostico;
    private $receita;
    private $presencial;
    private $exames = array();

    // Getters
    public function getData() {
        return $this->data;
    }

    public function getMedico() {
        return $this->medico;
    }

    public function getPaciente() {
        return $this->paciente;
    }

    public function getDiagnostico() {
        return $this->diagnostico;
    }

    public function getReceita() {
        return $this->receita;
    }

    public function getPresencial() {
        return $this->presencial;
    }

    public function getExames() {
        return $this->exames;
    }

    // Setters
    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    public function setMedico($medico) {
        $this->medico = $medico;
        return $this;
    }

    public function setPaciente($paciente) {
        $this->paciente = $paciente;
        return $this;
    }

    public function setDiagnostico($diagnostico) {
        $this->diagnostico = $diagnostico;
        return $this;
    }

    public function setReceita($receita) {
        $this->receita = $receita;
        return $this;
    }

    public function setPresencial($presencial) {
        $this->presencial = $presencial;
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
