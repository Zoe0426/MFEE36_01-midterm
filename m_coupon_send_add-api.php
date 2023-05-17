<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => '',

];

$coupon_sid = isset($_GET['coupon_sid']) ? $_GET['coupon_sid'] : '';
$member_sid = isset($_GET['member_sid']) ? $_GET['member_sid'] : '';




$sql = "INSERT INTO `mem_coupon_send`(
`coupon_sid`, `member_sid`, `coupon_status`, `used_time` ,`create_time`) VALUES (
?, ?, ?, ?,
NOW()
    )";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $coupon_sid,
    $member_sid,
    0,
    null
]);


$output['success'] = !!$stmt->rowCount();
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
