<?php

require_once 'Paciente.php';

class Exame {
    private $data;
    private $paciente;
    private $tipo;
    private $resultado;
    private $consulta;

    // Getters
    public function getData() {
        return $this->data;
    }

    public function getPaciente() {
        return $this->paciente;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getResultado() {
        return $this->resultado;
    }

    public function getConsulta() {
        return $this->consulta;
    }

    // Setters
    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    public function setPaciente($paciente) {
        $this->paciente = $paciente;
        return $this;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
        return $this;
    }

    public function setResultado($resultado) {
        $this->resultado = $resultado;
        return $this;
    }

    public function setConsulta($consulta) {
        $this->consulta = $consulta;
        return $this;
    }
}
?>
