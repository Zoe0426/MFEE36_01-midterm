<?php
require './partsNOEDIT/connect-db.php';
$output = [
    'success' => false,
    'getData' => $_GET,
    'code' => 0,
    'error' => [],
];



$catgSid = $_GET['catg_sid'];

$stmt = $pdo->query("SELECT ri.*, rc.catg_name FROM rest_info AS ri JOIN rest_catg AS rc ON ri.catg_sid = rc.catg_sid WHERE ri.catg_sid = $catgSid");
$data = $stmt->fetchAll();



$output['success'] = !!$stmt->rowCount();
$output['getData'] = $data;



header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
