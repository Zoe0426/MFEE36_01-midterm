<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => '',

];
$sid = isset($_POST["coupon_sid"]) ? $_POST["coupon_sid"] : '';
$code = isset($_POST['coupon_code']) ? $_POST['coupon_code'] : '';
$name =  isset($_POST['coupon_name']) ? $_POST['coupon_name'] : '';
$price = isset($_POST['coupon_price']) ? intval($_POST['coupon_price']) : 0;
$startDate = isset($_POST['coupon_startDate']) ? date('Y-m-d', strtotime($_POST['coupon_startDate'])) : '';
$expDate = isset($_POST['coupon_expDate']) ? date('Y-m-d', strtotime($_POST['coupon_expDate'])) : '';

$sql = "UPDATE mem_coupon_type SET 
coupon_code=?, 
coupon_name=?, 
coupon_price=?, 
coupon_startDate=?, 
coupon_expDate=?,
update_time=NOW()
WHERE coupon_sid=?";


$stmt = $pdo->prepare($sql);
$stmt->execute([
    $code,
    $name,
    $price,
    $startDate,
    $expDate,
    $sid,
]);

$output['success'] = !!$stmt->rowCount();

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
