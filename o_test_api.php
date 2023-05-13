<?php

require './partsNOEDIT/connect-db.php';



$mem = 'mem00001';

$sqlCoupon = "SELECT
cs.member_sid,
cs.coupon_sid,
ct.coupon_code,
ct.coupon_name,
ct.coupon_price,
ct.coupon_expDate
FROM
mem_coupon_send cs
JOIN mem_coupon_type ct ON cs.coupon_sid = ct.coupon_sid
WHERE
ct.coupon_expDate > NOW()
AND cs.coupon_status = 0
AND cs.member_sid = ?";

$stm2 = $pdo->prepare($sqlCoupon);
$stm2->execute([$mem]);
$coupons = $stm2->fetchAll();



// header('Content-Type: application/json');
echo json_encode($coupons, JSON_UNESCAPED_UNICODE);
