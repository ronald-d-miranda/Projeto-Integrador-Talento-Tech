<?php

namespace App\Service;

use App\Model\Pessoa;
use App\Model\Paciente;
use App\Model\Medico;
use Exception;

class UserService
{
    /**
     * Cadastra um novo usuário (paciente ou médico)
     * 
     * @param array $dados Dados do usuário
     * @param string $tipo Tipo de usuário (paciente ou medico)
     * @return array Resultado da operação
     */
    public function cadastrarUsuario(array $dados, string $tipo)
    {
        try {
            // Validar campos obrigatórios comuns
            $camposObrigatorios = ['nome', 'rg', 'cpf', 'data_nascimento', 'sexo', 'email', 'senha', 'telefone'];
            foreach ($camposObrigatorios as $campo) {
                if (empty($dados[$campo])) {
                    return [
                        'sucesso' => false,
                        'mensagem' => "O campo {$campo} é obrigatório."
                    ];
                }
            }
            
            // Verificar se o CPF já está cadastrado
            $pessoa = new Pessoa();
            if ($pessoa->findByCpf($dados['cpf'])) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'CPF já cadastrado no sistema.'
                ];
            }
            
            // Verificar se o e-mail já está cadastrado
            if ($pessoa->findByEmail($dados['email'])) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'E-mail já cadastrado no sistema.'
                ];
            }
            
            if ($tipo === 'paciente') {
                // Validar campos específicos do paciente
                $camposObrigatoriosPaciente = ['logradouro', 'numero', 'bairro', 'cidade', 'uf'];
                foreach ($camposObrigatoriosPaciente as $campo) {
                    if (empty($dados[$campo])) {
                        return [
                            'sucesso' => false,
                            'mensagem' => "O campo {$campo} é obrigatório para pacientes."
                        ];
                    }
                }
                
                // Criar paciente
                $paciente = new Paciente();
                $paciente->setNome($dados['nome']);
                $paciente->setRg($dados['rg']);
                $paciente->setCpf($dados['cpf']);
                $paciente->setDataNascimento($dados['data_nascimento']);
                $paciente->setSexo($dados['sexo']);
                $paciente->setEmail($dados['email']);
                $paciente->setSenha($dados['senha']);
                $paciente->setTelefone($dados['telefone']);
                $paciente->setMetodoPagamento($dados['metodo_pagamento'] ?? null);
                $paciente->setLogradouro($dados['logradouro']);
                $paciente->setNumero($dados['numero']);
                $paciente->setBairro($dados['bairro']);
                $paciente->setCidade($dados['cidade']);
                $paciente->setUf($dados['uf']);
                
                if ($paciente->create()) {
                    return [
                        'sucesso' => true,
                        'mensagem' => 'Paciente cadastrado com sucesso.',
                        'id' => $paciente->getId()
                    ];
                } else {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'Erro ao cadastrar paciente.'
                    ];
                }
            } elseif ($tipo === 'medico') {
                // Validar campos específicos do médico
                $camposObrigatoriosMedico = ['crm', 'matricula', 'especializacao'];
                foreach ($camposObrigatoriosMedico as $campo) {
                    if (empty($dados[$campo])) {
                        return [
                            'sucesso' => false,
                            'mensagem' => "O campo {$campo} é obrigatório para médicos."
                        ];
                    }
                }
                
                // Verificar se o CRM já está cadastrado
                $medico = new Medico();
                if ($medico->findByCrm($dados['crm'])) {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'CRM já cadastrado no sistema.'
                    ];
                }
                
                // Criar médico
                $medico = new Medico();
                $medico->setNome($dados['nome']);
                $medico->setRg($dados['rg']);
                $medico->setCpf($dados['cpf']);
                $medico->setDataNascimento($dados['data_nascimento']);
                $medico->setSexo($dados['sexo']);
                $medico->setEmail($dados['email']);
                $medico->setSenha($dados['senha']);
                $medico->setTelefone($dados['telefone']);
                $medico->setCrm($dados['crm']);
                $medico->setMatricula($dados['matricula']);
                $medico->setEspecializacao($dados['especializacao']);
                
                if ($medico->create()) {
                    return [
                        'sucesso' => true,
                        'mensagem' => 'Médico cadastrado com sucesso.',
                        'id' => $medico->getId()
                    ];
                } else {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'Erro ao cadastrar médico.'
                    ];
                }
            } else {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Tipo de usuário inválido.'
                ];
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao cadastrar usuário: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Atualiza os dados de um usuário existente
     * 
     * @param int $id ID do usuário
     * @param array $dados Novos dados do usuário
     * @param string $tipo Tipo de usuário (paciente ou medico)
     * @return array Resultado da operação
     */
    public function atualizarUsuario(int $id, array $dados, string $tipo)
    {
        try {
            if ($tipo === 'paciente') {
                $paciente = new Paciente();
                if (!$paciente->read($id)) {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'Paciente não encontrado.'
                    ];
                }
                
                // Atualizar dados básicos
                if (isset($dados['nome'])) $paciente->setNome($dados['nome']);
                if (isset($dados['rg'])) $paciente->setRg($dados['rg']);
                if (isset($dados['data_nascimento'])) $paciente->setDataNascimento($dados['data_nascimento']);
                if (isset($dados['sexo'])) $paciente->setSexo($dados['sexo']);
                if (isset($dados['email'])) $paciente->setEmail($dados['email']);
                if (isset($dados['telefone'])) $paciente->setTelefone($dados['telefone']);
                
                // Atualizar dados específicos do paciente
                if (isset($dados['metodo_pagamento'])) $paciente->setMetodoPagamento($dados['metodo_pagamento']);
                if (isset($dados['logradouro'])) $paciente->setLogradouro($dados['logradouro']);
                if (isset($dados['numero'])) $paciente->setNumero($dados['numero']);
                if (isset($dados['bairro'])) $paciente->setBairro($dados['bairro']);
                if (isset($dados['cidade'])) $paciente->setCidade($dados['cidade']);
                if (isset($dados['uf'])) $paciente->setUf($dados['uf']);
                
                if ($paciente->update()) {
                    return [
                        'sucesso' => true,
                        'mensagem' => 'Paciente atualizado com sucesso.'
                    ];
                } else {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'Erro ao atualizar paciente.'
                    ];
                }
            } elseif ($tipo === 'medico') {
                $medico = new Medico();
                if (!$medico->read($id)) {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'Médico não encontrado.'
                    ];
                }
                
                // Atualizar dados básicos
                if (isset($dados['nome'])) $medico->setNome($dados['nome']);
                if (isset($dados['rg'])) $medico->setRg($dados['rg']);
                if (isset($dados['data_nascimento'])) $medico->setDataNascimento($dados['data_nascimento']);
                if (isset($dados['sexo'])) $medico->setSexo($dados['sexo']);
                if (isset($dados['email'])) $medico->setEmail($dados['email']);
                if (isset($dados['telefone'])) $medico->setTelefone($dados['telefone']);
                
                // Atualizar dados específicos do médico
                if (isset($dados['crm'])) $medico->setCrm($dados['crm']);
                if (isset($dados['matricula'])) $medico->setMatricula($dados['matricula']);
                if (isset($dados['especializacao'])) $medico->setEspecializacao($dados['especializacao']);
                
                if ($medico->update()) {
                    return [
                        'sucesso' => true,
                        'mensagem' => 'Médico atualizado com sucesso.'
                    ];
                } else {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'Erro ao atualizar médico.'
                    ];
                }
            } else {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Tipo de usuário inválido.'
                ];
            }
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao atualizar usuário: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Exclui um usuário do sistema
     * 
     * @param int $id ID do usuário
     * @param string $tipo Tipo de usuário (paciente ou medico)
     * @return array Resultado da operação
     */
    public function excluirUsuario(int $id, string $tipo)
    {
        try {
            if ($tipo === 'paciente') {
                $paciente = new Paciente();
                if (!$paciente->read($id)) {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'Paciente não encontrado.'
                    ];
                }
                
                if ($paciente->delete()) {
                    return [
                        'sucesso' => true,
                        'mensagem' => 'Paciente excluído com sucesso.'
                    ];
                } else {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'Erro ao excluir paciente.'
                    ];
                }
            } elseif ($tipo === 'medico') {
                $medico = new Medico();
                if (!$medico->read($id)) {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'Médico não encontrado.'
                    ];
                }
                
                if ($medico->delete()) {
                    return [
                        'sucesso' => true,
                        'mensagem' => 'Médico excluído com sucesso.'
                    ];
                } else {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'Erro ao excluir médico.'
                    ];
                }
            } else {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Tipo de usuário inválido.'
                ];
            }
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao excluir usuário: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Busca um usuário pelo ID
     * 
     * @param int $id ID do usuário
     * @param string $tipo Tipo de usuário (paciente ou medico)
     * @return array Dados do usuário ou mensagem de erro
     */
    public function buscarUsuario(int $id, string $tipo)
    {
        try {
            if ($tipo === 'paciente') {
                $paciente = new Paciente();
                if (!$paciente->read($id)) {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'Paciente não encontrado.'
                    ];
                }
                
                return [
                    'sucesso' => true,
                    'dados' => [
                        'id' => $paciente->getId(),
                        'nome' => $paciente->getNome(),
                        'rg' => $paciente->getRg(),
                        'cpf' => $paciente->getCpf(),
                        'data_nascimento' => $paciente->getDataNascimento(),
                        'sexo' => $paciente->getSexo(),
                        'email' => $paciente->getEmail(),
                        'telefone' => $paciente->getTelefone(),
                        'tipo' => $paciente->getTipo(),
                        'metodo_pagamento' => $paciente->getMetodoPagamento(),
                        'logradouro' => $paciente->getLogradouro(),
                        'numero' => $paciente->getNumero(),
                        'bairro' => $paciente->getBairro(),
                        'cidade' => $paciente->getCidade(),
                        'uf' => $paciente->getUf()
                    ]
                ];
            } elseif ($tipo === 'medico') {
                $medico = new Medico();
                if (!$medico->read($id)) {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'Médico não encontrado.'
                    ];
                }
                
                return [
                    'sucesso' => true,
                    'dados' => [
                        'id' => $medico->getId(),
                        'nome' => $medico->getNome(),
                        'rg' => $medico->getRg(),
                        'cpf' => $medico->getCpf(),
                        'data_nascimento' => $medico->getDataNascimento(),
                        'sexo' => $medico->getSexo(),
                        'email' => $medico->getEmail(),
                        'telefone' => $medico->getTelefone(),
                        'tipo' => $medico->getTipo(),
                        'crm' => $medico->getCrm(),
                        'matricula' => $medico->getMatricula(),
                        'especializacao' => $medico->getEspecializacao()
                    ]
                ];
            } else {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Tipo de usuário inválido.'
                ];
            }
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao buscar usuário: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lista todos os usuários de um determinado tipo
     * 
     * @param string $tipo Tipo de usuário (paciente ou medico)
     * @return array Lista de usuários ou mensagem de erro
     */
    public function listarUsuarios(string $tipo)
    {
        try {
            if ($tipo === 'paciente') {
                $paciente = new Paciente();
                $pacientes = $paciente->getAllPacientes();
                
                return [
                    'sucesso' => true,
                    'dados' => $pacientes
                ];
            } elseif ($tipo === 'medico') {
                $medico = new Medico();
                $medicos = $medico->getAllMedicos();
                
                return [
                    'sucesso' => true,
                    'dados' => $medicos
                ];
            } else {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Tipo de usuário inválido.'
                ];
            }
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao listar usuários: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Altera a senha de um usuário
     * 
     * @param int $id ID do usuário
     * @param string $senhaAtual Senha atual
     * @param string $novaSenha Nova senha
     * @return array Resultado da operação
     */
    public function alterarSenha(int $id, string $senhaAtual, string $novaSenha)
    {
        try {
            $pessoa = new Pessoa();
            if (!$pessoa->read($id)) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Usuário não encontrado.'
                ];
            }
            
            // Verificar se a senha atual está correta
            if (!$pessoa->verificarSenha($senhaAtual)) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Senha atual incorreta.'
                ];
            }
            
            // Atualizar a senha
            if ($pessoa->atualizarSenha($novaSenha)) {
                return [
                    'sucesso' => true,
                    'mensagem' => 'Senha alterada com sucesso.'
                ];
            } else {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Erro ao alterar senha.'
                ];
            }
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao alterar senha: ' . $e->getMessage()
            ];
        }
    }
}

