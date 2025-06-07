<?php

namespace App\Service;

use App\Model\Consulta;
use App\Model\Medico;
use App\Model\Paciente;
use Exception;

class ConsultaService
{
    /**
     * Agenda uma nova consulta
     * 
     * @param array $dados Dados da consulta
     * @return array Resultado da operação
     */
    public function agendarConsulta(array $dados)
    {
        try {
            // Validar campos obrigatórios
            $camposObrigatorios = ['data', 'hora', 'medico_id', 'paciente_id'];
            foreach ($camposObrigatorios as $campo) {
                if (empty($dados[$campo])) {
                    return [
                        'sucesso' => false,
                        'mensagem' => "O campo {$campo} é obrigatório."
                    ];
                }
            }
            
            // Verificar se o médico existe
            $medico = new Medico();
            if (!$medico->read($dados['medico_id'])) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Médico não encontrado.'
                ];
            }
            
            // Verificar se o paciente existe
            $paciente = new Paciente();
            if (!$paciente->read($dados['paciente_id'])) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Paciente não encontrado.'
                ];
            }
            
            // Verificar disponibilidade do médico
            $consulta = new Consulta();
            if (!$consulta->verificarDisponibilidadeMedico($dados['medico_id'], $dados['data'], $dados['hora'])) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Médico não disponível neste horário.'
                ];
            }
            
            // Formatar data e hora
            $dataHora = $dados['data'] . ' ' . $dados['hora'] . ':00';
            
            // Criar consulta
            $consulta->setData($dataHora);
            $consulta->setMedicoId($dados['medico_id']);
            $consulta->setPacienteId($dados['paciente_id']);
            $consulta->setDiagnostico($dados['diagnostico'] ?? null);
            $consulta->setReceita($dados['receita'] ?? null);
            $consulta->setPresencial($dados['presencial'] ?? true);
            
            if ($consulta->create()) {
                return [
                    'sucesso' => true,
                    'mensagem' => 'Consulta agendada com sucesso.',
                    'id' => $consulta->getId()
                ];
            } else {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Erro ao agendar consulta.'
                ];
            }
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao agendar consulta: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Registra os dados de uma consulta realizada
     * 
     * @param int $id ID da consulta
     * @param array $dados Dados da consulta
     * @return array Resultado da operação
     */
    public function registrarConsulta(int $id, array $dados)
    {
        try {
            // Validar campos obrigatórios
            $camposObrigatorios = ['diagnostico', 'receita'];
            foreach ($camposObrigatorios as $campo) {
                if (empty($dados[$campo])) {
                    return [
                        'sucesso' => false,
                        'mensagem' => "O campo {$campo} é obrigatório."
                    ];
                }
            }
            
            // Buscar consulta
            $consulta = new Consulta();
            if (!$consulta->read($id)) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Consulta não encontrada.'
                ];
            }
            
            // Atualizar dados da consulta
            $consulta->setDiagnostico($dados['diagnostico']);
            $consulta->setReceita($dados['receita']);
            $consulta->setPresencial($dados['presencial'] ?? $consulta->getPresencial());
            
            if ($consulta->update()) {
                return [
                    'sucesso' => true,
                    'mensagem' => 'Consulta registrada com sucesso.'
                ];
            } else {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Erro ao registrar consulta.'
                ];
            }
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao registrar consulta: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cancela uma consulta agendada
     * 
     * @param int $id ID da consulta
     * @return array Resultado da operação
     */
    public function cancelarConsulta(int $id)
    {
        try {
            $consulta = new Consulta();
            if (!$consulta->read($id)) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Consulta não encontrada.'
                ];
            }
            
            if ($consulta->delete()) {
                return [
                    'sucesso' => true,
                    'mensagem' => 'Consulta cancelada com sucesso.'
                ];
            } else {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Erro ao cancelar consulta.'
                ];
            }
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao cancelar consulta: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Busca uma consulta pelo ID
     * 
     * @param int $id ID da consulta
     * @return array Dados da consulta ou mensagem de erro
     */
    public function buscarConsulta(int $id)
    {
        try {
            $consulta = new Consulta();
            if (!$consulta->read($id)) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Consulta não encontrada.'
                ];
            }
            
            // Buscar informações adicionais do médico e paciente
            $medico = new Medico();
            $medico->read($consulta->getMedicoId());
            
            $paciente = new Paciente();
            $paciente->read($consulta->getPacienteId());
            
            return [
                'sucesso' => true,
                'dados' => [
                    'id' => $consulta->getId(),
                    'data' => $consulta->getData(),
                    'medico_id' => $consulta->getMedicoId(),
                    'nome_medico' => $medico->getNome(),
                    'paciente_id' => $consulta->getPacienteId(),
                    'nome_paciente' => $paciente->getNome(),
                    'diagnostico' => $consulta->getDiagnostico(),
                    'receita' => $consulta->getReceita(),
                    'presencial' => $consulta->getPresencial()
                ]
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao buscar consulta: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lista todas as consultas
     * 
     * @return array Lista de consultas ou mensagem de erro
     */
    public function listarConsultas()
    {
        try {
            $consulta = new Consulta();
            $consultas = $consulta->getAll();
            
            return [
                'sucesso' => true,
                'dados' => $consultas
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao listar consultas: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lista as consultas de um paciente
     * 
     * @param int $pacienteId ID do paciente
     * @param string|null $data Data opcional para filtrar (formato: YYYY-MM-DD)
     * @return array Lista de consultas ou mensagem de erro
     */
    public function listarConsultasPaciente(int $pacienteId, string $data = null)
    {
        try {
            $consulta = new Consulta();
            
            if ($data) {
                $consultas = $consulta->getByPacienteData($pacienteId, $data);
            } else {
                $consultas = $consulta->getByPaciente($pacienteId);
            }
            
            return [
                'sucesso' => true,
                'dados' => $consultas
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao listar consultas do paciente: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lista as consultas de um médico
     * 
     * @param int $medicoId ID do médico
     * @param string|null $data Data opcional para filtrar (formato: YYYY-MM-DD)
     * @return array Lista de consultas ou mensagem de erro
     */
    public function listarConsultasMedico(int $medicoId, string $data = null)
    {
        try {
            $consulta = new Consulta();
            
            if ($data) {
                $consultas = $consulta->getByMedicoData($medicoId, $data);
            } else {
                $consultas = $consulta->getByMedico($medicoId);
            }
            
            return [
                'sucesso' => true,
                'dados' => $consultas
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao listar consultas do médico: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lista as consultas de uma data específica
     * 
     * @param string $data Data para filtrar (formato: YYYY-MM-DD)
     * @return array Lista de consultas ou mensagem de erro
     */
    public function listarConsultasData(string $data)
    {
        try {
            $consulta = new Consulta();
            $consultas = $consulta->getByData($data);
            
            return [
                'sucesso' => true,
                'dados' => $consultas
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao listar consultas da data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verifica a disponibilidade de um médico em uma data e hora específicas
     * 
     * @param int $medicoId ID do médico
     * @param string $data Data (formato: YYYY-MM-DD)
     * @param string $hora Hora (formato: HH:MM)
     * @return array Resultado da verificação
     */
    public function verificarDisponibilidadeMedico(int $medicoId, string $data, string $hora)
    {
        try {
            $consulta = new Consulta();
            $disponivel = $consulta->verificarDisponibilidadeMedico($medicoId, $data, $hora);
            
            return [
                'sucesso' => true,
                'disponivel' => $disponivel
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao verificar disponibilidade: ' . $e->getMessage()
            ];
        }
    }
}

