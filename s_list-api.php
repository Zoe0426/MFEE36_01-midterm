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

$searchR = isset($_GET['search_rank']) ? intval($_GET['search_rank']) : 0;

$searchRank = '';
switch ($searchR) {
    case 0:
        $searchRank = "";
        break;
    case 1:
        $searchRank = 'ORDER BY pd.`proDet_price` DESC';
        break;
    case 2:
        $searchRank = 'ORDER BY pd.`proDet_price` ASC';
        break;
    case 3:
        $searchRank = 'ORDER BY p.`pro_update` DESC';
        break;
    case 4:
        $searchRank = 'ORDER BY p.`pro_sid` DESC';
        break;
}

if ($totalRows) {
    $totalPages = ceil($totalRows / $perPage);
    if ($page > $totalPages) {
        header("Location: ?page=$totalPages");
        exit;
    };
    $sql = sprintf('SELECT * FROM `shop_proDet` pd
    JOIN `shop_pro` p ON pd.`pro_sid`=p.`pro_sid` 
    JOIN `shop_cat` c ON p.`cat_sid`=c.`cat_sid` and p.`catDet_sid` =c.`catDet_sid` WHERE p.`pro_status` !=3 %s LIMIT %s, %s', $searchRank, ($page - 1) * $perPage, $perPage);
    $rows = $pdo->query($sql)->fetchAll();
};
// header('Content-Type: application/json');
// print_r($rows);
// exit;



$filteredArray = array_map(function ($item) {
    $catDetName = "";
    $proFor = '';
    $proForAge = "";
    switch ($item['catDet_sid']) {
        case "FE":
            $catDetName = "飼料";
            break;
        case "CA":
            $catDetName = "罐頭";
            break;
        case "SN":
            $catDetName = "零食";
            break;
        case "HE":
            $catDetName = "保健";
            break;
        case "DR":
            $catDetName = "服飾";
            break;
        case "EA":
            $catDetName = "餐具";
            break;
        case "OD":
            $catDetName = "戶外用品";
            break;
        case "TO":
            $catDetName = "玩具";
            break;
        case "CL":
            $catDetName = "清潔";
            break;
        case "OT":
            $catDetName = "其他";
            break;
    }
    switch ($item['pro_for']) {
        case "D":
            $proFor = "狗";
            break;
        case "C":
            $proFor = "貓";
            break;
        case "B":
            $proFor = "皆可";
            break;
    }
    switch ($item['pro_forAge']) {
        case 1:
            $proForAge = "幼年";
            break;
        case 2:
            $proForAge = "成年";
            break;
        case 3:
            $proForAge = "高齡";
            break;
        case 4:
            $proForAge = "全齡";
            break;
    }
    return [
        '商品編號' => $item['pro_sid'],
        '細項編號' => $item['proDet_sid'],
        '商品類別' => $catDetName,
        '適用對象' => $proFor,
        '適用年齡' => $proForAge,
        '商品名稱' => $item['pro_name'],
        '商品價格' => number_format($item['proDet_price']),
        '商品數量' => number_format($item['proDet_qty']),
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
