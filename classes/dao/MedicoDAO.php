<?php

require_once '../classes/DatabaseConfig.php';
require_once '../classes/Medico.php';
require_once '../classes/dao/PessoaDAO.php';

class MedicoDAO {
    private $conn;
    private $pessoaDAO;
    
    public function __construct() {
        $this->conn = DatabaseConfig::getInstance()->getConnection();
        $this->pessoaDAO = new PessoaDAO();
    }
    
    public function insert(Medico $medico) {
        try {
            // Primeiro, inserimos os dados da pessoa se não já existir um ID de pessoa
            $this->conn->beginTransaction();
            
            if (!$medico->getIdPessoa() && $medico->getPessoa()) {
                $pessoaId = $this->pessoaDAO->insert($medico->getPessoa());
                if (!$pessoaId) {
                    $this->conn->rollBack();
                    return false;
                }
                $medico->setIdPessoa($pessoaId);
            }
            
            // Agora inserimos os dados específicos do médico
            $sql = "INSERT INTO tb_medico (id_pessoa, registro_crf, matricula, especializacao) 
                    VALUES (:id_pessoa, :registro_crf, :matricula, :especializacao)";
                    
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_pessoa', $medico->getIdPessoa(), PDO::PARAM_INT);
            $stmt->bindValue(':registro_crf', $medico->getRegistroCrf(), PDO::PARAM_STR);
            $stmt->bindValue(':matricula', $medico->getMatricula(), PDO::PARAM_STR);
            $stmt->bindValue(':especializacao', $medico->getEspecializacao(), PDO::PARAM_STR);
            
            $stmt->execute();
            $medicoId = $this->conn->lastInsertId();
            $this->conn->commit();
            
            $medico->setId($medicoId);
            return $medicoId;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            echo "Erro ao inserir médico: " . $e->getMessage();
            return false;
        }
    }
    
    public function findAll() {
        $sql = "SELECT m.*, p.* 
                FROM tb_medico m 
                JOIN tb_pessoa p ON m.id_pessoa = p.id";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $medicos = [];
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
                
                $medico = new Medico();
                $medico->setId($row['id'])
                      ->setIdPessoa($row['id_pessoa'])
                      ->setRegistroCrf($row['registro_crf'])
                      ->setMatricula($row['matricula'])
                      ->setEspecializacao($row['especializacao'])
                      ->setPessoa($pessoa);
                      
                $medicos[] = $medico;
            }
            
            return $medicos;
        } catch (PDOException $e) {
            echo "Erro na consulta de médicos: " . $e->getMessage();
            return false;
        }
    }
    
    public function findById($id) {
        $sql = "SELECT m.*, p.* 
                FROM tb_medico m 
                JOIN tb_pessoa p ON m.id_pessoa = p.id 
                WHERE m.id = :id";
        
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
            
            $medico = new Medico();
            $medico->setId($row['id'])
                  ->setIdPessoa($row['id_pessoa'])
                  ->setRegistroCrf($row['registro_crf'])
                  ->setMatricula($row['matricula'])
                  ->setEspecializacao($row['especializacao'])
                  ->setPessoa($pessoa);
                  
            return $medico;
        } catch (PDOException $e) {
            echo "Erro ao buscar médico: " . $e->getMessage();
            return null;
        }
    }
}
?>
