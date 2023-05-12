<?php
//連資料庫
require './partsNOEDIT/connect-db.php';
//=====功能，每次新增資料時，我的ord_sid要有"ORD開頭，並有五個數字自動長流水號==
//=====我的表單叫test_sid, 裡面只有 ord_sid 及 ydata 兩個欄位===


// 取得當前的最大值, 若是空表格，預設ORD0000
$sql = "SELECT IFNULL(MAX(ord_sid), 'xxx0000') FROM `test_sid`";
$stmt = $pdo->query($sql);
$last_ord_sid = $stmt->fetchColumn();

if ($last_ord_sid === false) { // 空表格的話，第一筆是xxx0001
    $new_ord_sid = 'xxx00001';
} else { // 有訂單
    $new_ord_num = (int)substr($last_ord_sid, 3) + 1;
    $new_ord_sid = 'xxx' . sprintf('%05d', $new_ord_num);
}

// 插入新資料
$sql = "INSERT INTO `test_sid` (ord_sid, ydata) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$new_ord_sid, "yyyy"]);
