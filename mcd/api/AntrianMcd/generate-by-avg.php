<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/AntrianMcd.php';

$database = new Database();
$db = $database->getConnection();
$item = new AntrianMcd($db);
$item->generateByAVG();

if ($item->sk_avg !== null) {
    //create array
    $dataArr = array(
        "waktu_tunggu" => "Waktu minimal konsumen menunggu saat mengantri pada kasir MCD di Solo Grand Mall sebesar" . date("i:s", $item->sk_min) . "menit dan waktu maksimal menunggu
         sebesar " . date("i:s", $item->sk_max) . "menit dengan rata-rata waktu konsumen menunggu sebesar " . date("i:s", $item->sk_avg) . "menit.",
        "kasir_1" =>"Pada kasir 1 MCD di Solo Grand Mall mempunyai jumlah antrian minimal sebanyak" . round($item->sp_min) . 
        "konsumen dan jumlah antrian maksimal sebanyak " . round($item->sp_max) . "konsumen dengan rata-rata jumlah antrian sebanyak " . 
        $item->sp_avg . "konsumen dan jika dibulatkan menjadi " . round($item->sp_avg) . "konsumen.", 
        "selisih_akhir" =>"Maka selisih konsumen keluar dari antrian terrendah " . date("i:s", $item->sa_avg) . "menit.",
    );

    http_response_code(200);
    echo json_encode($dataArr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "User not found."));
}
?>
