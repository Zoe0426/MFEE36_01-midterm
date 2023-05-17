<?php
require './partsNOEDIT/connect-db.php';
$output = [
    'success' => false,
    'postData' => $_POST,
    'code' => 0,
    'error' => [],
];



if (isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];
    $stmt = "SELECT * FROM rest_info WHERE rest_name LIKE '%$keyword%'";



    $output['success'] = true;
    $output['postData'] = $_POST;
    $output['code'] = 0;
    $output['error'] = [];
} else {
    // 沒有 'keyword' 索引，回傳錯誤訊息
    $output['success'] = false;
    $output['error'] = 'Missing keyword parameter';
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
