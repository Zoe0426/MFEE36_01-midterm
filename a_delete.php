<?php
//require 'a_admin_required.php';
require './partsNOEDIT/connect-db.php';


$output = [
    'success' => false, #刪除成功或失敗的結果（MUST）
    'code' => 0,
    'error' => [],
];

if (!empty($_GET['act_sid'])) {
    $act_sid = intval($_GET['act_sid']);
    //echo intval($_GET['act_sid']);

    $sql = "DELETE FROM `act_info` WHERE `act_sid`={$act_sid}";
    $pdo->query($sql);
    $output["act_sid"] = $_GET['act_sid'];
    $output['success'] = true;
} else {
    $output['dataStatus'] = "no act sid";
}


header('Content-Type: application/json');
echo json_encode($_GET['act_sid'], JSON_UNESCAPED_UNICODE);
