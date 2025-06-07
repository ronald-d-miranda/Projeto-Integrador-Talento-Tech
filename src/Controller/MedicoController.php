<?php

namespace App\Controller;

use App\Auth\AuthMiddleware;
use App\Service\UserService;
use App\Model\Medico;

class MedicoController
{
    private $userService;
    private $authMiddleware;
    private $authenticatedUser;

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
     * Lista todos os médicos
     * 
     * @return array Lista de médicos
     */
    public function index()
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Listar médicos
        return $this->userService->listarUsuarios('medico');
    }

    /**
     * Cadastra um novo médico
     * 
     * @return array Resultado da operação
     */
    public function create()
    {
        // Verificar se a requisição é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'sucesso' => false,
                'mensagem' => 'Método não permitido. Use POST para criar médico.'
            ];
        }

        // Obter os dados da requisição
        $dados = json_decode(file_get_contents('php://input'), true);

        // Cadastrar médico
        return $this->userService->cadastrarUsuario($dados, 'medico');
    }

    /**
     * Busca um médico pelo ID
     * 
     * @param int $id ID do médico
     * @return array Dados do médico
     */
    public function read($id)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Buscar médico
        return $this->userService->buscarUsuario($id, 'medico');
    }

    /**
     * Atualiza os dados de um médico
     * 
     * @param int $id ID do médico
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
                'mensagem' => 'Método não permitido. Use PUT para atualizar médico.'
            ];
        }

        // Obter os dados da requisição
        $dados = json_decode(file_get_contents('php://input'), true);

        // Atualizar médico
        return $this->userService->atualizarUsuario($id, $dados, 'medico');
    }

    /**
     * Exclui um médico
     * 
     * @param int $id ID do médico
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
                'mensagem' => 'Método não permitido. Use DELETE para excluir médico.'
            ];
        }

        // Excluir médico
        return $this->userService->excluirUsuario($id, 'medico');
    }

    /**
     * Lista as consultas de um médico
     * 
     * @param int $id ID do médico
     * @return array Lista de consultas
     */
    public function consultas($id)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota('medico');

        // Obter data da query string
        $data = isset($_GET['data']) ? $_GET['data'] : null;

        // Buscar médico
        $medico = new Medico();
        if (!$medico->read($id)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Médico não encontrado.'
            ];
        }

        // Obter consultas
        $consultas = $medico->getConsultas($data);

        return [
            'sucesso' => true,
            'dados' => $consultas
        ];
    }

    /**
     * Verifica a disponibilidade de um médico em uma data e hora específicas
     * 
     * @param int $id ID do médico
     * @return array Resultado da verificação
     */
    public function disponibilidade($id)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota();

        // Obter data e hora da query string
        $data = isset($_GET['data']) ? $_GET['data'] : null;
        $hora = isset($_GET['hora']) ? $_GET['hora'] : null;

        if (!$data || !$hora) {
            return [
                'sucesso' => false,
                'mensagem' => 'Data e hora são obrigatórios.'
            ];
        }

        // Buscar médico
        $medico = new Medico();
        if (!$medico->read($id)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Médico não encontrado.'
            ];
        }

        // Verificar disponibilidade
        $disponivel = $medico->verificarDisponibilidade($data, $hora);

        return [
            'sucesso' => true,
            'disponivel' => $disponivel
        ];
    }

    /**
     * Obtém o histórico de um paciente
     * 
     * @param int $id ID do médico
     * @param int $pacienteId ID do paciente
     * @return array Histórico do paciente
     */
    public function historicoPaciente($id, $pacienteId)
    {
        // Verificar autenticação
        $this->authMiddleware->protegerRota('medico');

        // Buscar médico
        $medico = new Medico();
        if (!$medico->read($id)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Médico não encontrado.'
            ];
        }

        // Obter histórico do paciente
        $historico = $medico->getHistoricoPaciente($pacienteId);

        return [
            'sucesso' => true,
            'dados' => $historico
        ];
    }
}

