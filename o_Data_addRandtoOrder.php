
<?php

$conn = new mysqli("localhost", "root", "root", "pet_db");

// 檢查連線是否成功
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

// 生成50筆隨機資料
// 產生隨機值

$postAddress = ['649 雲林縣二崙鄉田底30號', '268 宜蘭縣五結鄉大眾東路1840號', '737 臺南市鹽水區竹圍183號', '220 新北市板橋區大智街2670號', '950 臺東縣臺東市大孝路4230號', '351 苗栗縣頭份市蘆竹路4872號', '251 新北市淡水區大勇街1396號', '923 屏東縣萬巒鄉營區路4575號', '112 臺北市北投區立農街4832號', '710 臺南市永康區國光一街2659號', '116 臺北市文山區景後街3143號', '116 臺北市文山區恆光街3357號', '956 臺東縣關山鎮中正路4830號', '403 臺中市西區精明一街25號'];



for ($i = 1; $i <= 1000; $i++) {
    $order_sid = 'ORD' . str_pad($i, 5, '0', STR_PAD_LEFT);
    $member_sid = 'mem' . str_pad(mt_rand(1, 500), 5, '0', STR_PAD_LEFT);
    $coupon_sid = mt_rand(1, 2000);
    shuffle($postAddress);
    $postType = mt_rand(1, 2);
    $postStatus = mt_rand(0, 4);
    $treadType = mt_rand(1, 3);
    $relAmount = mt_rand(100, 1000);
    $postAmount = ($postType == 1) ? 60 : 80;
    $couponAmount = mt_rand(100, 600) * 100;
    $order_status = mt_rand(0, 1);
    $creator = (mt_rand(1, 10) <= 5) ? 'admin' . str_pad(mt_rand(1, 6), 3, '0', STR_PAD_LEFT) : $member_sid;
    $createDt = randomDatetime();
    $moder = (mt_rand(1, 10) <= 5) ? 'admin' . str_pad(mt_rand(1, 6), 3, '0', STR_PAD_LEFT) : null;
    $modDt = randomDatetimeAfter($createDt);
    // 建立INSERT語句
    $sql = "INSERT INTO `ord_order` (`order_sid`, `member_sid`, `coupon_sid`, `postAddress`, `postType`, `postStatus`, `treadType`, `relAmount`, `postAmount`, `couponAmount`, `order_status`, `creator`, `createDt`, `moder`, `modDt`)
            VALUES ('$order_sid', '$member_sid', '$coupon_sid', '$postAddress[0]', $postType, $postStatus, $treadType, $relAmount, $postAmount, $couponAmount, $order_status, '$creator', '$createDt', '$moder', '$modDt')";

    // 執行INSERT語句
    if ($conn->query($sql) === TRUE) {
        echo "第 $i 筆資料插入成功<br>";
    } else {
        echo "第 $i 筆資料插入失敗：" . $conn->error . "<br>";
    }
}

// 關閉資料庫連線
$conn->close();

// 
function randomDatetime()
{
    $start = strtotime("2020-01-01");
    $end = strtotime("now");
    $randomTimestamp = mt_rand($start, $end);
    return date("Y-m-d H:i:s", $randomTimestamp);
}

// 隨機產生指定日期之後的日期時間
function randomDatetimeAfter($date)
{
    $start = strtotime($date);
    $end = strtotime("now");
    $randomTimestamp = mt_rand($start, $end);
    return date("Y-m-d H:i:s", $randomTimestamp);
}
