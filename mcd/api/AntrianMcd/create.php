<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../../config/database.php';
include_once '../../models/AntrianMcd.php';
$database = new Database();
$db = $database->getConnection();
$item = new AntrianMcd($db);
$data = json_decode(file_get_contents("php://input"));
if(!$data) {
    http_response_code(400);
    echo json_encode('Error: Invalid JSON data.');
    exit();
}

$item->waktu_kedatangan = $data->waktu_kedatangan;
$item->selisih_kedatangan = $data->selisih_kedatangan;
$item->awal_layanan = $data->awal_layanan;
$item->selisih_pelayanan = $data->selisih_pelayanan;
$item->waktu_selesai = $data->waktu_selesai;
$item->selisih_akhir = $data->selisih_akhir;
if($item->createData()){
    echo json_encode('Data created successfully.');
} else{
    echo json_encode('Data could not be created. Error: ' . $item->getLastError());
}
?>