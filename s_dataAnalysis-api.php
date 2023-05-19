<?php
require './partsNOEDIT/connect-db.php';


//看目前公司前五大最熱銷的產品
$sql_top5 = "SELECT p.`pro_sid`, p.`cat_sid`,p.`catDet_sid`, p.`pro_for`, p.`pro_name`, s.`sup_name`, s.`sup_MIW`,p.`catDet_sid`, SUM(c.`prodQty`) sales_qty,SUM(pd.`proDet_price`*c.`prodQty`) sales_amount
FROM `shop_pro` p
JOIN `shop_prodet` pd ON pd.`pro_sid`=p.`pro_sid`
JOIN `shop_sup` s ON s.`sup_sid`=p.`sup_sid`
LEFT JOIN `ord_cart` c ON c.`rel_sid`=pd.`pro_sid` AND c.`rel_seqNum_sid`=pd.`proDet_sid` GROUP BY p.`pro_sid` ORDER BY `sales_qty` DESC LIMIT 5";

$sql_top5Amount = "SELECT p.`pro_sid`, p.`cat_sid`,p.`catDet_sid`, p.`pro_for`, p.`pro_name`, s.`sup_name`, s.`sup_MIW`,p.`catDet_sid`, SUM(c.`prodQty`) sales_qty,SUM(pd.`proDet_price`*c.`prodQty`) sales_amount
FROM `shop_pro` p
JOIN `shop_prodet` pd ON pd.`pro_sid`=p.`pro_sid`
JOIN `shop_sup` s ON s.`sup_sid`=p.`sup_sid`
LEFT JOIN `ord_cart` c ON c.`rel_sid`=pd.`pro_sid` AND c.`rel_seqNum_sid`=pd.`proDet_sid` GROUP BY p.`pro_sid` ORDER BY `sales_amount` DESC LIMIT 5";




$sql_salesQM = "SELECT SUM(c.`prodQty`) AS total_sales_quantity, SUM(pd.`proDet_price`*c.`prodQty`) total_sales_amount
FROM `shop_pro` p
JOIN `shop_prodet` pd ON pd.`pro_sid`=p.`pro_sid`
JOIN `ord_cart` c ON c.`rel_sid`=pd.`pro_sid` AND c.`rel_seqNum_sid`=pd.`proDet_sid`";

$r_top5 = $pdo->query($sql_top5)->fetchAll();
$r_top5Amount = $pdo->query($sql_top5Amount)->fetchAll();
$r_salesQM = $pdo->query($sql_salesQM)->fetch();


// $my_temple = "SELECT p.`pro_sid`, p.`cat_sid`,p.`catDet_sid`, p.`pro_for`, p.`pro_name`, pd.`proDet_sid`,pd.`proDet_price`, pd.`proDet_qty`, pd.`pro_forAge`, s.`sup_name`, s.`sup_MIW`, c.`prodQty`, c.`member_sid`
// FROM `shop_pro` p
// JOIN `shop_prodet` pd ON pd.`pro_sid`=p.`pro_sid`
// JOIN `shop_sup` s ON s.`sup_sid`=p.`sup_sid`
// LEFT JOIN `ord_cart` c ON c.`rel_sid`=pd.`pro_sid` AND c.`rel_seqNum_sid`=pd.`proDet_sid`";



$filteredArray = array_map(function ($item) {
    $catDetName = "";
    $proFor = '';
    $cat = '';
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
    switch ($item['cat_sid']) {
        case "F":
            $cat = "食品";
            break;
        case "G":
            $cat = "用品";
            break;
    }
    return [
        'proSid' => $item['pro_sid'],
        'cat' => $cat,
        'catDetName' => $catDetName,
        'proFor' => $proFor,
        'proName' => $item['pro_name'],
        'saleQty' => $item['sales_qty'],
        'saleAmount' => $item['sales_amount'],
        'supplier' => $item['sup_name'],
        'MIV' => $item['sup_MIW']
    ];
}, $r_top5);



$filteredArray2 = array_map(function ($item) {
    $catDetName = "";
    $proFor = '';
    $cat = '';
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
    switch ($item['cat_sid']) {
        case "F":
            $cat = "食品";
            break;
        case "G":
            $cat = "用品";
            break;
    }
    return [
        'proSid' => $item['pro_sid'],
        'cat' => $cat,
        'catDetName' => $catDetName,
        'proFor' => $proFor,
        'proName' => $item['pro_name'],
        'saleQty' => $item['sales_qty'],
        'saleAmount' => $item['sales_amount'],
        'supplier' => $item['sup_name'],
        'MIV' => $item['sup_MIW']
    ];
}, $r_top5Amount);



//echo json_encode($filteredArray);
// header('Content-Type: application/json');
// print_r($r_top5);
// print_r($r_salesQM);
// echo json_encode($filteredArray);
// exit;


header('Content-Type: application/json');
echo json_encode([
    'top5bestSales' => $filteredArray,
    'top5salesAmount' => $filteredArray2,

    'salesQtyAmount' => $r_salesQM,
], JSON_UNESCAPED_UNICODE);
