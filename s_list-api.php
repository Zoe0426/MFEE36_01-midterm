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
    JOIN `shop_cat` c ON p.`cat_sid`=c.`cat_sid` and p.`catDet_sid` =c.`catDet_sid` WHERE p.`pro_status` !=3 ORDER BY p.`pro_sid` DESC LIMIT %s, %s', ($page - 1) * $perPage, $perPage);
    $rows = $pdo->query($sql)->fetchAll();
};
// header('Content-Type: application/json');
// print_r($rows);
// exit;

$filteredArray = array_map(function ($item) {

    return [
        '商品編號' => $item['pro_sid'],
        '細項編號' => $item['proDet_sid'],
        '商品名稱' => $item['pro_name'],
        '商品規格' => $item['proDet_name'],
        '商品價格' => "NT$" . " " . number_format($item['proDet_price']),
        '商品數量' => number_format($item['proDet_qty']),
        '商品描述' => $item['pro_describe'],
        '商品狀態' => $item['pro_status'] == 1 ? '上架中' : '下架中',
        '最後編輯' => $item['pro_update']
    ];
}, $rows);

// echo json_encode($filteredArray);



header('Content-Type: application/json');
echo json_encode([
    'perPage' => $perPage,
    'page' => $page,
    'totalRows' => $totalRows,
    'totalPages' => $totalPages,
    'rows' => $filteredArray
], JSON_UNESCAPED_UNICODE);
