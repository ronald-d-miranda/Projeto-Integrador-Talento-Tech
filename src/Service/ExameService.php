<?php

namespace App\Service;

use App\Model\Exame;
use App\Model\Paciente;
use Exception;

class ExameService
{
    /**
     * Registra um novo exame
     * 
     * @param array $dados Dados do exame
     * @return array Resultado da operação
     */
    public function registrarExame(array $dados)
    {
        try {
            // Validar campos obrigatórios
            $camposObrigatorios = ['paciente_id', 'data', 'tipo', 'resultado'];
            foreach ($camposObrigatorios as $campo) {
                if (empty($dados[$campo])) {
                    return [
                        'sucesso' => false,
                        'mensagem' => "O campo {$campo} é obrigatório."
                    ];
                }
            }
            
            // Verificar se o paciente existe
            $paciente = new Paciente();
            if (!$paciente->read($dados['paciente_id'])) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Paciente não encontrado.'
                ];
            }
            
            // Criar exame
            $exame = new Exame();
            $exame->setPacienteId($dados['paciente_id']);
            $exame->setData($dados['data']);
            $exame->setTipo($dados['tipo']);
            $exame->setResultado($dados['resultado']);
            
            if ($exame->create()) {
                return [
                    'sucesso' => true,
                    'mensagem' => 'Exame registrado com sucesso.',
                    'id' => $exame->getId()
                ];
            } else {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Erro ao registrar exame.'
                ];
            }
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao registrar exame: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Atualiza os dados de um exame
     * 
     * @param int $id ID do exame
     * @param array $dados Novos dados do exame
     * @return array Resultado da operação
     */
    public function atualizarExame(int $id, array $dados)
    {
        try {
            // Buscar exame
            $exame = new Exame();
            if (!$exame->read($id)) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Exame não encontrado.'
                ];
            }
            
            // Atualizar dados do exame
            if (isset($dados['paciente_id'])) {
                // Verificar se o paciente existe
                $paciente = new Paciente();
                if (!$paciente->read($dados['paciente_id'])) {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'Paciente não encontrado.'
                    ];
                }
                $exame->setPacienteId($dados['paciente_id']);
            }
            
            if (isset($dados['data'])) $exame->setData($dados['data']);
            if (isset($dados['tipo'])) $exame->setTipo($dados['tipo']);
            if (isset($dados['resultado'])) $exame->setResultado($dados['resultado']);
            
            if ($exame->update()) {
                return [
                    'sucesso' => true,
                    'mensagem' => 'Exame atualizado com sucesso.'
                ];
            } else {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Erro ao atualizar exame.'
                ];
            }
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao atualizar exame: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Exclui um exame
     * 
     * @param int $id ID do exame
     * @return array Resultado da operação
     */
    public function excluirExame(int $id)
    {
        try {
            $exame = new Exame();
            if (!$exame->read($id)) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Exame não encontrado.'
                ];
            }
            
            if ($exame->delete()) {
                return [
                    'sucesso' => true,
                    'mensagem' => 'Exame excluído com sucesso.'
                ];
            } else {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Erro ao excluir exame.'
                ];
            }
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao excluir exame: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Busca um exame pelo ID
     * 
     * @param int $id ID do exame
     * @return array Dados do exame ou mensagem de erro
     */
    public function buscarExame(int $id)
    {
        try {
            $exame = new Exame();
            if (!$exame->read($id)) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Exame não encontrado.'
                ];
            }
            
            // Buscar informações adicionais do paciente
            $paciente = new Paciente();
            $paciente->read($exame->getPacienteId());
            
            return [
                'sucesso' => true,
                'dados' => [
                    'id' => $exame->getId(),
                    'paciente_id' => $exame->getPacienteId(),
                    'nome_paciente' => $paciente->getNome(),
                    'data' => $exame->getData(),
                    'tipo' => $exame->getTipo(),
                    'resultado' => $exame->getResultado()
                ]
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao buscar exame: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lista todos os exames
     * 
     * @return array Lista de exames ou mensagem de erro
     */
    public function listarExames()
    {
        try {
            $exame = new Exame();
            $exames = $exame->getAll();
            
            return [
                'sucesso' => true,
                'dados' => $exames
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao listar exames: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lista os exames de um paciente
     * 
     * @param int $pacienteId ID do paciente
     * @return array Lista de exames ou mensagem de erro
     */
    public function listarExamesPaciente(int $pacienteId)
    {
        try {
            $exame = new Exame();
            $exames = $exame->getByPaciente($pacienteId);
            
            return [
                'sucesso' => true,
                'dados' => $exames
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao listar exames do paciente: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lista os exames por data
     * 
     * @param string $dataInicio Data inicial (formato: YYYY-MM-DD)
     * @param string|null $dataFim Data final opcional (formato: YYYY-MM-DD)
     * @return array Lista de exames ou mensagem de erro
     */
    public function listarExamesPorData(string $dataInicio, string $dataFim = null)
    {
        try {
            $exame = new Exame();
            $exames = $exame->getByData($dataInicio, $dataFim);
            
            return [
                'sucesso' => true,
                'dados' => $exames
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao listar exames por data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lista os exames de um paciente por data
     * 
     * @param int $pacienteId ID do paciente
     * @param string $dataInicio Data inicial (formato: YYYY-MM-DD)
     * @param string|null $dataFim Data final opcional (formato: YYYY-MM-DD)
     * @return array Lista de exames ou mensagem de erro
     */
    public function listarExamesPacientePorData(int $pacienteId, string $dataInicio, string $dataFim = null)
    {
        try {
            $exame = new Exame();
            $exames = $exame->getByPacienteData($pacienteId, $dataInicio, $dataFim);
            
            return [
                'sucesso' => true,
                'dados' => $exames
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao listar exames do paciente por data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lista os exames por tipo
     * 
     * @param string $tipo Tipo de exame
     * @return array Lista de exames ou mensagem de erro
     */
    public function listarExamesPorTipo(string $tipo)
    {
        try {
            $exame = new Exame();
            $exames = $exame->getByTipo($tipo);
            
            return [
                'sucesso' => true,
                'dados' => $exames
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao listar exames por tipo: ' . $e->getMessage()
            ];
        }
    }
}

