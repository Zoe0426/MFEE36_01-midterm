<?php
//連資料庫
require './partsNOEDIT/connect-db.php';

$sqlParent = "INSERT INTO `rest_info` (     
        `catg_sid`,
        `rest_name`,
        `rest_phone`,
        `rest_address`,
        `rest_info`,
        `rest_notice`,
        `rest_menu`,
        `rest_f_title`,
        `rest_f_ctnt`,
        `rest_f_img`,
        `date_start`,
        `date_end`,
        `m_start`,
        `m_end`,
        `e_start`,
        `e_end`,
        `n_start`,
        `n_end`,
        `p_max`,
        `pt_max`,
        `ml_time`,
        `weekly`,
        `created_at` ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())";
//加一筆資料到父表的SQL語法

$stmt = $pdo->prepare($sqlParent); //準備(父)

$stmt->execute([
    $_POST['catg_sid'],
    $_POST['rest_name'],
    $_POST['rest_phone'],
    $_POST['rest_address'],
    $_POST['rest_info'],
    $_POST['rest_notice'],
    $_POST['rest_menu'],
    $_POST['rest_f_title'],
    $_POST['rest_f_title'],
    $_POST['rest_f_ctnt'],
    $_POST['rest_f_img'],
    $_POST['date_start'],
    $_POST['date_end'],
    $_POST['m_start'],
    $_POST['m_end'],
    $_POST['e_start'],
    $_POST['e_end'],
    $_POST['n_start'],
    $_POST['p_max'],
    $_POST['pt_max'],
    $_POST['ml_time'],
    $_POST['weekly'],
]);

//要送進父資料表的資料

$parentSid = $pdo->lastInsertId(); //取得剛加入父表的品項編號 //echo $lastsid;


//要加入子表 規則
$sqlChild = "INSERT INTO rest_c_rr (`rest_sid`, `r_sid` )VALUES ($parentSid,?);";
$stm = $pdo->prepare($sqlChild); //準備(子)


$data = [$_POST['r_sid']];

for ($i = 0; $i < $data . length; $i++) {
    $stm->execute([
        $parentSid,
        $_POST['r_sid']
    ]);
}




foreach ($data as $d) { // 使用for迴圈插入多條記錄
    $stm->execute([
        $parentSid, //父表品項編號
        $d['xxx'],
        $d['yyy'],
        $d['zzz'],
    ]);
}

//===========簡約功能說明===========
//先新增一筆訂單到父表後，拿該筆訂單的編號，再到子表加入多筆對應的訂單明細

// 父表（order）

// |order_sid   |member_sid
// |ORD0005     |MEM001

// 子表 （order-detail）

// |order_sid   |order_detail_sid | order_detail_name
// |ORD0005     |001              |貓食-大罐
// |ORD0005     |002              |貓食-中罐
// |ORD0005     |003              |貓食-小罐