<?php
require './partsNOEDIT/connect-db.php';

// $output = [
//     'success' => false, #新增資料成功或失敗的結果（MUST）
//     'postData' => $_POST, # 除錯用的
//     'code' => 0,
//     'error' => [],
// ];
$shopOrders = $_POST['prod'];
foreach ($shopOrders as $d) {
    echo urldecode($d);
}
// print_r($shopOrders);
header('Content-Type: application/json');
echo json_encode($shopOrders, JSON_UNESCAPED_UNICODE);
