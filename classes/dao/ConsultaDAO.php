<?php

require_once '../classes/DatabaseConfig.php';
require_once '../classes/Consulta.php';
require_once '../classes/dao/MedicoDAO.php';
require_once '../classes/dao/PacienteDAO.php';

class ConsultaDAO {
    private $conn;
    private $medicoDAO;
    private $pacienteDAO;
    
    public function __construct() {
        $this->conn = DatabaseConfig::getInstance()->getConnection();
        $this->medicoDAO = new MedicoDAO();
        $this->pacienteDAO = new PacienteDAO();
    }
    
    public function insert(Consulta $consulta) {
        $sql = "INSERT INTO tb_consulta (data, id_medico, id_paciente, diagnostico, receita, presencial) 
                VALUES (:data, :id_medico, :id_paciente, :diagnostico, :receita, :presencial)";
                
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':data', $consulta->getData()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->bindValue(':id_medico', $this->getMedicoId($consulta->getMedico()), PDO::PARAM_INT);
            $stmt->bindValue(':id_paciente', $this->getPacienteId($consulta->getPaciente()), PDO::PARAM_INT);
            $stmt->bindValue(':diagnostico', $consulta->getDiagnostico(), PDO::PARAM_STR);
            $stmt->bindValue(':receita', $consulta->getReceita(), PDO::PARAM_STR);
            $stmt->bindValue(':presencial', $consulta->getPresencial() ? 1 : 0, PDO::PARAM_BOOL);
            
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Erro ao inserir consulta: " . $e->getMessage();
            return false;
        }
    }
    
    public function findAll() {
        $sql = "SELECT c.*, m.id, p.id 
                FROM tb_consulta c
                JOIN tb_medico m ON c.id_medico = m.id
                JOIN tb_paciente p ON c.id_paciente = p.id";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $consultas = [];
            while ($row = $stmt->fetch()) {
                $medico = $this->medicoDAO->findById($row['id_medico']);
                $paciente = $this->pacienteDAO->findById($row['id_paciente']);
                
                $consulta = new Consulta();
                $consulta->setData(new DateTime($row['data']))
                        ->setMedico($medico)
                        ->setPaciente($paciente)
                        ->setDiagnostico($row['diagnostico'])
                        ->setReceita($row['receita'])
                        ->setPresencial((bool)$row['presencial']);
                        
                $consultas[] = $consulta;
            }
            
            return $consultas;
        } catch (PDOException $e) {
            echo "Erro na consulta: " . $e->getMessage();
            return false;
        }
    }
    
    public function findById($id) {
        $sql = "SELECT c.*, m.id, p.id 
                FROM tb_consulta c
                JOIN tb_medico m ON c.id_medico = m.id
                JOIN tb_paciente p ON c.id_paciente = p.id
                WHERE c.id = :id";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch();
            if (!$row) {
                return null;
            }
            
            $medico = $this->medicoDAO->findById($row['id_medico']);
            $paciente = $this->pacienteDAO->findById($row['id_paciente']);
            
            $consulta = new Consulta();
            $consulta->setData(new DateTime($row['data']))
                    ->setMedico($medico)
                    ->setPaciente($paciente)
                    ->setDiagnostico($row['diagnostico'])
                    ->setReceita($row['receita'])
                    ->setPresencial((bool)$row['presencial']);
                    
            return $consulta;
        } catch (PDOException $e) {
            echo "Erro ao buscar consulta: " . $e->getMessage();
            return null;
        }
    }
    
    private function getMedicoId($medico) {
        return $medico->getId;
    }
    
    private function getPacienteId($paciente) {
        return $paciente->getId;
    }
}
?>
