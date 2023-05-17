<?php
// require '../partsNOEDIT/connect-db.php';

// $codes = ["all100", "all200", "all300", "all400", "all500"];

// $names = ["註冊好禮", "評論好禮", "發文好禮", "生日好禮", "拼圖好禮"];

// $price = [100, 200, 300, 400, 500];

// $starts = ["2023-03-01", "2023-04-01", "2023-05-01", "2023-05-01"];

// $exp = ["2023-03-31", "2023-04-30", "2023-05-31", "2023-05-31"];




// // 取得當前的最大值, 若是空表格，預設ORD0000





// $sql = "INSERT INTO `mem_coupon_type`
// (`coupon_sid`, `coupon_code`, `coupon_name`, 
// `coupon_price`, `coupon_startDate`, `coupon_expDate`, 
// `update_time`, `creat_time`) 
// VALUES (
// ?, ?, ?, 
// ?, ?, ?,
// ?, ?
// )";

// $stmt = $pdo->prepare($sql);


// for ($i = 0; $i <= 6; $i++) {
//     $sql2 = "SELECT IFNULL(MAX(coupon_sid), 'COUPON0000') FROM `mem_coupon_type`";
//     $stmt2 = $pdo->query($sql2);
//     $last_coupon_sid = $stmt2->fetchColumn();
//     $last_coupon_sid;
//     if (!$last_coupon_sid) { // 沒有任何訂單
//         $new_coupon_sid = 'COUPON00001';
//     } else { // 有訂單
//         $new_coupon_num = (int)substr($last_coupon_sid, 3) + 1;
//         $new_coupon_sid = 'COUPON' . sprintf('%05d', $new_coupon_num);
//     }
//     $new_coupon_sid;

//     // $name = $lasts[0] . $firsts[0];
//     // $email = 'mail' . rand(10000, 99999) . '@test.com';
//     // $pwd = 'pwd' . rand(10000, 99999);
//     // // $hashPwd = password_hash($pwd, PASSWORD_BCRYPT);
//     // $mobile = '0918' . rand(100000, 999999);
//     // $gender = $sex[0];
//     // $bt = rand(strtotime('1985-01-01'), strtotime('2000-01-01'));
//     // $birthday =  date('Y-m-d', $bt);
//     // $pet = $pets[0];
//     // $level = $levels[0];
//     // $memId = $engName[0] . rand(10000, 99999);



//     $ct = rand(strtotime('2023-03-01 00:00:00'), strtotime('2023-03-31 00:00:00'));
//     $creatTime = date('Y-m-d H:i:s', $ct);

//     $ut = rand(strtotime('2023-04-01 00:00:00'), strtotime('2023-05-01 00:00:00'));
//     $updateTime = date('Y-m-d H:i:s', $ut);

//     $stmt->execute([
//         $new_coupon_sid,
//         $codes[$i],
//         $names[$i],
//         $price[$i],
//         $starts[$i],
//         $exp[$i],
//         $updateTime,
//         $creatTime
//     ]);
// }

// echo json_encode([
//     $stmt->rowCount(), // 影響的資料筆數
//     $pdo->lastInsertId(), // 最新的新增資料的主鍵
// ]);


/*
https://www.ntdtv.com/b5/2017/05/14/a1324156.html


let d = `01李 02王 03張 04劉 05陳 06楊 07趙 08黃 09周 10吳
11徐 12孫 13胡 14朱 15高 16林 17何 18郭 19馬 20羅
21梁 22宋 23鄭 24謝 25韓 26唐 27馮 28於 29董 30蕭
31程 32曹 33袁 34鄧 35許 36傅 37沈 38曾 39彭 40呂`.split('').sort().slice(119);
JSON.stringify(d);

// ---------------------
https://freshman.tw/namerank

let ar = [];
$('table').eq(0).find('tr>td:nth-of-type(2)').each(function(i, el){
    ar.push(el.innerText);
});
$('table').eq(1).find('tr>td:nth-of-type(2)').each(function(i, el){
    ar.push(el.innerText);
});
JSON.stringify(ar);

// -------------------
https://www.president.gov.tw/Page/106
let ar = [];
$('.btn.btn-default.alluser').each(function(i, el){
    ar.push(el.innerText);
});
JSON.stringify(ar);

*/
// $sql = "INSERT INTO `mem_coupon_type`
//  (`coupon_sid`, `coupon_code`, `coupon_name`, 
//  `coupon_price`, `coupon_startDate`, `coupon_expDate`, 
//  `update_time`, `creat_time`) 
//  VALUES (
// ?, ?, ?, 
//  ?, ?, ?,
//  ?, ?
//  )";




// $coupon_types = array(
//     array(
//         'code' => 'all100',
//         'name' => '註冊好禮',
//         'price' => 100,
//         'start_date' => '2023-01-01',
//         'exp_date' => '2023-01-31'
//     ),
//     array(
//         'code' => 'all200',
//         'name' => '評論好禮',
//         'price' => 200,
//         'start_date' => '2023-03-01',
//         'exp_date' => '2023-03-31'
//     ),
//     array(
//         'code' => 'all300',
//         'name' => '發文好禮',
//         'price' => 300,
//         'start_date' => '2023-06-01',
//         'exp_date' => '2023-07-31'
//     ),
//     array(
//         'code' => 'all400',
//         'name' => '滿額好禮',
//         'price' => 400,
//         'start_date' => '2023-05-01',
//         'exp_date' => '2023-08-31'
//     ),
//     array(
//         'code' => 'all500',
//         'name' => '拼圖好禮',
//         'price' => 500,
//         'start_date' => '2023-01-01',
//         'exp_date' => '2023-12-31'
//     )
// );


// $ct = rand(strtotime('2023-03-01 00:00:00'), strtotime('2023-03-31 00:00:00'));
// $creatTime = date('Y-m-d H:i:s', $ct);

// $ut = rand(strtotime('2023-04-01 00:00:00'), strtotime('2023-05-01 00:00:00'));
// $updateTime = date('Y-m-d H:i:s', $ut);
// //插入資料庫
// foreach ($coupon_types as $coupon) {
//     try {
//         $stmt = $pdo->prepare($sql);
//         $stmt->execute(array(
//             $coupon['code'],
//             $coupon['name'],
//             $coupon['price'],
//             $coupon['start_date'],
//             $coupon['exp_date'],
//             $updateTime,
//             $creatTime
//         ));
//         echo "Coupon inserted successfully: {$coupon['name']}\n";
//     } catch (PDOException $e) {
//         echo "Insert failed: " . $e->getMessage();
//     }
// }
// 連接至資料庫，請自行修改成正確的資料庫資訊


// 取得目前最新的 coupon_sid 流水編號
// $stmt = $pdo->prepare("SELECT MAX(coupon_sid) AS max_coupon_sid FROM mem_coupon_type");
// $stmt->execute();
// $result = $stmt->fetch(PDO::FETCH_ASSOC);
// $maxCouponSid = $result["max_coupon_sid"];

// // 判斷是否為第一筆資料
// if ($maxCouponSid === null) {
//     // 若是第一筆資料，直接給予 COUPON00001 作為第一筆流水編號
//     $newCouponSid = "COUPON00001";
// } else {
//     // 若不是第一筆資料，產生新的流水編號
//     $newCouponSid = "COUPON" . str_pad($maxCouponSid + 1, 5, "0", STR_PAD_LEFT);
// }

// // SQL INSERT 語句使用新的流水編號
// $sql = "INSERT INTO `mem_coupon_type`
//  (`coupon_sid`, `coupon_code`, `coupon_name`, 
//  `coupon_price`, `coupon_startDate`, `coupon_expDate`, 
//  `update_time`, `creat_time`) 
//  VALUES (
// ?, ?, ?, 
//  ?, ?, ?,
//  ?, ?
//  )";

// $coupon_types = array(
//     array(
//         'code' => 'all100',
//         'name' => '註冊好禮',
//         'price' => 100,
//         'start_date' => '2023-01-01',
//         'exp_date' => '2023-01-31'
//     ),
//     array(
//         'code' => 'all200',
//         'name' => '評論好禮',
//         'price' => 200,
//         'start_date' => '2023-03-01',
//         'exp_date' => '2023-03-31'
//     ),
//     array(
//         'code' => 'all300',
//         'name' => '發文好禮',
//         'price' => 300,
//         'start_date' => '2023-06-01',
//         'exp_date' => '2023-07-31'
//     ),
//     array(
//         'code' => 'all400',
//         'name' => '滿額好禮',
//         'price' => 400,
//         'start_date' => '2023-05-01',
//         'exp_date' => '2023-08-31'
//     ),
//     array(
//         'code' => 'all500',
//         'name' => '拼圖好禮',
//         'price' => 500,
//         'start_date' => '2023-01-01',
//         'exp_date' => '2023-12-31'
//     )
// );
// for ($i = 0; $i <= 6; $i++) {
//     $ct = rand(strtotime('2023-03-01 00:00:00'), strtotime('2023-03-31 00:00:00'));
//     $creatTime = date('Y-m-d H:i:s', $ct);


//     $ut = rand(strtotime('2023-04-01 00:00:00'), strtotime('2023-05-01 00:00:00'));
//     $updateTime = date('Y-m-d H:i:s', $ut);
// }


// // 插入資料庫
// foreach ($coupon_types as $coupon) {
//     try {
//         $stmt = $pdo->prepare($sql);
//         $stmt->execute(array(
//             $newCouponSid, // 使用新的流水編號
//             $coupon['code'],
//             $coupon['name'],
//             $coupon['price'],
//             $coupon['start_date'],
//             $coupon['exp_date'],
//             $updateTime,
//             $creatTime
//         ));
//         echo "Coupon inserted successfully: {$coupon['name']}\n";

//         // 每次成功插入資料後，更新 coupon_sid 流水編號
//         $maxCouponSid++;
//         $newCouponSid = "COUPON" . str_pad($maxCouponSid + 1, 5, "0", STR_PAD_LEFT);
//     } catch (PDOException $e) {
//         echo "Insert failed: " . $e->getMessage();
//     }
// }

// 建立資料庫連線
// $servername = "localhost";
// $username = "root";
// $password = "root";
// $dbname = "pet_db";
// $conn = new mysqli($servername, $username, $password, $dbname);
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// // 定義已知的 coupon_sid 和 member_sid 陣列
// $coupon_sids = array("COUPON00001", "COUPON00002", "COUPON00003", "COUPON00004", "COUPON00005");
// $member_sids = array();
// for ($i = 1; $i <= 500; $i++) {
//     $member_sids[] = sprintf("mem%05d", $i);
// }

// // 生成需要的假資料
// for ($i = 1; $i <= 500; $i++) {
//     // 隨機選擇一個 coupon_sid 和 member_sid
//     $coupon_sid = $coupon_sids[array_rand($coupon_sids)];
//     $member_sid = $member_sids[array_rand($member_sids)];

//     // 產生隨機的 coupon_status
//     $coupon_status = rand(0, 1);

//     // 產生隨機的 create_time 和 update_time
//     $ct = rand(strtotime('2023-03-01 00:00:00'), strtotime('2023-03-31 00:00:00'));
//     $creatTime = date('Y-m-d H:i:s', $ct);
//     $ut = rand(strtotime('2023-04-01 00:00:00'), strtotime('2023-05-01 00:00:00'));
//     $updateTime = date('Y-m-d H:i:s', $ut);

//     // 將新的資料列插入資料表中
//     $sql = "INSERT INTO mem_coupon_send (couponSend_sid, coupon_sid, member_sid, coupon_status, create_time, update_time)
//     VALUES ('$i', '$coupon_sid', '$member_sid', '$coupon_status', '$creatTime', '$updateTime')";
//     if ($conn->query($sql) === TRUE) {
//         echo "New record created successfully";
//     } else {
//         echo "Error: " . $sql . "<br>" . $conn->error;
//     }
// }

// $sql = "INSERT INTO mem_coupon_send (couponSend_sid, coupon_sid, member_sid, coupon_status, used_time, update_time, create_time)
// SELECT 
//     t.n,
//     CONCAT('COUPON', LPAD(FLOOR(RAND() * 6) + 1, 5, '0')) AS coupon_sid,
//     CONCAT('MEM', LPAD(FLOOR(RAND() * 500) + 1, 5, '0')) AS member_sid,
//     IF( RAND() < 0.5 , 0, 1 ) AS coupon_status,
//     IF( RAND() < 0.5 , DATE_ADD(NOW(), INTERVAL -RAND()*30 DAY), NULL) AS used_time,
//     DATE_ADD(NOW(), INTERVAL -RAND()*30 DAY) AS update_time,
//     DATE_ADD(NOW(), INTERVAL -RAND()*30 DAY) AS create_time
// FROM (
//     SELECT @row_number:=@row_number+1 AS n
//     FROM information_schema.tables, (SELECT @row_number:=0) AS t1
//     LIMIT 1000
// ) AS t";



// 關閉資料庫連線



$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "pet_db";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Generate fake data
$sql = "INSERT INTO mem_coupon_send (coupon_sid, member_sid, coupon_status, used_time, create_time)
SELECT 
    CONCAT('COUPON', LPAD(FLOOR(RAND() * 6) + 1, 5, '0')) AS coupon_sid,
    CONCAT('mem', LPAD(FLOOR(RAND() * 500) + 1, 5, '0')) AS member_sid,
    FLOOR(RAND() * 2) AS coupon_status,
    @used_time := CASE WHEN FLOOR(RAND() * 2) = 1 THEN DATE_ADD(@create_time, INTERVAL FLOOR(RAND() * 1417) + 24 HOUR) ELSE NULL END,
    CASE WHEN @used_time IS NOT NULL AND FLOOR(RAND() * 2) = 1 THEN ADDTIME(@create_time, SEC_TO_TIME(FLOOR(RAND() * TIME_TO_SEC(TIMEDIFF(@used_time, @create_time)))) ) ELSE NULL END AS update_time
FROM 
    (SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) t1,
    (SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) t2,
    (SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) t3,
    (SELECT @create_time := FROM_UNIXTIME(UNIX_TIMESTAMP('2020-01-01') + FLOOR(RAND() * DATEDIFF('2023-05-01', '2020-01-01')))) t4
HAVING update_time IS NOT NULL;
";


// Execute query
if ($conn->query($sql) === TRUE) {
    echo "1000 rows inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
