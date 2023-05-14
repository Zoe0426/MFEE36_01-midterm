<?php
require './partsNOEDIT/connect-db.php';

// $output = [
//     'success' => false, #新增資料成功或失敗的結果（MUST）
//     'postData' => $_POST, # 除錯用的
//     'code' => 0,
//     'error' => [],
// ];
$shopOrders = isset($_POST['prod']) ? $_POST['prod'] : '';
$actOrders = isset($_POST['act']) ? $_POST['act'] : '';



if ($_POST['prod']) {
    $forProd = [];
    foreach ($shopOrders as $d) {
        $dParsed =  urldecode($d);
        // $dParsed . [pro_sid];
    }
}


// print_r($shopOrders);
header('Content-Type: application/json');
echo json_encode($shopOrders, JSON_UNESCAPED_UNICODE);
