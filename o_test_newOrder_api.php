<?php
//連資料庫
require './partsNOEDIT/connect-db.php';

$sqlHead = "SELECT IFNULL(MAX(order_sid), 'ORD0000') FROM `ord_order`";
$stmt1 = $pdo->query($sqlHead);
$last_ord_sid = $stmt1->fetchColumn();

if ($last_ord_sid === false) { // 空表格的話，第一筆是xxx0001
    $new_ord_sid = 'ORD00001';
} else { // 有訂單
    $new_ord_num = (int)substr($last_ord_sid, 3) + 1;
    $new_ord_sid = 'ORD' . sprintf('%05d', $new_ord_num);
}

// 加到父表格
$sqlParent = "INSERT INTO `ord_order`
(`order_sid`, `member_sid`, `coupon_sid`, 
`postAddress`, `postType`, `postStatus`, 
`treadType`, `relAmount`, `postAmount`, 
`couponAmount`, `order_status`, `creator`, 
`createDt`, `moder`, `modDt`) 
VALUES 
(?,?,?,
?,?,?,
?,?,?,
?,?,?,
NOW(),?,NOW())";
$stmt2 = $pdo->prepare($sqlParent);
$stmt2->execute([
    $new_ord_sid, 'member_sid', 'coupon_sid',
    'postAddress', 1, 1,
    3, 300, 80,
    60, 0, "Admin01",
    null
]);

//加到子表格

$sqlChild = "INSERT INTO `ord_details`
(`order_sid`, `relType`, `rel_sid`, 
`rel_seq_sid`, `relName`, `rel_seqName`, 
`prodAmount`, `prodQty`, `adultAmount`, 
`adultQty`, `childAmount`, `childQty`, 
`amount`) 
VALUES 
(?,?,?,
?,?,?,
?,?,?,
?,?,?,
?)";
$stm3 = $pdo->prepare($sqlChild);

$stm3->execute([
    $new_ord_sid, 'prod', 'P010',
    'PD020', 'Dogfood', "pork",
    500, 2, null,
    null, null, null,
    1000
]);
