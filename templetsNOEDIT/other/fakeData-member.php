<?php
require './partsNOEDIT/connect-db.php';

$lasts = ["何", "傅", "劉", "吳", "呂", "周", "唐", "孫", "宋", "張", "彭", "徐", "於", "曹", "曾", "朱", "李", "林", "梁", "楊", "沈", "王", "程", "羅", "胡", "董", "蕭", "袁", "許", "謝", "趙", "郭", "鄧", "鄭", "陳", "韓", "馬", "馮", "高", "黃"];

$firsts = ["冠廷", "冠宇", "宗翰", "家豪", "彥廷", "承翰", "柏翰", "宇軒", "家瑋", "冠霖", "雅婷", "雅筑", "怡君", "佳穎", "怡萱", "宜庭", "郁婷", "怡婷", "詩涵", "鈺婷"];

$sex = ["男", "女"];

$pets = ["狗", "貓", "狗貓", "其他"];

$levels = ["金牌", "銀牌", "銅牌"];

$engName = ['Noah', 'Oliver', 'George', 'Arthur', 'Muhammad', 'Leo', 'Harry', 'Oscar', 'Archie', 'Henry', 'Theodore', 'Freddie', 'Jack', 'Charlie', 'Theo', 'Alfie', 'Jacob', 'Thomas', 'Finley', 'Arlo', 'William', 'Lucas', 'Roman', 'Tommy', 'Isaac', 'Teddy', 'Alexander', 'Luca', 'Edward', 'James', 'Joshua', 'Albie', 'Elijah', 'Max', 'Mohammed', 'Reuben', 'Mason', 'Sebastian', 'Rory', 'Jude', 'Louie', 'Benjamin', 'Ethan', 'Adam', 'Hugo', 'Joseph', 'Reggie', 'Ronnie', 'Harrison', 'Louis', 'Ezra', 'Jaxon', 'Logan', 'Daniel', 'Zachary', 'Samuel', 'Dylan', 'Albert', 'Hudson', 'Hunter', 'Frederick', 'David', 'Rowan', 'Jesse', 'Frankie', 'Toby', 'Oakley', 'Grayson', 'Carter', 'Riley', 'Felix', 'Finn', 'Bobby', 'Blake', 'Sonny', 'Caleb', 'Gabriel', 'Michael', 'Jasper', 'Alfred', 'Otis', 'Stanley', 'Milo', 'Mohammad', 'Ralph', 'Liam', 'Chester', 'Ellis', 'Elliot', 'Brody', 'Charles', 'Kai', 'Rupert', 'Yusuf', 'Harvey', 'Ollie', 'Jackson', 'Tobias', 'Nathan', 'Myles'];


// 取得當前的最大值, 若是空表格，預設ORD0000





$sql = "INSERT INTO `MEM_member`
(`member_sid`, `member_name`, `member_email`, 
`member_password`, `member_mobile`, `member_gender`, 
`member_birth`, `member_pet`, `member_level`, 
`member_ID`, `member_profile`, `creat_time`, 
`update_time`) 
VALUES (
?, ?, ?, 
?, ?, ?,
?, ?, ?,
?, ?, ?, 
?
)";

$stmt = $pdo->prepare($sql);


for ($i = 1; $i <= 500; $i++) {
    $sql2 = "SELECT IFNULL(MAX(member_sid), 'MEM0000') FROM `MEM_member`";
    $stmt2 = $pdo->query($sql2);
    $last_mem_sid = $stmt2->fetchColumn();
    $last_mem_sid;
    if (!$last_mem_sid) { // 沒有任何訂單
        $new_mem_sid = 'MEM00001';
    } else { // 有訂單
        $new_mem_num = (int)substr($last_mem_sid, 3) + 1;
        $new_mem_sid = 'MEM' . sprintf('%05d', $new_mem_num);
    }
    $new_mem_sid;

    shuffle($lasts);
    shuffle($firsts);
    shuffle($sex);
    shuffle($pets);
    shuffle($levels);
    shuffle($engName);

    $name = $lasts[0] . $firsts[0];
    $email = 'mail' . rand(10000, 99999) . '@test.com';
    $pwd = 'pwd' . rand(10000, 99999);
    // $hashPwd = password_hash($pwd, PASSWORD_BCRYPT);
    $mobile = '0918' . rand(100000, 999999);
    $gender = $sex[0];
    $bt = rand(strtotime('1985-01-01'), strtotime('2000-01-01'));
    $birthday =  date('Y-m-d', $bt);
    $pet = $pets[0];
    $level = $levels[0];
    $memId = $engName[0] . rand(10000, 99999);

    $ct = rand(strtotime('2023-03-01 00:00:00'), strtotime('2023-03-31 00:00:00'));
    $creatTime = date('Y-m-d H:i:s', $ct);

    $ut = rand(strtotime('2023-04-01 00:00:00'), strtotime('2023-05-01 00:00:00'));
    $updateTime = date('Y-m-d H:i:s', $ut);

    $stmt->execute([
        $new_mem_sid,
        $name,
        $email,
        $pwd,
        $mobile,
        $gender,
        $birthday,
        $pet,
        $level,
        $memId,
        "img01",
        $creatTime,
        $updateTime
    ]);
}

echo json_encode([
    $stmt->rowCount(), // 影響的資料筆數
    $pdo->lastInsertId(), // 最新的新增資料的主鍵
]);


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
