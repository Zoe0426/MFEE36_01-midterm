<?php
require './partsNOEDIT/connect-db.php';
// $coupon_sid = isset($_GET["coupon_sid"]) ? $_GET["coupon_sid"] : '';
$sql = "SELECT * FROM mem_coupon_type WHERE coupon_sid='COUPON00001'";
$r = $pdo->query($sql);
$result = $r->fetch();


header('Content-Type: application/json');
echo json_encode($result, JSON_UNESCAPED_UNICODE);
