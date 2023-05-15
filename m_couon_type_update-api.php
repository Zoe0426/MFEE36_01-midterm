<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => '',

];

$sid = isset($_GET["sid"]) ? intval($_GET["sid"]) : 0;
$sql = "SELECT * FROM mem_coupon_type WHERE sid={$sid}";
$r = $pdo->query($sql)->fetch();

// if (!empty($code) and !empty($name) and !empty($price) and !empty($startDate) and !empty($expDate)) {
//     $sqlhead = "SELECT IFNULL(MAX(coupon_sid), 'COUPON0000') FROM `mem_coupon_type`";
//     $stmt = $pdo->query($sqlhead);
//     $last_ord_sid = $stmt->fetchColumn();

//     if ($last_ord_sid === false) { // 空表格的話，第一筆是xxx0001
//         $new_ord_sid = 'COUPON00001';
//     } else { // 有訂單
//         $new_ord_num = (int)substr($last_ord_sid, 6) + 1;
//         $new_ord_sid = 'COUPON' . sprintf('%05d', $new_ord_num);
//     }


//     $sql = "INSERT INTO `mem_coupon_type`(
//     `coupon_sid`, `coupon_code`, `coupon_name`, 
//     `coupon_price`, `coupon_startDate`, `coupon_expDate`, 
//     `update_time`, `creat_time`) VALUES (
//         ?, ?, ?,
//         ?, ?, ?,
//         NOW(), NOW()
//     )";

//     $stmt2 = $pdo->prepare($sql);
//     $stmt2->execute([
//         $new_ord_sid,
//         $code,
//         $name,
//         $price,
//         $startDate,
//         $expDate
//     ]);

//     $output['success'] = !!$stmt2->rowCount();
// } else {
//     $output['error'] = '所有欄位必填寫';
// }




header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
