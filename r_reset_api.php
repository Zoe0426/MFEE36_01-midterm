<?php
require './partsNOEDIT/connect-db.php';
$output = [
    'success' => false,
    'postData' => $_POST,
    'code' => 0,
    'error' => [],
];


$sql = "SELECT ri.*, rc.`catg_name` FROM `rest_info` ri JOIN `rest_catg` rc ON ri.`catg_sid` = rc.`catg_sid` ORDER BY rest_sid DESC";
$data = $pdo->query($sql)->fetchAll();

$output['success'] = true;
$output['postData'] = $data;
$output['code'] = 0;
$output['error'] = [];



header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
