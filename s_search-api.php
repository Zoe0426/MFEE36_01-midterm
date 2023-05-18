<?php
require './partsNOEDIT/connect-db.php';

$perPage = 25;       #顯示幾筆資料
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;           #用戶要看第幾頁
if ($page < 1) {
    header('Location: ?page=1');
    exit;
};

$searchC = isset($_POST['search_sid']) ? intval($_POST['search_sid']) : "";
$searchW = "";

$sql_searchC = "";

switch ($searchC) {
    case 1:
        $searchW = $_POST['search_word'];
        $sql_searchC = "SELECT COUNT(*) 
        FROM `shop_proDet` pd
        JOIN `shop_pro` p ON pd.`pro_sid`=p.`pro_sid` WHERE p.`pro_name` LIKE '%$searchW%'";
        $sql_search = "SELECT * FROM `shop_proDet` pd
        JOIN `shop_pro` p ON pd.`pro_sid`=p.`pro_sid` 
        JOIN `shop_cat` c ON p.`cat_sid`=c.`cat_sid` and p.`catDet_sid` =c.`catDet_sid` WHERE p.`pro_status` !=3 AND p.`pro_name`LIKE '%{$searchW}%' ORDER BY p.`pro_sid` DESC";
        break;
    case 2:
        $searchW = $_POST['search_word'];
        $sql_searchC = "SELECT COUNT(*) 
        FROM `shop_proDet` pd
        JOIN `shop_pro` p ON pd.`pro_sid`=p.`pro_sid` WHERE p.`catDet_sid`='{$searchW}'";
        $sql_search = "SELECT * FROM `shop_proDet` pd
        JOIN `shop_pro` p ON pd.`pro_sid`=p.`pro_sid` 
        JOIN `shop_cat` c ON p.`cat_sid`=c.`cat_sid` and p.`catDet_sid` =c.`catDet_sid` WHERE p.`pro_status` !=3 AND p.`catDet_sid`='{$searchW}' ORDER BY p.`pro_sid` DESC ";
        break;
    case 3:
        $searchW = $_POST['search_word'];
        $sql_searchC = "SELECT COUNT(*) 
        FROM `shop_proDet` pd
        JOIN `shop_pro` p ON pd.`pro_sid`=p.`pro_sid` WHERE p.`pro_for`='{$searchW}'";
        $sql_search = "SELECT * FROM `shop_proDet` pd
        JOIN `shop_pro` p ON pd.`pro_sid`=p.`pro_sid` 
        JOIN `shop_cat` c ON p.`cat_sid`=c.`cat_sid` and p.`catDet_sid` =c.`catDet_sid` WHERE p.`pro_status` !=3 AND p.`pro_for`='{$searchW}' ORDER BY p.`pro_sid` DESC ";
        break;
    case 4:
        $searchW = intval($_POST['search_word']);
        $sql_searchC = "SELECT COUNT(*) 
        FROM `shop_proDet` pd
        JOIN `shop_pro` p ON pd.`pro_sid`=p.`pro_sid` WHERE pd.`pro_forAge`='{$searchW}'";
        $sql_search = "SELECT * FROM `shop_proDet` pd
        JOIN `shop_pro` p ON pd.`pro_sid`=p.`pro_sid` 
        JOIN `shop_cat` c ON p.`cat_sid`=c.`cat_sid` and p.`catDet_sid` =c.`catDet_sid` WHERE p.`pro_status` !=3 AND pd.`pro_forAge`='{$searchW}' ORDER BY p.`pro_sid` DESC";
        break;
    case 5:
        $searchW = intval($_POST['search_word']);
        $sql_searchC = "SELECT COUNT(*) 
            FROM `shop_proDet` pd
            JOIN `shop_pro` p ON pd.`pro_sid`=p.`pro_sid` WHERE p.`pro_status`='{$searchW}'";
        $sql_search = "SELECT * FROM `shop_proDet` pd
            JOIN `shop_pro` p ON pd.`pro_sid`=p.`pro_sid` 
            JOIN `shop_cat` c ON p.`cat_sid`=c.`cat_sid` and p.`catDet_sid` =c.`catDet_sid` WHERE p.`pro_status` !=3 AND p.`pro_status`='{$searchW}' ORDER BY p.`pro_sid` DESC";
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
