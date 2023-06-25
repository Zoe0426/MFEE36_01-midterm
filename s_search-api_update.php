<?php
require './partsNOEDIT/connect-db.php';

$perPage = 25;       #顯示幾筆資料
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;           #用戶要看第幾頁
if ($page < 1) {
    header('Location: ?page=1');
    exit;
};

$searchC = isset($_POST['search_sid']) ? intval($_POST['search_sid']) : 0;
$searchW = "";
$searchR = isset($_POST['search_rank']) ? intval($_POST['search_rank']) : 0;
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

$sql_searchC = "";
$searchFileds = "p.product_sid, pd.product_detail_sid, p.category_detail_sid, p.for_pet_type,p.name, pd.price, pd.qty, p.shelf_status, p.update_date, pd.for_age";

switch ($searchC) {
    case 1:
        $searchW = $_POST['search_word'];
        $sql_searchC = "SELECT COUNT(*) 
        FROM `shop_product_detail` pd
        JOIN `shop_product` p ON pd.`product_sid`=p.`product_sid` WHERE p.`name` LIKE '%$searchW%'";
        $sql_search = "SELECT $searchFileds FROM `shop_product_detail` pd
        JOIN `shop_product` p ON pd.`product_sid`=p.`product_sid` 
        JOIN `shop_category` c ON p.`category_sid`=c.`category_sid` and p.`category_detail_sid` =c.`category_detail_sid` WHERE p.`shelf_status` !=3 AND p.`name`LIKE '%{$searchW}%' $searchRank";
        break;
    case 2:
        $searchW = $_POST['search_word'];
        $sql_searchC = "SELECT COUNT(*) 
        FROM `shop_product_detail` pd
        JOIN `shop_product` p ON pd.`product_sid`=p.`product_sid` WHERE p.`category_detail_sid`='{$searchW}'";
        $sql_search = "SELECT $searchFileds FROM `shop_product_detail` pd
        JOIN `shop_product` p ON pd.`product_sid`=p.`product_sid` 
        JOIN `shop_category` c ON p.`category_sid`=c.`category_sid` and p.`category_detail_sid` =c.`category_detail_sid` WHERE p.`shelf_status` !=3 AND p.`category_detail_sid`='{$searchW}' $searchRank";
        break;
    case 3:
        $searchW = $_POST['search_word'];
        $sql_searchC = "SELECT COUNT(*) 
        FROM `shop_product_detail` pd
        JOIN `shop_product` p ON pd.`product_sid`=p.`product_sid` WHERE p.`for_pet_type`='{$searchW}'";
        $sql_search = "SELECT $searchFileds FROM `shop_product_detail` pd
        JOIN `shop_product` p ON pd.`product_sid`=p.`product_sid` 
        JOIN `shop_category` c ON p.`category_sid`=c.`category_sid` and p.`category_detail_sid` =c.`category_detail_sid` WHERE p.`shelf_status` !=3 AND p.`for_pet_type`='{$searchW}' $searchRank";
        break;
    case 4:
        $searchW = intval($_POST['search_word']);
        $sql_searchC = "SELECT COUNT(*) 
        FROM `shop_product_detail` pd
        JOIN `shop_product` p ON pd.`product_sid`=p.`product_sid` WHERE pd.`for_age`='{$searchW}'";
        $sql_search = "SELECT $searchFileds FROM `shop_product_detail` pd
        JOIN `shop_product` p ON pd.`product_sid`=p.`product_sid` 
        JOIN `shop_category` c ON p.`category_sid`=c.`category_sid` and p.`category_detail_sid` =c.`category_detail_sid` WHERE p.`shelf_status` !=3 AND pd.`for_age`='{$searchW}' $searchRank";
        break;
    case 5:
        $searchW = intval($_POST['search_word']);
        $sql_searchC = "SELECT COUNT(*) 
            FROM `shop_product_detail` pd
            JOIN `shop_product` p ON pd.`product_sid`=p.`product_sid` WHERE p.`shelf_status`='{$searchW}'";
        $sql_search = "SELECT $searchFileds FROM `shop_product_detail` pd
            JOIN `shop_product` p ON pd.`product_sid`=p.`product_sid` 
            JOIN `shop_category` c ON p.`category_sid`=c.`category_sid` and p.`category_detail_sid` =c.`category_detail_sid` WHERE p.`shelf_status` !=3 AND p.`shelf_status`='{$searchW}' $searchRank";
        break;
};

$totalRows = $pdo->query($sql_searchC)->fetch(PDO::FETCH_NUM)[0];


if ($totalRows) {
    $totalPages = ceil($totalRows / $perPage);
    if ($page > $totalPages) {
        header("Location: ?page=$totalPages");
        exit;
    };
    $rows = $pdo->query($sql_search)->fetchAll();
} else {
    $totalPages = 0;
};
// header('Content-Type: application/json');
// print_r($rows);
// exit;


$rows = !empty($rows) ? $rows : [];

$filteredArray = [];
if (!empty($rows)) {
    $filteredArray = array_map(function ($item) use ($rows, $filteredArray) {
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

    header('Content-Type: application/json');
    echo json_encode([
        'perPage' => $perPage,
        'page' => $page,
        'totalRows' => $totalRows,
        'totalPages' => $totalPages,
        'rows' => $filteredArray,
        'message' => true
    ], JSON_UNESCAPED_UNICODE);
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'message' => false
    ], JSON_UNESCAPED_UNICODE);
}

// echo json_encode($filteredArray);



// header('Content-Type: application/json');
// echo json_encode([
//     'perPage' => $perPage,
//     'page' => $page,
//     'totalRows' => $totalRows,
//     'totalPages' => $totalPages,
//     'rows' => $filteredArray
// ], JSON_UNESCAPED_UNICODE);
