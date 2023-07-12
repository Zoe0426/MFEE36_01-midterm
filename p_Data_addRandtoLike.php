<?php
$db_host = 'localhost';
$db_name = 'pet_db';
$db_user = 'root';
$db_pass = 'root';
// Create a new mysqli object
$mysqli = new mysqli('localhost', 'root', 'root', 'pet_db');

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Select a random member
$result = $mysqli->query("SELECT member_sid FROM member_info ORDER BY RAND() LIMIT 1");

if (!$result) {
    echo "Query Failed: " . $mysqli->error;
    exit();
}

$member = $result->fetch_assoc();
var_dump($member);


// Select 1-36 random post
$result = $mysqli->query("SELECT post_sid FROM post_list_member ORDER BY RAND() LIMIT " . rand(1, 36));

if (!$result) {
    echo "Query Failed: " . $mysqli->error;
    exit();
}

$post = $result->fetch_all(MYSQLI_ASSOC);
var_dump($post);




foreach ($post as $item) {
    // 隨機產生2023年的1月到7月之間的時間戳
    $timestamp = mt_rand(mktime(0, 0, 0, 1, 1, 2023), mktime(0, 0, 0, 7, 31, 2023));
    
    // 將時間戳轉換為 MySQL datetime 格式
    $date = date('Y-m-d H:i:s', $timestamp);

    $mysqli->query("INSERT INTO `post_like`(`post_sid`, `member_sid`, `like_time`) 
    VALUES ('{$item['post_sid']}','{$member['member_sid']}','{$date}')");
}


$mysqli->close();
