<?php

require_once '../DatabaseConfig.php';
require_once '../Exame.php';
require_once 'PacienteDAO.php';
require_once 'ConsultaDAO.php';

class ExameDAO {
    private $conn;
    private $pacienteDAO;
    private $consultaDAO;

    public function __construct() {
        $this->conn = DatabaseConfig::getInstance()->getConnection();
        $this->pacienteDAO = new PacienteDAO();
        $this->consultaDAO = new ConsultaDAO();
    }

    public function insert(Exame $exame) {
        $sql = "INSERT INTO tb_exame (data, id_paciente, tipo, resultado, id_consulta)
                VALUES (:data, :id_paciente, :tipo, :resultado, :id_consulta)";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':data', $exame->getData()->format('Y-m-d'), PDO::PARAM_STR);
            $stmt->bindValue(':id_paciente', $exame->getPaciente()->getId(), PDO::PARAM_INT);
            $stmt->bindValue(':tipo', $exame->getTipo(), PDO::PARAM_STR);
            $stmt->bindValue(':resultado', $exame->getResultado(), PDO::PARAM_STR);

            // A consulta é opcional no modelo, então precisamos verificar se existe
            $consulta = $exame->getConsulta();
            $id_consulta = $consulta ? $consulta->getId() : null;
            $stmt->bindValue(':id_consulta', $id_consulta, $id_consulta ? PDO::PARAM_INT : PDO::PARAM_NULL);

            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Erro ao inserir exame: " . $e->getMessage();
            return false;
        }
    }

    public function update(Exame $exame) {
        $sql = "UPDATE tb_exame 
                SET data = :data, 
                    id_paciente = :id_paciente, 
                    tipo = :tipo, 
                    resultado = :resultado, 
                    id_consulta = :id_consulta
                WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':data', $exame->getData()->format('Y-m-d'), PDO::PARAM_STR);
            $stmt->bindValue(':id_paciente', $exame->getPaciente()->getId(), PDO::PARAM_INT);
            $stmt->bindValue(':tipo', $exame->getTipo(), PDO::PARAM_STR);
            $stmt->bindValue(':resultado', $exame->getResultado(), PDO::PARAM_STR);
            
            // A consulta é opcional no modelo, então precisamos verificar se existe
            $consulta = $exame->getConsulta();
            $id_consulta = $consulta ? $consulta->getId() : null;
            $stmt->bindValue(':id_consulta', $id_consulta, $id_consulta ? PDO::PARAM_INT : PDO::PARAM_NULL);
            
            $stmt->bindValue(':id', $exame->getId(), PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao atualizar exame: " . $e->getMessage();
            return false;
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM tb_exame WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao excluir exame: " . $e->getMessage();
            return false;
        }
    }

    public function findAll() {
        $sql = "SELECT * FROM tb_exame";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            $exames = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $exame = $this->createExameFromRow($row);
                if ($exame) {
                    $exames[] = $exame;
                }
            }

            return $exames;
        } catch (PDOException $e) {
            echo "Erro na consulta de exames: " . $e->getMessage();
            return [];
        }
    }

    public function findById($id) {
        $sql = "SELECT * FROM tb_exame WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return null;
            }

            return $this->createExameFromRow($row);
        } catch (PDOException $e) {
            echo "Erro ao buscar exame: " . $e->getMessage();
            return null;
        }
    }

    public function findByPaciente($idPaciente) {
        $sql = "SELECT * FROM tb_exame WHERE id_paciente = :id_paciente";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_paciente', $idPaciente, PDO::PARAM_INT);
            $stmt->execute();

            $exames = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $exame = $this->createExameFromRow($row);
                if ($exame) {
                    $exames[] = $exame;
                }
            }

            return $exames;
        } catch (PDOException $e) {
            echo "Erro ao buscar exames do paciente: " . $e->getMessage();
            return [];
        }
    }

    public function findByConsulta($idConsulta) {
        $sql = "SELECT * FROM tb_exame WHERE id_consulta = :id_consulta";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_consulta', $idConsulta, PDO::PARAM_INT);
            $stmt->execute();

            $exames = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $exame = $this->createExameFromRow($row);
                if ($exame) {
                    $exames[] = $exame;
                }
            }

            return $exames;
        } catch (PDOException $e) {
            echo "Erro ao buscar exames da consulta: " . $e->getMessage();
            return [];
        }
    }

    private function createExameFromRow($row) {
        try {
            $exame = new Exame();
            $exame->setId($row['id'])
                 ->setData(new DateTime($row['data']))
                 ->setTipo($row['tipo']);
            
            if ($row['resultado'] !== null) {
                $exame->setResultado($row['resultado']);
            }

            // Carrega o paciente se o ID estiver presente
            if ($row['id_paciente']) {
                $paciente = $this->pacienteDAO->findById($row['id_paciente']);
                if ($paciente) {
                    $exame->setPaciente($paciente);
                }
            }

            // Carrega a consulta se o ID estiver presente (é opcional)
            if ($row['id_consulta']) {
                $consulta = $this->consultaDAO->findById($row['id_consulta']);
                if ($consulta) {
                    $exame->setConsulta($consulta);
                }
            }

            return $exame;
        } catch (Exception $e) {
            echo "Erro ao criar objeto Exame: " . $e->getMessage();
            return null;
        }
    }
}
?>
