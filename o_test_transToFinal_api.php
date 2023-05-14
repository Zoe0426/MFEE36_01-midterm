<?php

require './partsNOEDIT/connect-db.php';



$d = "%7B%22pro_sid%22%3A%22P003%22%2C%22pro_name%22%3A%22Pet%20Health%20C%22%2C%22proDet_sid%22%3A%22PD008%22%2C%22proDet_name%22%3A%22Pet%20Health%20C%20Medium%22%2C%22proDet_price%22%3A%22500%22%2C%22proDet_qty%22%3A%22100%22%2C%22prodQty%22%3A%221%22%7D";
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
echo json_encode($result, JSON_UNESCAPED_UNICODE);

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
