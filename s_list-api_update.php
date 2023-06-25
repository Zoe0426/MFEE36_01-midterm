<?php
require './partsNOEDIT/connect-db.php';

$perPage = 25;       #顯示幾筆資料
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;           #用戶要看第幾頁

if ($page < 1) {
    header('Location: ?page=1');
    exit;
};

$t_sql = "SELECT COUNT(*) 
FROM `shop_product_detail` pd
JOIN `shop_product` p ON pd.`product_sid`=p.`product_sid`";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

$searchR = isset($_GET['search_rank']) ? intval($_GET['search_rank']) : 0;

$searchRank = '';
switch ($searchR) {
    case 0:
        $searchRank = "";
        break;
    case 1:
        $searchRank = 'ORDER BY pd.`price` DESC';
        break;
    case 2:
        $searchRank = 'ORDER BY pd.`price` ASC';
        break;
    case 3:
        $searchRank = 'ORDER BY p.`update_date` DESC';
        break;
    case 4:
        $searchRank = 'ORDER BY p.`product_sid` DESC';
        break;
}

if ($totalRows) {
    $totalPages = ceil($totalRows / $perPage);
    if ($page > $totalPages) {
        header("Location: ?page=$totalPages");
        exit;
    };
    $sql = sprintf('SELECT p.product_sid, pd.product_detail_sid, p.category_detail_sid, p.for_pet_type,p.name, pd.price, pd.qty, p.shelf_status, p.update_date, pd.for_age, pd.img FROM `shop_product_detail` pd
    JOIN `shop_product` p ON pd.`product_sid`=p.`product_sid` 
    JOIN `shop_category` c ON p.`category_sid`=c.`category_sid` and p.`category_detail_sid` =c.`category_detail_sid` WHERE p.`shelf_status` !=3 %s LIMIT %s, %s', $searchRank, ($page - 1) * $perPage, $perPage);
    $rows = $pdo->query($sql)->fetchAll();
};
// header('Content-Type: application/json');
// print_r($rows);
// exit;



$filteredArray = array_map(function ($item) {
    $catDetName = "";
    $proFor = '';
    $proForAge = "";
    switch ($item['category_detail_sid']) {
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
    switch ($item['for_pet_type']) {
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
    switch ($item['for_age']) {
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
        '商品編號' => $item['product_sid'],
        '細項編號' => $item['product_detail_sid'],
        '細項照片' => $item['img'],
        '商品類別' => $catDetName,
        '適用對象' => $proFor,
        '適用年齡' => $proForAge,
        '商品名稱' => $item['name'],
        '商品價格' => number_format($item['price']),
        '商品數量' => number_format($item['qty']),
        '商品狀態' => $item['shelf_status'] == 1 ? '上架中' : '下架中',
        '最後編輯' => $item['update_date']
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
