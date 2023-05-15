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

$sql2 = "DELETE FROM rest_c_rr WHERE rest_sid = $delSid";
$stm2 = $pdo->query($sql2);

$sql3 = "DELETE FROM rest_c_rs WHERE rest_sid = $delSid";
$stm3 = $pdo->query($sql3);

# 待加： 確認刪除成功，更改success訊息
