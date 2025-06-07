<?php

namespace App\Model;

use App\Config\Database;
use PDO;
use PDOException;

class Consulta
{
    private $id;
    private $data;
    private $medicoId;
    private $pacienteId;
    private $diagnostico;
    private $receita;
    private $presencial;
    private $conn;

    public function __construct()
    {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    /**
     * Getters e Setters
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getMedicoId()
    {
        return $this->medicoId;
    }

    public function setMedicoId($medicoId)
    {
        $this->medicoId = $medicoId;
    }

    public function getPacienteId()
    {
        return $this->pacienteId;
    }

    public function setPacienteId($pacienteId)
    {
        $this->pacienteId = $pacienteId;
    }

    public function getDiagnostico()
    {
        return $this->diagnostico;
    }

    public function setDiagnostico($diagnostico)
    {
        $this->diagnostico = $diagnostico;
    }

    public function getReceita()
    {
        return $this->receita;
    }

    public function setReceita($receita)
    {
        $this->receita = $receita;
    }

    public function getPresencial()
    {
        return $this->presencial;
    }

    public function setPresencial($presencial)
    {
        $this->presencial = $presencial;
    }

    /**
     * Métodos CRUD
     */
    public function create()
    {
        try {
            $query = "INSERT INTO consulta (data, medico_id, paciente_id, diagnostico, receita, presencial) 
                      VALUES (:data, :medico_id, :paciente_id, :diagnostico, :receita, :presencial)";
            
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar e vincular valores
            $stmt->bindParam(':data', $this->data);
            $stmt->bindParam(':medico_id', $this->medicoId);
            $stmt->bindParam(':paciente_id', $this->pacienteId);
            $stmt->bindParam(':diagnostico', $this->diagnostico);
            $stmt->bindParam(':receita', $this->receita);
            $stmt->bindParam(':presencial', $this->presencial, PDO::PARAM_BOOL);
            
            if ($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function read($id)
    {
        try {
            $query = "SELECT * FROM consulta WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row) {
                $this->id = $row['id'];
                $this->data = $row['data'];
                $this->medicoId = $row['medico_id'];
                $this->pacienteId = $row['paciente_id'];
                $this->diagnostico = $row['diagnostico'];
                $this->receita = $row['receita'];
                $this->presencial = $row['presencial'];
                
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function update()
    {
        try {
            $query = "UPDATE consulta 
                      SET data = :data, 
                          medico_id = :medico_id, 
                          paciente_id = :paciente_id, 
                          diagnostico = :diagnostico, 
                          receita = :receita, 
                          presencial = :presencial 
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar e vincular valores
            $stmt->bindParam(':data', $this->data);
            $stmt->bindParam(':medico_id', $this->medicoId);
            $stmt->bindParam(':paciente_id', $this->pacienteId);
            $stmt->bindParam(':diagnostico', $this->diagnostico);
            $stmt->bindParam(':receita', $this->receita);
            $stmt->bindParam(':presencial', $this->presencial, PDO::PARAM_BOOL);
            $stmt->bindParam(':id', $this->id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function delete()
    {
        try {
            $query = "DELETE FROM consulta WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getAll()
    {
        try {
            $query = "SELECT c.*, 
                             p_paciente.nome as nome_paciente, 
                             p_medico.nome as nome_medico 
                      FROM consulta c 
                      INNER JOIN pessoa p_paciente ON c.paciente_id = p_paciente.id 
                      INNER JOIN pessoa p_medico ON c.medico_id = p_medico.id 
                      ORDER BY c.data DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getByPaciente($pacienteId)
    {
        try {
            $query = "SELECT c.*, 
                             p_medico.nome as nome_medico 
                      FROM consulta c 
                      INNER JOIN pessoa p_medico ON c.medico_id = p_medico.id 
                      WHERE c.paciente_id = :paciente_id 
                      ORDER BY c.data DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':paciente_id', $pacienteId);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getByMedico($medicoId)
    {
        try {
            $query = "SELECT c.*, 
                             p_paciente.nome as nome_paciente 
                      FROM consulta c 
                      INNER JOIN pessoa p_paciente ON c.paciente_id = p_paciente.id 
                      WHERE c.medico_id = :medico_id 
                      ORDER BY c.data DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':medico_id', $medicoId);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getByData($data)
    {
        try {
            $query = "SELECT c.*, 
                             p_paciente.nome as nome_paciente, 
                             p_medico.nome as nome_medico 
                      FROM consulta c 
                      INNER JOIN pessoa p_paciente ON c.paciente_id = p_paciente.id 
                      INNER JOIN pessoa p_medico ON c.medico_id = p_medico.id 
                      WHERE DATE(c.data) = :data 
                      ORDER BY c.data ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':data', $data);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getByMedicoData($medicoId, $data)
    {
        try {
            $query = "SELECT c.*, 
                             p_paciente.nome as nome_paciente 
                      FROM consulta c 
                      INNER JOIN pessoa p_paciente ON c.paciente_id = p_paciente.id 
                      WHERE c.medico_id = :medico_id 
                      AND DATE(c.data) = :data 
                      ORDER BY c.data ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':medico_id', $medicoId);
            $stmt->bindParam(':data', $data);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getByPacienteData($pacienteId, $data)
    {
        try {
            $query = "SELECT c.*, 
                             p_medico.nome as nome_medico 
                      FROM consulta c 
                      INNER JOIN pessoa p_medico ON c.medico_id = p_medico.id 
                      WHERE c.paciente_id = :paciente_id 
                      AND DATE(c.data) = :data 
                      ORDER BY c.data ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':paciente_id', $pacienteId);
            $stmt->bindParam(':data', $data);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function verificarDisponibilidadeMedico($medicoId, $data, $hora)
    {
        try {
            $dataHora = $data . ' ' . $hora . ':00';
            
            $query = "SELECT COUNT(*) as total FROM consulta 
                      WHERE medico_id = :medico_id 
                      AND data = :data_hora";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':medico_id', $medicoId);
            $stmt->bindParam(':data_hora', $dataHora);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Se não houver consultas nesse horário, o médico está disponível
            return ($row['total'] == 0);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}

