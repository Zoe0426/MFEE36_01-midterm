<?php
// require 'a_admin_required.php';
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false,
    'code' => 0,
    'error' => [],
];

if (!empty($_GET['act_sid'])) {
    $act_sid = intval($_GET['act_sid']);

    // 刪除 act_info 表中的資料
    $sql_info = "DELETE FROM `act_info` WHERE `act_sid` = {$act_sid}";
    $pdo->query($sql_info);

    // 刪除 act_group 表中的資料
    $sql_group = "DELETE FROM `act_group` WHERE `act_sid` = {$act_sid}";
    $pdo->query($sql_group);

    $output["act_sid"] = $_GET['act_sid'];
    $output['success'] = true;
} else {
    $output['dataStatus'] = "no act sid";
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
