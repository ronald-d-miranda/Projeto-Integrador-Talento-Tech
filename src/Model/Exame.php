<?php

namespace App\Model;

use App\Config\Database;
use PDO;
use PDOException;

class Exame
{
    private $id;
    private $pacienteId;
    private $data;
    private $tipo;
    private $resultado;
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

    public function getPacienteId()
    {
        return $this->pacienteId;
    }

    public function setPacienteId($pacienteId)
    {
        $this->pacienteId = $pacienteId;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function getResultado()
    {
        return $this->resultado;
    }

    public function setResultado($resultado)
    {
        $this->resultado = $resultado;
    }

    /**
     * Métodos CRUD
     */
    public function create()
    {
        try {
            $query = "INSERT INTO exame (paciente_id, data, tipo, resultado) 
                      VALUES (:paciente_id, :data, :tipo, :resultado)";
            
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar e vincular valores
            $stmt->bindParam(':paciente_id', $this->pacienteId);
            $stmt->bindParam(':data', $this->data);
            $stmt->bindParam(':tipo', $this->tipo);
            $stmt->bindParam(':resultado', $this->resultado);
            
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
            $query = "SELECT * FROM exame WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row) {
                $this->id = $row['id'];
                $this->pacienteId = $row['paciente_id'];
                $this->data = $row['data'];
                $this->tipo = $row['tipo'];
                $this->resultado = $row['resultado'];
                
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
            $query = "UPDATE exame 
                      SET paciente_id = :paciente_id, 
                          data = :data, 
                          tipo = :tipo, 
                          resultado = :resultado 
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar e vincular valores
            $stmt->bindParam(':paciente_id', $this->pacienteId);
            $stmt->bindParam(':data', $this->data);
            $stmt->bindParam(':tipo', $this->tipo);
            $stmt->bindParam(':resultado', $this->resultado);
            $stmt->bindParam(':id', $this->id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function delete()
    {
        try {
            $query = "DELETE FROM exame WHERE id = :id";
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
            $query = "SELECT e.*, p.nome as nome_paciente 
                      FROM exame e 
                      INNER JOIN pessoa p ON e.paciente_id = p.id 
                      ORDER BY e.data DESC";
            
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
            $query = "SELECT e.*, p.nome as nome_paciente 
                      FROM exame e 
                      INNER JOIN pessoa p ON e.paciente_id = p.id 
                      WHERE e.paciente_id = :paciente_id 
                      ORDER BY e.data DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':paciente_id', $pacienteId);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getByData($dataInicio, $dataFim = null)
    {
        try {
            if ($dataFim) {
                $query = "SELECT e.*, p.nome as nome_paciente 
                          FROM exame e 
                          INNER JOIN pessoa p ON e.paciente_id = p.id 
                          WHERE e.data BETWEEN :data_inicio AND :data_fim 
                          ORDER BY e.data DESC";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':data_inicio', $dataInicio);
                $stmt->bindParam(':data_fim', $dataFim);
            } else {
                $query = "SELECT e.*, p.nome as nome_paciente 
                          FROM exame e 
                          INNER JOIN pessoa p ON e.paciente_id = p.id 
                          WHERE e.data = :data 
                          ORDER BY e.data DESC";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':data', $dataInicio);
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getByPacienteData($pacienteId, $dataInicio, $dataFim = null)
    {
        try {
            if ($dataFim) {
                $query = "SELECT e.*, p.nome as nome_paciente 
                          FROM exame e 
                          INNER JOIN pessoa p ON e.paciente_id = p.id 
                          WHERE e.paciente_id = :paciente_id 
                          AND e.data BETWEEN :data_inicio AND :data_fim 
                          ORDER BY e.data DESC";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':paciente_id', $pacienteId);
                $stmt->bindParam(':data_inicio', $dataInicio);
                $stmt->bindParam(':data_fim', $dataFim);
            } else {
                $query = "SELECT e.*, p.nome as nome_paciente 
                          FROM exame e 
                          INNER JOIN pessoa p ON e.paciente_id = p.id 
                          WHERE e.paciente_id = :paciente_id 
                          AND e.data = :data 
                          ORDER BY e.data DESC";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':paciente_id', $pacienteId);
                $stmt->bindParam(':data', $dataInicio);
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getByTipo($tipo)
    {
        try {
            $query = "SELECT e.*, p.nome as nome_paciente 
                      FROM exame e 
                      INNER JOIN pessoa p ON e.paciente_id = p.id 
                      WHERE e.tipo = :tipo 
                      ORDER BY e.data DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}

