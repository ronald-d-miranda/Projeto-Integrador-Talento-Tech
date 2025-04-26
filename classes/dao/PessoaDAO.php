<?php

require_once '../classes/DatabaseConfig.php';
require_once '../classes/Pessoa.php';

class PessoaDAO {
    private $conn;
    
    public function __construct() {
        $this->conn = DatabaseConfig::getInstance()->getConnection();
    }
    
    public function insert(Pessoa $pessoa) {
        $sql = "INSERT INTO tb_pessoa (nome, rg, cpf, data_nasc, sexo, email, senha, telefone) 
                VALUES (:nome, :rg, :cpf, :data_nasc, :sexo, :email, :senha, :telefone)";
                
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':nome', $pessoa->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(':rg', $pessoa->getRg(), PDO::PARAM_STR);
            $stmt->bindValue(':cpf', $pessoa->getCpf(), PDO::PARAM_STR);
            
            // Verifica se a data é um objeto DateTime ou uma string
            if ($pessoa->getDataNasc() instanceof DateTime) {
                $stmt->bindValue(':data_nasc', $pessoa->getDataNasc()->format('Y-m-d'), PDO::PARAM_STR);
            } else {
                $stmt->bindValue(':data_nasc', $pessoa->getDataNasc(), PDO::PARAM_STR);
            }
            
            $stmt->bindValue(':sexo', $pessoa->getSexo(), PDO::PARAM_STR);
            $stmt->bindValue(':email', $pessoa->getEmail(), PDO::PARAM_STR);
            $stmt->bindValue(':senha', $pessoa->getSenha(), PDO::PARAM_STR);
            $stmt->bindValue(':telefone', $pessoa->getTelefone(), PDO::PARAM_STR);
            
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Erro ao inserir: " . $e->getMessage();
            return false;
        }
    }
    
    public function findAll() {
        $sql = "SELECT * FROM tb_pessoa";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $pessoas = [];
            while ($row = $stmt->fetch()) {
                $pessoa = new Pessoa();
                $pessoa->setId($row['id'])
                      ->setNome($row['nome'])
                      ->setRg($row['rg'])
                      ->setCpf($row['cpf'])
                      ->setDataNasc($row['data_nasc'])
                      ->setSexo($row['sexo'])
                      ->setEmail($row['email'])
                      ->setSenha($row['senha'])
                      ->setTelefone($row['telefone']);
                      
                $pessoas[] = $pessoa;
            }
            
            return $pessoas;
        } catch (PDOException $e) {
            echo "Erro na consulta: " . $e->getMessage();
            return false;
        }
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM tb_pessoa WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch();
            if (!$row) {
                return null;
            }
            
            $pessoa = new Pessoa();
            $pessoa->setId($row['id'])
                  ->setNome($row['nome'])
                  ->setRg($row['rg'])
                  ->setCpf($row['cpf'])
                  ->setDataNasc($row['data_nasc'])
                  ->setSexo($row['sexo'])
                  ->setEmail($row['email'])
                  ->setSenha($row['senha'])
                  ->setTelefone($row['telefone']);
                  
            return $pessoa;
        } catch (PDOException $e) {
            echo "Erro ao buscar: " . $e->getMessage();
            return null;
        }
    }
}
?>
