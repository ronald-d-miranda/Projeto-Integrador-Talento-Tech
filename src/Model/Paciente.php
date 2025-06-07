<?php

namespace App\Model;

use PDO;
use PDOException;

class Paciente extends Pessoa
{
    private $metodoPagamento;
    private $logradouro;
    private $numero;
    private $bairro;
    private $cidade;
    private $uf;

    public function __construct()
    {
        parent::__construct();
        $this->setTipo('paciente');
    }

    /**
     * Getters e Setters
     */
    public function getMetodoPagamento()
    {
        return $this->metodoPagamento;
    }

    public function setMetodoPagamento($metodoPagamento)
    {
        $this->metodoPagamento = $metodoPagamento;
    }

    public function getLogradouro()
    {
        return $this->logradouro;
    }

    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    public function getBairro()
    {
        return $this->bairro;
    }

    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    public function getCidade()
    {
        return $this->cidade;
    }

    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    public function getUf()
    {
        return $this->uf;
    }

    public function setUf($uf)
    {
        $this->uf = $uf;
    }

    /**
     * Métodos CRUD específicos para Paciente
     */
    public function create()
    {
        try {
            // Inicia uma transação
            $this->conn->beginTransaction();
            
            // Primeiro, cria o registro na tabela pessoa
            if (parent::create()) {
                // Em seguida, cria o registro na tabela paciente
                $query = "INSERT INTO paciente (id, metodo_pagamento, logradouro, numero, bairro, cidade, uf) 
                          VALUES (:id, :metodo_pagamento, :logradouro, :numero, :bairro, :cidade, :uf)";
                
                $stmt = $this->conn->prepare($query);
                
                // Sanitizar e vincular valores
                $stmt->bindParam(':id', $this->id);
                $stmt->bindParam(':metodo_pagamento', $this->metodoPagamento);
                $stmt->bindParam(':logradouro', $this->logradouro);
                $stmt->bindParam(':numero', $this->numero);
                $stmt->bindParam(':bairro', $this->bairro);
                $stmt->bindParam(':cidade', $this->cidade);
                $stmt->bindParam(':uf', $this->uf);
                
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
                // Em seguida, lê os dados específicos da tabela paciente
                $query = "SELECT * FROM paciente WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($row) {
                    $this->metodoPagamento = $row['metodo_pagamento'];
                    $this->logradouro = $row['logradouro'];
                    $this->numero = $row['numero'];
                    $this->bairro = $row['bairro'];
                    $this->cidade = $row['cidade'];
                    $this->uf = $row['uf'];
                    
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
                // Em seguida, atualiza os dados específicos na tabela paciente
                $query = "UPDATE paciente 
                          SET metodo_pagamento = :metodo_pagamento, 
                              logradouro = :logradouro, 
                              numero = :numero, 
                              bairro = :bairro, 
                              cidade = :cidade, 
                              uf = :uf 
                          WHERE id = :id";
                
                $stmt = $this->conn->prepare($query);
                
                // Sanitizar e vincular valores
                $stmt->bindParam(':metodo_pagamento', $this->metodoPagamento);
                $stmt->bindParam(':logradouro', $this->logradouro);
                $stmt->bindParam(':numero', $this->numero);
                $stmt->bindParam(':bairro', $this->bairro);
                $stmt->bindParam(':cidade', $this->cidade);
                $stmt->bindParam(':uf', $this->uf);
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

    public function getAllPacientes()
    {
        try {
            $query = "SELECT p.*, pa.metodo_pagamento, pa.logradouro, pa.numero, pa.bairro, pa.cidade, pa.uf 
                      FROM pessoa p 
                      INNER JOIN paciente pa ON p.id = pa.id 
                      WHERE p.tipo = 'paciente'";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getConsultas()
    {
        try {
            $query = "SELECT c.*, m.nome as nome_medico 
                      FROM consulta c 
                      INNER JOIN pessoa m ON c.medico_id = m.id 
                      WHERE c.paciente_id = :paciente_id 
                      ORDER BY c.data DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':paciente_id', $this->id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getExames()
    {
        try {
            $query = "SELECT * FROM exame WHERE paciente_id = :paciente_id ORDER BY data DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':paciente_id', $this->id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}

