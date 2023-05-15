<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => '',

];

$coupon_sid = isset($_POST['coupon_sid']) ? $_POST['coupon_sid'] : '';
$member_sid = isset($_POST['member_sid']) ? $_POST['member_sid'] : '';


if (!empty($coupon_sid) and !empty($member_sid)) {

    $sql = "INSERT INTO `mem_coupon_send`(
`coupon_sid`, `member_sid`, `coupon_status`, 
`update_time`, `create_time`) VALUES (
?, ?, ?,
NOW(),
NOW()
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $coupon_sid,
        $member_sid,
        0,
    ]);
}

$output['success'] = !!$stmt->rowCount();
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
