<?php

namespace App\Model;

use App\Config\Database;
use PDO;
use PDOException;

class Pessoa
{
    protected $id;
    protected $nome;
    protected $rg;
    protected $cpf;
    protected $dataNascimento;
    protected $sexo;
    protected $email;
    protected $senha;
    protected $telefone;
    protected $tipo;
    protected $conn;

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

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getRg()
    {
        return $this->rg;
    }

    public function setRg($rg)
    {
        $this->rg = $rg;
    }

    public function getCpf()
    {
        return $this->cpf;
    }

    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    public function setDataNascimento($dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;
    }

    public function getSexo()
    {
        return $this->sexo;
    }

    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Métodos CRUD
     */
    public function create()
    {
        try {
            $query = "INSERT INTO pessoa (nome, rg, cpf, data_nascimento, sexo, email, senha, telefone, tipo) 
                      VALUES (:nome, :rg, :cpf, :data_nascimento, :sexo, :email, :senha, :telefone, :tipo)";
            
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar e vincular valores
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':rg', $this->rg);
            $stmt->bindParam(':cpf', $this->cpf);
            $stmt->bindParam(':data_nascimento', $this->dataNascimento);
            $stmt->bindParam(':sexo', $this->sexo);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':senha', $this->senha);
            $stmt->bindParam(':telefone', $this->telefone);
            $stmt->bindParam(':tipo', $this->tipo);
            
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
            $query = "SELECT * FROM pessoa WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
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
            $query = "UPDATE pessoa 
                      SET nome = :nome, 
                          rg = :rg, 
                          cpf = :cpf, 
                          data_nascimento = :data_nascimento, 
                          sexo = :sexo, 
                          email = :email, 
                          telefone = :telefone 
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar e vincular valores
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':rg', $this->rg);
            $stmt->bindParam(':cpf', $this->cpf);
            $stmt->bindParam(':data_nascimento', $this->dataNascimento);
            $stmt->bindParam(':sexo', $this->sexo);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':telefone', $this->telefone);
            $stmt->bindParam(':id', $this->id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function delete()
    {
        try {
            $query = "DELETE FROM pessoa WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function findByEmail($email)
    {
        try {
            $query = "SELECT * FROM pessoa WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
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
                
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function findByCpf($cpf)
    {
        try {
            $query = "SELECT * FROM pessoa WHERE cpf = :cpf";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cpf', $cpf);
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
                
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getAll()
    {
        try {
            $query = "SELECT * FROM pessoa";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function verificarSenha($senha)
    {
        return password_verify($senha, $this->senha);
    }

    public function atualizarSenha($novaSenha)
    {
        try {
            $this->senha = password_hash($novaSenha, PASSWORD_DEFAULT);
            
            $query = "UPDATE pessoa SET senha = :senha WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':senha', $this->senha);
            $stmt->bindParam(':id', $this->id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}

