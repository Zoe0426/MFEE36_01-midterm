<?php
require './partsNOEDIT/connect-db.php';

$perPage = 15; # 每頁最多幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; # 用戶要看第幾頁

if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM `ord_order` ";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0]; # 總筆數
$totalPages = ceil($totalRows / $perPage); # 總頁數
$rows = [];

if ($totalRows) {
    if ($page > $totalPages) {
        header("Location: ?page=$totalPages");
        exit;
    }
    $sql = sprintf("SELECT * FROM `ord_order` ORDER BY `order_sid` DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $rows = $pdo->query($sql)->fetchAll();
}

header('Content-Type: application/json');
echo json_encode([
    'perPage' => $perPage, #每頁顯示幾筆
    'page' => $page,   #顯示的頁數
    'totalRows' => $totalRows, #資料表內總資料數
    'totalPages' => $totalPages, #資料顯示總頁數
    'rows' => $rows, #會顯示的當頁資料
], JSON_UNESCAPED_UNICODE);
