<?php

require_once '../classes/DatabaseConfig.php';
require_once '../classes/Paciente.php';
require_once '../classes/dao/PessoaDAO.php';

class PacienteDAO {
    private $conn;
    private $pessoaDAO;
    
    public function __construct() {
        $this->conn = DatabaseConfig::getInstance()->getConnection();
        $this->pessoaDAO = new PessoaDAO();
    }
    
    public function insert(Paciente $paciente) {
        try {
            // Primeiro, inserimos os dados da pessoa se não já existir um ID de pessoa
            $this->conn->beginTransaction();
            
            if (!$paciente->getIdPessoa() && $paciente->getPessoa()) {
                $pessoaId = $this->pessoaDAO->insert($paciente->getPessoa());
                if (!$pessoaId) {
                    $this->conn->rollBack();
                    return false;
                }
                $paciente->setIdPessoa($pessoaId);
            }
            
            // Agora inserimos os dados específicos do paciente
            $sql = "INSERT INTO tb_paciente (id_pessoa, metodo_pagamento, logradouro, numero, bairro, cidade, uf) 
                    VALUES (:id_pessoa, :metodo_pagamento, :logradouro, :numero, :bairro, :cidade, :uf)";
                    
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_pessoa', $paciente->getIdPessoa(), PDO::PARAM_INT);
            $stmt->bindValue(':metodo_pagamento', $paciente->getMetodoPagamento(), PDO::PARAM_STR);
            $stmt->bindValue(':logradouro', $paciente->getLogradouro(), PDO::PARAM_STR);
            $stmt->bindValue(':numero', $paciente->getNumero(), PDO::PARAM_STR);
            $stmt->bindValue(':bairro', $paciente->getBairro(), PDO::PARAM_STR);
            $stmt->bindValue(':cidade', $paciente->getCidade(), PDO::PARAM_STR);
            $stmt->bindValue(':uf', $paciente->getUF(), PDO::PARAM_STR);
            
            $stmt->execute();
            $pacienteId = $this->conn->lastInsertId();
            $this->conn->commit();
            
            $paciente->setId($pacienteId);
            return $pacienteId;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            echo "Erro ao inserir paciente: " . $e->getMessage();
            return false;
        }
    }
    
    public function findAll() {
        $sql = "SELECT pa.*, p.* 
                FROM tb_paciente pa 
                JOIN tb_pessoa p ON pa.id_pessoa = p.id";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $pacientes = [];
            while ($row = $stmt->fetch()) {
                $pessoa = new Pessoa();
                $pessoa->setId($row['id_pessoa'])
                      ->setNome($row['nome'])
                      ->setRg($row['rg'])
                      ->setCpf($row['cpf'])
                      ->setDataNasc($row['data_nasc'])
                      ->setSexo($row['sexo'])
                      ->setEmail($row['email'])
                      ->setSenha($row['senha'])
                      ->setTelefone($row['telefone']);
                
                $paciente = new Paciente();
                $paciente->setId($row['id'])
                        ->setIdPessoa($row['id_pessoa'])
                        ->setMetodoPagamento($row['metodo_pagamento'])
                        ->setLogradouro($row['logradouro'])
                        ->setNumero($row['numero'])
                        ->setBairro($row['bairro'])
                        ->setCidade($row['cidade'])
                        ->setUF($row['uf'])
                        ->setPessoa($pessoa);
                        
                $pacientes[] = $paciente;
            }
            
            return $pacientes;
        } catch (PDOException $e) {
            echo "Erro na consulta de pacientes: " . $e->getMessage();
            return false;
        }
    }
    
    public function findById($id) {
        $sql = "SELECT pa.*, p.* 
                FROM tb_paciente pa 
                JOIN tb_pessoa p ON pa.id_pessoa = p.id 
                WHERE pa.id = :id";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch();
            if (!$row) {
                return null;
            }
            
            $pessoa = new Pessoa();
            $pessoa->setId($row['id_pessoa'])
                  ->setNome($row['nome'])
                  ->setRg($row['rg'])
                  ->setCpf($row['cpf'])
                  ->setDataNasc($row['data_nasc'])
                  ->setSexo($row['sexo'])
                  ->setEmail($row['email'])
                  ->setSenha($row['senha'])
                  ->setTelefone($row['telefone']);
            
            $paciente = new Paciente();
            $paciente->setId($row['id'])
                    ->setIdPessoa($row['id_pessoa'])
                    ->setMetodoPagamento($row['metodo_pagamento'])
                    ->setLogradouro($row['logradouro'])
                    ->setNumero($row['numero'])
                    ->setBairro($row['bairro'])
                    ->setCidade($row['cidade'])
                    ->setUF($row['uf'])
                    ->setPessoa($pessoa);
                    
            return $paciente;
        } catch (PDOException $e) {
            echo "Erro ao buscar paciente: " . $e->getMessage();
            return null;
        }
    }
}
?>
