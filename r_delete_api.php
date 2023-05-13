<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false, #刪除成功或失敗的結果（MUST）
    'code' => 0,
    'error' => [],
];

if (!empty($_GET['rest_sid'])) {
    $delSid = $_GET['rest_sid'] ? intval($_GET['rest_sid']) : '';
}

$sql = "DELETE FROM `rest_info` WHERE `rest_sid`=$delSid";
$stm = $pdo->query($sql);

# 待加： 確認刪除成功，更改success訊息

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
