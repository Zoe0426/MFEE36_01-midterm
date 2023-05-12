<?php
//連資料庫
require './partsNOEDIT/connect-db.php';

$sqlParent = "INSERT INTO `資料表` ( `父資料表欄位們` ) VALUES (?,?,?)"; //加一筆資料到父表的SQL語法
$stm = $pdo->prepare($sqlParent); //準備(父)
$stm->execute([
    //要送進父資料表的資料
]);


$parentSid = $pdo->lastInsertId(); //取得剛加入父表的品項編號 //echo $lastsid;


//要加入子表的SQL語法
$sqlChild = "INSERT INTO order_details (`子資料表欄位們`)VALUES (?, ?, ? );";
$stm = $pdo->prepare($sqlChild); //準備(子)

$data = [ //（從前端來的資料，依需求準備好子資料表內容）
    ['xxx' => 'aaa', 'yyy' => "001", 'zzz' => '2023-05-07'],
    ['xxx' => 'bbb', 'yyy' => "002", 'zzz' => '2023-06-07'],
];

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