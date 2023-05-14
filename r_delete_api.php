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



// 父表格+子表格
$sql = "DELETE rest_info , rest_c_rr , rest_c_rs
FROM rest_info
LEFT JOIN `rest_c_rr` ON rest_c_rr.rest_sid = rest_info.rest_sid
LEFT JOIN `rest_c_rs` ON rest_c_rs.rest_sid = rest_info.rest_sid
WHERE rest_sid = $delSid";


// 只刪除父表格

// $sql = "DELETE FROM rest_info WHERE rest_sid = $delSid";

$stm = $pdo->query($sql);

$comeFrom = 'r_read.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $comeFrom = $_SERVER['HTTP_REFERER'];
}


header('Location: ' . $comeFrom);
# 待加： 確認刪除成功，更改success訊息
