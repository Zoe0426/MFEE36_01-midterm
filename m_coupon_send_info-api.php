<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => '',

];
$sid = isset($_GET["coupon_sid"]) ? $_GET["coupon_sid"] : '';
$sql_cupon_detail = "SELECT `coupon_sid`, `coupon_code`, `coupon_name`, `coupon_price`, `coupon_startDate`, `coupon_expDate` FROM `mem_coupon_type` WHERE coupon_sid=:sid";
$stmt_coupon_detail = $pdo->prepare($sql_cupon_detail);
$stmt_coupon_detail->bindValue(':sid', $sid, PDO::PARAM_STR);
$stmt_coupon_detail->execute();
$r1 = $stmt_coupon_detail->fetch(PDO::FETCH_ASSOC);





$sid = isset($_GET["member_sid"]) ? $_GET["member_sid"] : '';
$sql_member_detail = "SELECT `member_sid`, `member_name`, `member_email`, `member_mobile` FROM `mem_member` WHERE member_sid=:sid";
$stmt_member_detail = $pdo->prepare($sql_member_detail);
$stmt_member_detail->bindValue(':sid', $sid, PDO::PARAM_STR);
$stmt_member_detail->execute();
$r2 = $stmt_member_detail->fetch(PDO::FETCH_ASSOC);

$output['r1'] = $r1;
$output['r2'] = $r2;

// $output['success'] = !!$stmt_coupon_detail->rowCount();
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
