<?php

namespace App\Controller;

use App\Auth\AuthMiddleware;
use App\Service\ExameService;

class ExameController
{
    private $exameService;
    private $authMiddleware;

    public function __construct()
    {
        $this->exameService = new ExameService();
        $this->authMiddleware = new AuthMiddleware();
    }

    /**
     * Lista todos os exames
     * 
     * @return array Lista de exames
     */
    public function index()
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota('medico');

        // Listar exames
        return $this->exameService->listarExames();
    }

    /**
     * Registra um novo exame
     * 
     * @return array Resultado da operação
     */
    public function create()
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota('medico');

        // Verificar se a requisição é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'sucesso' => false,
                'mensagem' => 'Método não permitido. Use POST para registrar exame.'
            ];
        }

        // Obter os dados da requisição
        $dados = json_decode(file_get_contents('php://input'), true);

        // Registrar exame
        return $this->exameService->registrarExame($dados);
    }

    /**
     * Busca um exame pelo ID
     * 
     * @param int $id ID do exame
     * @return array Dados do exame
     */
    public function read($id)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Buscar exame
        return $this->exameService->buscarExame($id);
    }

    /**
     * Atualiza os dados de um exame
     * 
     * @param int $id ID do exame
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
                'mensagem' => 'Método não permitido. Use PUT para atualizar exame.'
            ];
        }

        // Obter os dados da requisição
        $dados = json_decode(file_get_contents('php://input'), true);

        // Atualizar exame
        return $this->exameService->atualizarExame($id, $dados);
    }

    /**
     * Exclui um exame
     * 
     * @param int $id ID do exame
     * @return array Resultado da operação
     */
    public function delete($id)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota('medico');

        // Verificar se a requisição é DELETE
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'sucesso' => false,
                'mensagem' => 'Método não permitido. Use DELETE para excluir exame.'
            ];
        }

        // Excluir exame
        return $this->exameService->excluirExame($id);
    }

    /**
     * Lista os exames de um paciente
     * 
     * @param int $pacienteId ID do paciente
     * @return array Lista de exames
     */
    public function paciente($pacienteId)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Listar exames do paciente
        return $this->exameService->listarExamesPaciente($pacienteId);
    }

    /**
     * Lista os exames por data
     * 
     * @return array Lista de exames
     */
    public function porData()
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Obter dados da query string
        $dataInicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : null;
        $dataFim = isset($_GET['data_fim']) ? $_GET['data_fim'] : null;

        if (!$dataInicio) {
            return [
                'sucesso' => false,
                'mensagem' => 'Data inicial é obrigatória.'
            ];
        }

        // Listar exames por data
        return $this->exameService->listarExamesPorData($dataInicio, $dataFim);
    }

    /**
     * Lista os exames de um paciente por data
     * 
     * @param int $pacienteId ID do paciente
     * @return array Lista de exames
     */
    public function pacientePorData($pacienteId)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Obter dados da query string
        $dataInicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : null;
        $dataFim = isset($_GET['data_fim']) ? $_GET['data_fim'] : null;

        if (!$dataInicio) {
            return [
                'sucesso' => false,
                'mensagem' => 'Data inicial é obrigatória.'
            ];
        }

        // Listar exames do paciente por data
        return $this->exameService->listarExamesPacientePorData($pacienteId, $dataInicio, $dataFim);
    }

    /**
     * Lista os exames por tipo
     * 
     * @return array Lista de exames
     */
    public function porTipo()
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Obter tipo da query string
        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;

        if (!$tipo) {
            return [
                'sucesso' => false,
                'mensagem' => 'Tipo é obrigatório.'
            ];
        }

        // Listar exames por tipo
        return $this->exameService->listarExamesPorTipo($tipo);
    }
}

