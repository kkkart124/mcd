<?php
class AntrianMcd{
    // Connection
    private $conn;
    // Table
    private $db_table = "antrian_mcd";
    // Columns
    public $id;
    public $waktu_kedatangan;
    public $selisih_kedatangan;
    public $awal_layanan;
    public $selisih_pelayanan;
    public $waktu_selesai;
    public $selisih_akhir;
    // Db connection
    public function __construct($db){
        $this->conn = $db;
    }
    // GET ALL
    public function getAll(){
        $sqlQuery = "SELECT id, waktu_kedatangan, selisih_kedatangan, awal_layanan, selisih_pelayanan, waktu_selesai, selisih_akhir FROM ". $this->db_table . "";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }
    // CREATE
    public function createData(){
        $sqlQuery = "INSERT INTO ". $this->db_table ."
        SET
        waktu_kedatangan= :waktu_kedatangan,
        selisih_kedatangan= :selisih_kedatangan,
        awal_layanan= :awal_layanan,
        selisih_pelayanan= :selisih_pelayanan,
        waktu_selesai= :waktu_selesai,
        selisih_akhir= :selisih_akhir";
        $stmt = $this->conn->prepare($sqlQuery);
            // sanitize
            $this->waktu_kedatangan=htmlspecialchars(strip_tags($this->waktu_kedatangan));
            $this->selisih_kedatangan=htmlspecialchars(strip_tags($this->selisih_kedatangan));
            $this->awal_layanan=htmlspecialchars(strip_tags($this->awal_layanan));
            $this->selisih_pelayanan=htmlspecialchars(strip_tags($this->selisih_pelayanan));
            $this->waktu_selesai=htmlspecialchars(strip_tags($this->waktu_selesai));
            $this->selisih_akhir=htmlspecialchars(strip_tags($this->selisih_akhir));
            // bind data
            $stmt->bindParam(":waktu_kedatangan", $this->waktu_kedatangan);
            $stmt->bindParam(":selisih_kedatangan", $this->selisih_kedatangan);
            $stmt->bindParam(":awal_layanan", $this->awal_layanan);
            $stmt->bindParam(":selisih_pelayanan", $this->selisih_pelayanan);
            $stmt->bindParam(":waktu_selesai", $this->waktu_selesai);
            $stmt->bindParam(":selisih_akhir", $this->selisih_akhir);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    // READ single
    public function getSingleUser(){
        $sqlQuery = "SELECT
        id,
        waktu_kedatangan,
        selisih_kedatangan,
        awal_layanan,
        selisih_pelayanan,
        waktu_selesai,
        selisih_akhir  
        FROM
        ". $this->db_table ."
        WHERE
        id = ?
        LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->waktu_kedatangan = $dataRow['waktu_kedatangan'];
        $this->selisih_kedatangan = $dataRow['selisih_kedatangan'];
        $this->awal_layanan = $dataRow['awal_layanan'];
        $this->selisih_pelayanan = $dataRow['selisih_pelayanan'];
        $this->waktu_selesai = $dataRow['waktu_selesai'];
        $this->selisih_akhir = $dataRow['selisih_akhir'];
    }
    // UPDATE
    public function updateData(){
        $sqlQuery = "UPDATE
        ". $this->db_table ."
        SET
        waktu_kedatangan = :waktu_kedatangan,
        selisih_kedatangan = :selisih_kedatangan,
        awal_layanan = :awal_layanan,
        selisih_pelayanan = :selisih_pelayanan,
        waktu_selesai = :waktu_selesai,
        selisih_akhir = :selisih_akhir
        WHERE
        id = :id";
        $stmt = $this->conn->prepare($sqlQuery);
        
        $this->waktu_kedatangan=htmlspecialchars(strip_tags($this->waktu_kedatangan));
        $this->selisih_kedatangan=htmlspecialchars(strip_tags($this->selisih_kedatangan));
        $this->awal_layanan=htmlspecialchars(strip_tags($this->awal_layanan));
        $this->selisih_pelayanan=htmlspecialchars(strip_tags($this->selisih_pelayanan));
        $this->waktu_selesai=htmlspecialchars(strip_tags($this->waktu_selesai));
        $this->selisih_akhir=htmlspecialchars(strip_tags($this->selisih_akhir));
        $this->id=htmlspecialchars(strip_tags($this->id));
        // bind data
        $stmt->bindParam(":waktu_kedatangan", $this->waktu_kedatangan);
        $stmt->bindParam(":selisih_kedatangan", $this->selisih_kedatangan);
        $stmt->bindParam(":awal_layanan", $this->awal_layanan);
        $stmt->bindParam(":selisih_pelayanan", $this->selisih_pelayanan);
        $stmt->bindParam(":waktu_selesai", $this->waktu_selesai);
        $stmt->bindParam(":selisih_akhir", $this->selisih_akhir);
        $stmt->bindParam(":id", $this->id);
        $stmt->fetchAll();

        try {
            $stmt->execute();
        }
        catch(PDOException $exception) {
            die($exception->getMessage());
        }
        
        if (count($stmt->fetchAll()) == 0) {
            return true;
        }
    }
    // DELETE
    function deleteData(){
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function generateByAVG(){
        $sqlQuery = "SELECT 
        MIN(selisih_kedatangan) AS min_sk,
        MAX(selisih_kedatangan) AS max_sk,
        AVG(selisih_kedatangan) AS avg_sk, 
        MIN(selisih_pelayanan) AS min_sp,
        MAX(selisih_pelayanan) AS max_sp,
        AVG(selisih_pelayanan) AS avg_sp,
        AVG(selisih_akhir) AS avg_sa      
        FROM ". $this->db_table;
        
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $this->sk_min = $dataRow['min_sk'];
        $this->sk_max = $dataRow['max_sk'];
        $this->sk_avg = $dataRow['avg_sk'];
        $this->sp_min = $dataRow['min_sp'];
        $this->sp_max = $dataRow['max_sp'];
        $this->sp_avg = $dataRow['avg_sp'];
        $this->sa_avg = $dataRow['avg_sa'];

    }
    }
?>