<?php

require './partsNOEDIT/connect-db.php';

$output = [
    'getMemberSuccess' => false,
    'getCouponSuccess' => false,
];
$searchBy = isset($_POST['searchBy']) ? intval($_POST['searchBy']) : 0;
//有資料再查尋
if (!empty($_POST['searchBy'])) {
    $sbmemsid = isset($_POST['sbmemsid']) ? $_POST['sbmemsid'] : null;
    $sbmobile = isset($_POST['sbmobile']) ? $_POST['sbmobile'] : null;
    $sbname = isset($_POST['sbname']) ? $_POST['sbname'] : null;

    if ($searchBy == 3) {
        $sqlmem =
            "SELECT `member_sid`, `member_name`, `member_mobile`, `member_birth` 
        FROM `mem_member` WHERE `member_sid` = ? ";
        $stm = $pdo->prepare($sqlmem);
        $stm->execute([$sbmemsid]);
        $data = $stm->fetch();

        $output['getMemberSuccess'] = true;
        $output['sid'] = $data['member_sid'];
        $output['name'] = $data['member_name'];
        $output['mobile'] = $data['member_mobile'];
        $output['birth'] = $data['member_birth'];
    }

    if ($searchBy == 2) {
        $sqlmobile =
            "SELECT `member_sid`, `member_name`, `member_mobile`, `member_birth` 
        FROM `mem_member` WHERE `member_mobile` = ? ";
        $stm = $pdo->prepare($sqlmobile);
        $stm->execute([$sbmobile]);
        $data = $stm->fetch();

        $output['getMemberSuccess'] = true;
        $output['sid'] = $data['member_sid'];
        $output['name'] = $data['member_name'];
        $output['mobile'] = $data['member_mobile'];
        $output['birth'] = $data['member_birth'];
    }

    if ($searchBy == 1) {
        $sqlname =
            "SELECT `member_sid`, `member_name`, `member_mobile`, `member_birth` 
        FROM `mem_member` WHERE `member_name` = ? ";
        $stm = $pdo->prepare($sqlname);
        $stm->execute([$sbname]);
        $data = $stm->fetch();

        $output['getMemberSuccess'] = true;
        $output['sid'] = $data['member_sid'];
        $output['name'] = $data['member_name'];
        $output['mobile'] = $data['member_mobile'];
        $output['birth'] = $data['member_birth'];
    }
}
$mem = $data['member_sid'];
//有找到Member，拿購物車,coupon資料
if ($mem) {
    //拿coupon資料
    $sqlCoupon = "SELECT
        cs.member_sid,
        cs.couponSend_sid,
        ct.coupon_code,
        ct.coupon_name,
        ct.coupon_price,
        ct.coupon_expDate
        FROM
        mem_coupon_send cs
        JOIN mem_coupon_type ct ON cs.coupon_sid = ct.coupon_sid
        WHERE
        ct.coupon_expDate > NOW()
        AND cs.coupon_status = 0
        AND cs.member_sid = ?";
    $stm2 = $pdo->prepare($sqlCoupon);
    $stm2->execute([$mem]);
    $coupons = $stm2->fetchAll();
    //此member，有資料回傳前端
    if ($coupons) {
        $output['getCouponSuccess'] = true;
        $output['coupons'] = $coupons;
    } else {
        $output['coupons'] = "noCoupons";
    }
    //拿商城資料
    $sqlShop = "SELECT
        sp.`pro_sid`,
        sp.`pro_name`,
        spd.`proDet_sid`,
        spd.`proDet_name`,
        spd.`proDet_price`,
        spd.`proDet_qty`,
        oc.`prodQty`
    FROM
        `ord_cart` oc
        JOIN `shop_pro` sp ON oc.`rel_sid` = sp.`pro_sid`
        JOIN `shop_prodet` spd ON sp.`pro_sid` = spd.`pro_sid`
        AND oc.`rel_seqNum_sid` = spd.`proDet_sid`
    WHERE
        oc.member_sid = ? AND oc.orderStatus = '001';";

    $stm3 = $pdo->prepare($sqlShop);
    $stm3->execute([$mem]);
    $shoplist = $stm3->fetchAll();
    if ($shoplist) {
        $output['getShopSuccess'] = true;
        $output['shoplist'] = $shoplist;
    } else {
        $output['shoplist'] = "noShopItems";
    }
    //拿活動資料
    $sqlAct = "SELECT
        ai.`act_sid`,
        ai.`act_name`,
        ag.`group_sid`,
        ag.`group_date`,
        ag.`price_adult`,
        ag.`price_kid`,
        ag.`ppl_max`,
        oc.`adultQty`,
        oc.`childQty`
    FROM
       `ord_cart` oc
        JOIN `act_info` ai ON oc.`rel_sid` = ai.`act_sid`
        JOIN `act_group` ag ON ai.`act_sid` = ag.`act_sid`
        AND oc.`rel_seqNum_sid` = ag.`group_sid`
    WHERE
        oc.member_sid = ? AND oc.orderStatus = '001'";

    $stm4 = $pdo->prepare($sqlAct);
    $stm4->execute([$mem]);
    $actlist = $stm4->fetchAll();
    if ($actlist) {
        $output['getShopSuccess'] = true;
        $output['actlist'] = $actlist;
    } else {
        $output['actlist'] = "noActItems";
    }
}


header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
