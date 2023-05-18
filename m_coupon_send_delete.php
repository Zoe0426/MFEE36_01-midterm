<?php
require "./partsNOEDIT/connect-db.php";

$sid = isset($_GET["couponSend_sid"]) ? $_GET["couponSend_sid"] : '';
$sql = "DELETE FROM `mem_coupon_send` WHERE couponSend_sid=:sid";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':sid', $sid, PDO::PARAM_STR);
$stmt->execute();
$r = $stmt->fetch(PDO::FETCH_ASSOC);
$comeFrom = "m_coupon_send_list.php";
if (!empty($_SERVER["HTTP_REFERER"])) {
    $comeFrom = $_SERVER["HTTP_REFERER"];
};


header("Location: " . $comeFrom);
