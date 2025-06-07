<?php

namespace App\Controller;

use App\Auth\AuthMiddleware;
use App\Service\UserService;
use App\Model\Paciente;

class PacienteController
{
    private $userService;
    private $authenticatedUser;
    private $authMiddleware;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->authMiddleware = new AuthMiddleware();
    }

    public function setAuthenticatedUser($user)
    {
        $this->authenticatedUser = $user;
    }

    /**
     * Lista todos os pacientes
     * 
     * @return array Lista de pacientes
     */
    public function index()
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Listar pacientes
        return $this->userService->listarUsuarios('paciente');
    }

    /**
     * Cadastra um novo paciente
     * 
     * @return array Resultado da operação
     */
    public function create()
    {
        // Verificar se a requisição é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'sucesso' => false,
                'mensagem' => 'Método não permitido. Use POST para criar paciente.'
            ];
        }

        // Obter os dados da requisição
        $dados = json_decode(file_get_contents('php://input'), true);

        // Cadastrar paciente
        return $this->userService->cadastrarUsuario($dados, 'paciente');
    }

    /**
     * Busca um paciente pelo ID
     * 
     * @param int $id ID do paciente
     * @return array Dados do paciente
     */
    public function read($id)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Buscar paciente
        return $this->userService->buscarUsuario($id, 'paciente');
    }

    /**
     * Atualiza os dados de um paciente
     * 
     * @param int $id ID do paciente
     * @return array Resultado da operação
     */
    public function update($id)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Verificar se a requisição é PUT
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'sucesso' => false,
                'mensagem' => 'Método não permitido. Use PUT para atualizar paciente.'
            ];
        }

        // Obter os dados da requisição
        $dados = json_decode(file_get_contents('php://input'), true);

        // Atualizar paciente
        return $this->userService->atualizarUsuario($id, $dados, 'paciente');
    }

    /**
     * Exclui um paciente
     * 
     * @param int $id ID do paciente
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
                'mensagem' => 'Método não permitido. Use DELETE para excluir paciente.'
            ];
        }

        // Excluir paciente
        return $this->userService->excluirUsuario($id, 'paciente');
    }

    /**
     * Lista as consultas de um paciente
     * 
     * @param int $id ID do paciente
     * @return array Lista de consultas
     */
    public function consultas($id)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Buscar paciente
        $paciente = new Paciente();
        if (!$paciente->read($id)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Paciente não encontrado.'
            ];
        }

        // Obter consultas
        $consultas = $paciente->getConsultas();

        return [
            'sucesso' => true,
            'dados' => $consultas
        ];
    }

    /**
     * Lista os exames de um paciente
     * 
     * @param int $id ID do paciente
     * @return array Lista de exames
     */
    public function exames($id)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Buscar paciente
        $paciente = new Paciente();
        if (!$paciente->read($id)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Paciente não encontrado.'
            ];
        }

        // Obter exames
        $exames = $paciente->getExames();

        return [
            'sucesso' => true,
            'dados' => $exames
        ];
    }
}

