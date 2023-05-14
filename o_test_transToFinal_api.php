<?php

require './partsNOEDIT/connect-db.php';



$d = "%7B%22act_sid%22%3A%221%22%2C%22act_name%22%3A%22%E5%8F%B0%E5%8C%97%E8%88%87%E6%AF%9B%E5%AE%B6%E5%BA%AD%E6%9C%89%E7%B4%84%EF%BC%8C%E9%82%80%E4%BD%A0%E4%B8%80%E8%B5%B7%E4%BE%86%E6%8C%BA%E5%AF%B5%EF%BC%81%22%2C%22group_sid%22%3A%223%22%2C%22group_date%22%3A%222023-07-22%22%2C%22price_adult%22%3A%22200%22%2C%22price_kid%22%3A%22100%22%2C%22ppl_max%22%3A%2250%22%2C%22adultQty%22%3A%221%22%2C%22childQty%22%3A%221%22%7D";
$dParsed = urldecode($d);
$dataget = json_decode($dParsed, true);
$dProsid = $dataget['pro_sid'];
$dProDetsid = $dataget['proDet_sid'];
$dProQty = intval($dataget['prodQty']);

$sql = "SELECT sp.`pro_sid`, spd.`proDet_sid`, sp.`pro_name`, spd.`proDet_name`, spd.`proDet_price`, spd.proDet_price*? FROM `shop_pro` sp JOIN `shop_prodet` spd ON sp.`pro_sid` = spd.`pro_sid` WHERE sp.pro_sid=? AND spd.proDet_sid = ?";
$stm = $pdo->prepare($sql);
$stm->execute([$dProQty, $dProsid, $dProDetsid]);
$result = $stm->fetch();

header('Content-Type: application/json');
echo json_encode($forProd, JSON_UNESCAPED_UNICODE);

// $sqlCoupon = "SELECT
// cs.member_sid,
// cs.coupon_sid,
// ct.coupon_code,
// ct.coupon_name,
// ct.coupon_price,
// ct.coupon_expDate
// FROM
// mem_coupon_send cs
// JOIN mem_coupon_type ct ON cs.coupon_sid = ct.coupon_sid
// WHERE
// ct.coupon_expDate > NOW()
// AND cs.coupon_status = 0
// AND cs.member_sid = ?";

// $stm2 = $pdo->prepare($sqlCoupon);
// $stm2->execute([$mem]);
// $coupons = $stm2->fetchAll();
