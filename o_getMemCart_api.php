<?php
require './partsNOEDIT/connect-db.php';

$sbname = isset($_POST['sbname']) ? $_POST['sbname'] : '';
// sbmobile: 
// sbmemsid: 

$sqlName = "SELECT member_sid, member_name, member_mobile, member_birth FROM mem_member WHERE member_name = {$sbname}";
$stm = $pdo->query($sqlName)->fetch();
// $stm->execute([$sbname]);

// $stm = $pdo->query($sqlName)->fetch();


print_r($stm);


// $sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;
// $sql = "SELECT * FROM address_book WHERE sid={$sid}";

// $r = $pdo->query($sql)->fetch();
