<?php
require './partsNOEDIT/connect-db.php';

$perPage = 25;       #顯示幾筆資料
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;           #用戶要看第幾頁

if ($page < 1) {
    header('Location: ?page=1');
    exit;
};

$t_sql = "SELECT COUNT(*) 
FROM `shop_proDet` pd
JOIN `shop_pro` p ON pd.`pro_sid`=p.`pro_sid`";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

if ($totalRows) {
    $totalPages = ceil($totalRows / $perPage);
    if ($page > $totalPages) {
        header("Location: ?page=$totalPages");
        exit;
    };
    $sql = sprintf('SELECT * FROM `shop_proDet` pd
    JOIN `shop_pro` p ON pd.`pro_sid`=p.`pro_sid` 
    JOIN `shop_cat` c ON p.`cat_sid`=c.`cat_sid` and p.`catDet_sid` =c.`catDet_sid` WHERE p.`pro_status` !=3 LIMIT %s, %s', ($page - 1) * $perPage, $perPage);
    $rows = $pdo->query($sql)->fetchAll();
};

header('Content-Type: application/json');
echo json_encode([
    'perPage' => $perPage,
    'page' => $page,
    'totalRows' => $totalRows,
    'totalPages' => $totalPages,
    'rows' => $rows
], JSON_UNESCAPED_UNICODE);
