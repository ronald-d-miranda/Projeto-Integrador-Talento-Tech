<?php

namespace App\Model;

use PDO;
use PDOException;

class Medico extends Pessoa
{
    private $crm;
    private $matricula;
    private $especializacao;

    public function __construct()
    {
        parent::__construct();
        $this->setTipo('medico');
    }

    /**
     * Getters e Setters
     */
    public function getCrm()
    {
        return $this->crm;
    }

    public function setCrm($crm)
    {
        $this->crm = $crm;
    }

    public function getMatricula()
    {
        return $this->matricula;
    }

    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    public function getEspecializacao()
    {
        return $this->especializacao;
    }

    public function setEspecializacao($especializacao)
    {
        $this->especializacao = $especializacao;
    }

    /**
     * Métodos CRUD específicos para Médico
     */
    public function create()
    {
        try {
            // Inicia uma transação
            $this->conn->beginTransaction();
            
            // Primeiro, cria o registro na tabela pessoa
            if (parent::create()) {
                // Em seguida, cria o registro na tabela medico
                $query = "INSERT INTO medico (id, crm, matricula, especializacao) 
                          VALUES (:id, :crm, :matricula, :especializacao)";
                
                $stmt = $this->conn->prepare($query);
                
                // Sanitizar e vincular valores
                $stmt->bindParam(':id', $this->id);
                $stmt->bindParam(':crm', $this->crm);
                $stmt->bindParam(':matricula', $this->matricula);
                $stmt->bindParam(':especializacao', $this->especializacao);
                
                if ($stmt->execute()) {
                    $this->conn->commit();
                    return true;
                }
            }
            
            // Se algo deu errado, desfaz a transação
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function read($id)
    {
        try {
            // Primeiro, lê os dados da tabela pessoa
            if (parent::read($id)) {
                // Em seguida, lê os dados específicos da tabela medico
                $query = "SELECT * FROM medico WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($row) {
                    $this->crm = $row['crm'];
                    $this->matricula = $row['matricula'];
                    $this->especializacao = $row['especializacao'];
                    
                    return true;
                }
            }
            
            return false;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function update()
    {
        try {
            // Inicia uma transação
            $this->conn->beginTransaction();
            
            // Primeiro, atualiza os dados na tabela pessoa
            if (parent::update()) {
                // Em seguida, atualiza os dados específicos na tabela medico
                $query = "UPDATE medico 
                          SET crm = :crm, 
                              matricula = :matricula, 
                              especializacao = :especializacao 
                          WHERE id = :id";
                
                $stmt = $this->conn->prepare($query);
                
                // Sanitizar e vincular valores
                $stmt->bindParam(':crm', $this->crm);
                $stmt->bindParam(':matricula', $this->matricula);
                $stmt->bindParam(':especializacao', $this->especializacao);
                $stmt->bindParam(':id', $this->id);
                
                if ($stmt->execute()) {
                    $this->conn->commit();
                    return true;
                }
            }
            
            // Se algo deu errado, desfaz a transação
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function delete()
    {
        // A exclusão em cascata é tratada pelo banco de dados
        return parent::delete();
    }

    public function getAllMedicos()
    {
        try {
            $query = "SELECT p.*, m.crm, m.matricula, m.especializacao 
                      FROM pessoa p 
                      INNER JOIN medico m ON p.id = m.id 
                      WHERE p.tipo = 'medico'";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getConsultas($data = null)
    {
        try {
            $query = "SELECT c.*, p.nome as nome_paciente 
                      FROM consulta c 
                      INNER JOIN pessoa p ON c.paciente_id = p.id 
                      WHERE c.medico_id = :medico_id";
            
            if ($data) {
                $query .= " AND DATE(c.data) = :data";
            }
            
            $query .= " ORDER BY c.data DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':medico_id', $this->id);
            
            if ($data) {
                $stmt->bindParam(':data', $data);
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function verificarDisponibilidade($data, $hora)
    {
        try {
            $dataHora = $data . ' ' . $hora . ':00';
            
            $query = "SELECT COUNT(*) as total FROM consulta 
                      WHERE medico_id = :medico_id 
                      AND data = :data_hora";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':medico_id', $this->id);
            $stmt->bindParam(':data_hora', $dataHora);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Se não houver consultas nesse horário, o médico está disponível
            return ($row['total'] == 0);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function findByCrm($crm)
    {
        try {
            $query = "SELECT p.* FROM pessoa p 
                      INNER JOIN medico m ON p.id = m.id 
                      WHERE m.crm = :crm";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':crm', $crm);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row) {
                $this->id = $row['id'];
                $this->nome = $row['nome'];
                $this->rg = $row['rg'];
                $this->cpf = $row['cpf'];
                $this->dataNascimento = $row['data_nascimento'];
                $this->sexo = $row['sexo'];
                $this->email = $row['email'];
                $this->senha = $row['senha'];
                $this->telefone = $row['telefone'];
                $this->tipo = $row['tipo'];
                
                // Buscar dados específicos do médico
                $query = "SELECT * FROM medico WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $this->id);
                $stmt->execute();
                
                $medicoRow = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($medicoRow) {
                    $this->crm = $medicoRow['crm'];
                    $this->matricula = $medicoRow['matricula'];
                    $this->especializacao = $medicoRow['especializacao'];
                    
                    return true;
                }
            }
            
            return false;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getHistoricoPaciente($pacienteId)
    {
        try {
            $query = "SELECT c.*, p.nome as nome_paciente, m.nome as nome_medico 
                      FROM consulta c 
                      INNER JOIN pessoa p ON c.paciente_id = p.id 
                      INNER JOIN pessoa m ON c.medico_id = m.id 
                      WHERE c.paciente_id = :paciente_id 
                      ORDER BY c.data DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':paciente_id', $pacienteId);
            $stmt->execute();
            
            $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $query = "SELECT e.*, p.nome as nome_paciente 
                      FROM exame e 
                      INNER JOIN pessoa p ON e.paciente_id = p.id 
                      WHERE e.paciente_id = :paciente_id 
                      ORDER BY e.data DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':paciente_id', $pacienteId);
            $stmt->execute();
            
            $exames = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'consultas' => $consultas,
                'exames' => $exames
            ];
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}

