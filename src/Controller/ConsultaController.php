<?php

namespace App\Controller;

use App\Auth\AuthMiddleware;
use App\Service\ConsultaService;

class ConsultaController
{
    private $consultaService;
    private $authMiddleware;

    public function __construct()
    {
        $this->consultaService = new ConsultaService();
        $this->authMiddleware = new AuthMiddleware();
    }

    /**
     * Lista todas as consultas
     * 
     * @return array Lista de consultas
     */
    public function index()
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Obter data da query string
        $data = isset($_GET['data']) ? $_GET['data'] : null;

        // Listar consultas
        if ($data) {
            return $this->consultaService->listarConsultasData($data);
        } else {
            return $this->consultaService->listarConsultas();
        }
    }

    /**
     * Agenda uma nova consulta
     * 
     * @return array Resultado da operação
     */
    public function create()
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Verificar se a requisição é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'sucesso' => false,
                'mensagem' => 'Método não permitido. Use POST para agendar consulta.'
            ];
        }

        // Obter os dados da requisição
        $dados = json_decode(file_get_contents('php://input'), true);

        // Agendar consulta
        return $this->consultaService->agendarConsulta($dados);
    }

    /**
     * Busca uma consulta pelo ID
     * 
     * @param int $id ID da consulta
     * @return array Dados da consulta
     */
    public function read($id)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Buscar consulta
        return $this->consultaService->buscarConsulta($id);
    }

    /**
     * Registra os dados de uma consulta realizada
     * 
     * @param int $id ID da consulta
     * @return array Resultado da operação
     */
    public function update($id)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota('medico');

        // Verificar se a requisição é PUT
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'sucesso' => false,
                'mensagem' => 'Método não permitido. Use PUT para registrar consulta.'
            ];
        }

        // Obter os dados da requisição
        $dados = json_decode(file_get_contents('php://input'), true);

        // Registrar consulta
        return $this->consultaService->registrarConsulta($id, $dados);
    }

    /**
     * Cancela uma consulta agendada
     * 
     * @param int $id ID da consulta
     * @return array Resultado da operação
     */
    public function delete($id)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Verificar se a requisição é DELETE
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'sucesso' => false,
                'mensagem' => 'Método não permitido. Use DELETE para cancelar consulta.'
            ];
        }

        // Cancelar consulta
        return $this->consultaService->cancelarConsulta($id);
    }

    /**
     * Lista as consultas de um paciente
     * 
     * @param int $pacienteId ID do paciente
     * @return array Lista de consultas
     */
    public function paciente($pacienteId)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Obter data da query string
        $data = isset($_GET['data']) ? $_GET['data'] : null;

        // Listar consultas do paciente
        return $this->consultaService->listarConsultasPaciente($pacienteId, $data);
    }

    /**
     * Lista as consultas de um médico
     * 
     * @param int $medicoId ID do médico
     * @return array Lista de consultas
     */
    public function medico($medicoId)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Obter data da query string
        $data = isset($_GET['data']) ? $_GET['data'] : null;

        // Listar consultas do médico
        return $this->consultaService->listarConsultasMedico($medicoId, $data);
    }

    /**
     * Verifica a disponibilidade de um médico em uma data e hora específicas
     * 
     * @return array Resultado da verificação
     */
    public function verificarDisponibilidade()
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Obter dados da query string
        $medicoId = isset($_GET['medico_id']) ? $_GET['medico_id'] : null;
        $data = isset($_GET['data']) ? $_GET['data'] : null;
        $hora = isset($_GET['hora']) ? $_GET['hora'] : null;

        if (!$medicoId || !$data || !$hora) {
            return [
                'sucesso' => false,
                'mensagem' => 'Médico, data e hora são obrigatórios.'
            ];
        }

        // Verificar disponibilidade
        return $this->consultaService->verificarDisponibilidadeMedico($medicoId, $data, $hora);
    }
}

