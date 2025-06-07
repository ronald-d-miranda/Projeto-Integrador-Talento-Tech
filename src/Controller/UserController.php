<?php

namespace App\Controller;

use App\Auth\AuthMiddleware;
use App\Service\UserService;

class UserController
{
    private $userService;
    private $authenticatedUser;
    private $authMiddleware;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->authMiddleware = new AuthMiddleware();
    }

    // Método para definir o usuário autenticado
    public function setAuthenticatedUser($user)
    {
        $this->authenticatedUser = $user;
    }
    
    /**
     * Lista todos os usuários
     * 
     * @return array Lista de usuários
     */
    public function index()
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Obter tipo de usuário da query string
        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'paciente';

        // Listar usuários
        return $this->userService->listarUsuarios($tipo);
    }

    /**
     * Cadastra um novo usuário
     * 
     * @return array Resultado da operação
     */
    public function create()
    {
        // Verificar se a requisição é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'sucesso' => false,
                'mensagem' => 'Método não permitido. Use POST para criar usuário.'
            ];
        }

        // Obter os dados da requisição
        $dados = json_decode(file_get_contents('php://input'), true);

        // Obter tipo de usuário da query string
        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'paciente';

        // Cadastrar usuário
        return $this->userService->cadastrarUsuario($dados, $tipo);
    }

    /**
     * Busca um usuário pelo ID
     * 
     * @param int $id ID do usuário
     * @return array Dados do usuário
     */
    public function read($id)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Obter tipo de usuário da query string
        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'paciente';

        // Buscar usuário
        return $this->userService->buscarUsuario($id, $tipo);
    }

    /**
     * Atualiza os dados de um usuário
     * 
     * @param int $id ID do usuário
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
                'mensagem' => 'Método não permitido. Use PUT para atualizar usuário.'
            ];
        }

        // Obter os dados da requisição
        $dados = json_decode(file_get_contents('php://input'), true);

        // Obter tipo de usuário da query string
        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'paciente';

        // Atualizar usuário
        return $this->userService->atualizarUsuario($id, $dados, $tipo);
    }

    /**
     * Exclui um usuário
     * 
     * @param int $id ID do usuário
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
                'mensagem' => 'Método não permitido. Use DELETE para excluir usuário.'
            ];
        }

        // Obter tipo de usuário da query string
        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'paciente';

        // Excluir usuário
        return $this->userService->excluirUsuario($id, $tipo);
    }

    /**
     * Altera a senha de um usuário
     * 
     * @param int $id ID do usuário
     * @return array Resultado da operação
     */
    public function alterarSenha($id)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Verificar se a requisição é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'sucesso' => false,
                'mensagem' => 'Método não permitido. Use POST para alterar senha.'
            ];
        }

        // Obter os dados da requisição
        $dados = json_decode(file_get_contents('php://input'), true);

        // Validar dados obrigatórios
        if (!isset($dados['senha_atual']) || !isset($dados['nova_senha'])) {
            return [
                'sucesso' => false,
                'mensagem' => 'Senha atual e nova senha são obrigatórios.'
            ];
        }

        // Alterar senha
        return $this->userService->alterarSenha($id, $dados['senha_atual'], $dados['nova_senha']);
    }
}

