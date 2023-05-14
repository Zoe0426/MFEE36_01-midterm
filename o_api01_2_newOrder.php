<?php
require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false, #新增資料成功或失敗的結果（MUST）
    'postData' => $_POST, # 除錯用的
    'code' => 0,
    'error' => [],

];
$shopOrders = isset($_POST['prod']) ? $_POST['prod'] : '';
$actOrders = isset($_POST['act']) ? $_POST['act'] : '';



if ($_POST['prod']) {
    $forProd = [];
    foreach ($shopOrders as $d) {
        $dParsed =  urldecode($d);
        $dDataget = json_decode($dParsed, true);
        $dProsid = $dDataget['pro_sid'];
        $dProDetsid = $dDataget['proDet_sid'];
        $dProQty = intval($dDataget['prodQty']);

        $sqls = "SELECT sp.`pro_sid`, spd.`proDet_sid`, sp.`pro_name`, spd.`proDet_name`, spd.`proDet_price`, spd.proDet_price*? AS pdSubtotal 
        FROM `shop_pro` sp 
            JOIN `shop_prodet` spd 
            ON sp.`pro_sid` = spd.`pro_sid` 
        WHERE sp.pro_sid=? AND spd.proDet_sid = ?";

        $stm = $pdo->prepare($sqls);
        $stm->execute([$dProQty, $dProsid, $dProDetsid]);
        $result = $stm->fetch();
        $forProd[] = $result;
    }
    $output['forProd'] = $forProd;
}

if ($_POST['act']) {
    $forAct = [];
    foreach ($actOrders as $a) {
        $aParsed =  urldecode($a);
        $aDataget = json_decode($aParsed, true);
        $aActsid = $aDataget['act_sid'];
        $aGroupsid = $aDataget['group_sid'];
        $aAdultQty = intval($aDataget['adultQty']);
        $aChildQty = intval($aDataget['childQty']);

        $sqla = "SELECT ai.`act_sid`, ai.`act_name`, ag.`group_sid`, 
        ag.`group_date`, ag.`price_adult`*? AS adSubtotal, ag.`price_kid`*? AS kidSubtotal
        FROM `act_info` ai 
        JOIN `act_group` ag ON ai.`act_sid` = ag.`act_sid` 
        WHERE ai.act_sid = ? AND ag.group_sid = ?";

        $stm = $pdo->prepare($sqla);
        $stm->execute([$aAdultQty, $aChildQty, $aActsid, $aGroupsid]);
        $result = $stm->fetch();
        $forAct[] = $result;
    }
    $output['forAct'] = $forAct;
}

$cCoupon = isset($_POST['coupon']) ? $_POST['coupon'] : "";

if ($_POST['coupon']) {
    $sqlc =
        "SELECT ct.coupon_price
        FROM mem_coupon_send cs
        JOIN mem_coupon_type ct ON cs.coupon_sid = ct.coupon_sid
    WHERE
        cs.coupon_sid = ?";

    $stm = $pdo->prepare($sqlc);
    $stm->execute([$cCoupon]);
    $couponPrice = $stm->fetchColumn();
    $output['couponPrice'] = $couponPrice;
}

// print_r($shopOrders);
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
