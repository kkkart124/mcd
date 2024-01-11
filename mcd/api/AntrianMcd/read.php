<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-AllowHeaders, Authorization, X-Requested-With");
include_once '../../config/database.php';
include_once '../../models/AntrianMcd.php';

$database = new Database();
$db = $database->getConnection();
if(isset($_GET['id'])){
    $item = new AntrianMcd($db);
    $item->no = isset($_GET['id']) ? $_GET['id'] : die();
    $item->getSingleData();
    if($item->waktu_kedatangan != null){
        // create array
        $Data_arr = array(
        "id" => $item->id,
        "waktu_kedatangan" => $item->waktu_kedatangan,
        "selisih_kedatangan" => $item->selisih_kedatangan,
        "awal_layanan" => $item->awal_layanan,
        "selisih_pelayanan" => $item->selisih_pelayanan,
        "waktu_selesai" => $item->waktu_selesai,
        "selisih_akhir" => $item->selisih_akhir 
        );
        http_response_code(200);
        echo json_encode($Data_arr);
    }
    else{
        http_response_code(404);
        echo json_encode("AntrianMcd not found.");
    }
}
else {
    $items = new AntrianMcd($db);
    $stmt = $items->getAll();
    $itemCount = $stmt->rowCount();
    if($itemCount > 0){
        $DataArr = array();
        $DataArr["body"] = array();
        $DataArr["itemCount"] = $itemCount;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $e = array(
                "id" => $id,
                "waktu_kedatangan" => $waktu_kedatangan,
                "selisih_kedatangan" => $selisih_kedatangan,
                "awal_layanan" => $awal_layanan,
                "selisih_pelayanan" => $selisih_pelayanan,
                "waktu_selesai" => $waktu_selesai,
                "selisih_akhir" => $selisih_akhir
            );
            array_push($DataArr["body"], $e);
        }
        echo json_encode($DataArr);
    }
    else{
        http_response_code(404);
        echo json_encode(array("messstock" => "No record found."));
    }
}
?>
