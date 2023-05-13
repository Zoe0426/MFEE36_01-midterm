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

// DELETE 父表格, 子表格1, 子表格2
// FROM 父表格
// LEFT JOIN 子表格1 ON 子表格1.外部鍵 = 父表格.主鍵
// LEFT JOIN 子表格2 ON 子表格2.外部鍵 = 父表格.主鍵
// WHERE 條件;



$sql = "DELETE rest_info , rest_c_rr , rest_c_rs`
FROM rest_info
LEFT JOIN `rest_c_rr` ON rest_c_rr.rest_sid = rest_info.rest_sid
LEFT JOIN `rest_c_rs` ON rest_c_rs.rest_sid = rest_info.rest_sid
WHERE rest_sid = $delSid";




$stm = $pdo->query($sql);

# 待加： 確認刪除成功，更改success訊息

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
