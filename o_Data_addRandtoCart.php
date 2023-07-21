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
//var_dump($member);
// Select 1-3 random shop items
$result = $mysqli->query("SELECT sp.product_sid, spd.product_detail_sid FROM shop_product sp JOIN shop_product_detail spd ON sp.product_sid = spd.product_sid ORDER BY RAND() LIMIT " . rand(1, 5));

if (!$result) {
    echo "Query Failed: " . $mysqli->error;
    exit();
}

$shop_items = $result->fetch_all(MYSQLI_ASSOC);
//var_dump($shop_items);
//Select 1-3 random events
$result = $mysqli->query("SELECT ai.activity_sid, ag.activity_group_sid FROM activity_info ai JOIN activity_group ag ON ai.activity_sid = ag.activity_sid ORDER BY RAND() LIMIT " . rand(1, 2));

if (!$result) {
    echo "Query Failed: " . $mysqli->error;
    exit();
}

$events = $result->fetch_all(MYSQLI_ASSOC);
//var_dump($events);
//Insert the shop items into the cart for mem00300
foreach ($shop_items as $item) {
    $mysqli->query("INSERT INTO order_cart ( `member_sid`, `rel_type`, `rel_sid`, 
    `rel_seq_sid`, `product_qty`, `adult_qty`,
     `child_qty`, `order_status`) 
     VALUES ('mem00300', 'shop', '{$item['product_sid']}', 
     '{$item['product_detail_sid']}', " . rand(1, 5) . ", null, 
     null, '001')");

}

//Insert the events into the cart for mem00300
foreach ($events as $event) {
    $mysqli->query("INSERT INTO order_cart ( `member_sid`, `rel_type`, `rel_sid`, `rel_seq_sid`, `product_qty`, `adult_qty`, `child_qty`, `order_status`)
     VALUES ('mem00300', 'activity', '{$event['activity_sid']}', '{$event['activity_group_sid']}', null, " . rand(1, 2) . ", " . rand(1, 2) . ", '001')");
}

//Insert the shop items into the cart
// foreach ($shop_items as $item) {
//     $mysqli->query("INSERT INTO order_cart ( `member_sid`, `rel_type`, `rel_sid`, 
//     `rel_seq_sid`, `product_qty`, `adult_qty`,
//      `child_qty`, `order_status`) 
//      VALUES ('{$member['member_sid']}', 'shop', '{$item['product_sid']}', 
//     '{$item['product_detail_sid']}', " . rand(1, 5) . ", null, 
//     null, '001')");

// }

//Insert the events into the cart
// foreach ($events as $event) {
//     $mysqli->query("INSERT INTO order_cart ( `member_sid`, `rel_type`, `rel_sid`, `rel_seq_sid`, `product_qty`, `adult_qty`, `child_qty`, `order_status`)
//      VALUES ('{$member['member_sid']}', 'activity', '{$event['activity_sid']}', '{$event['activity_group_sid']}', null, " . rand(1, 2) . ", " . rand(1, 2) . ", '001')");
// }

$mysqli->close();
