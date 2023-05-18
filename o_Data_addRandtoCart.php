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
$result = $mysqli->query("SELECT member_sid FROM mem_member ORDER BY RAND() LIMIT 1");

if (!$result) {
    echo "Query Failed: " . $mysqli->error;
    exit();
}

$member = $result->fetch_assoc();

// Select 1-3 random shop items
$result = $mysqli->query("SELECT sp.pro_sid, spd.proDet_sid FROM shop_pro sp JOIN shop_prodet spd ON sp.pro_sid = spd.pro_sid ORDER BY RAND() LIMIT " . rand(1, 5));

if (!$result) {
    echo "Query Failed: " . $mysqli->error;
    exit();
}

$shop_items = $result->fetch_all(MYSQLI_ASSOC);

// Select 1-3 random events
$result = $mysqli->query("SELECT ai.act_sid, ag.group_sid FROM act_info ai JOIN act_group ag ON ai.act_sid = ag.act_sid ORDER BY RAND() LIMIT " . rand(1, 2));

if (!$result) {
    echo "Query Failed: " . $mysqli->error;
    exit();
}

$events = $result->fetch_all(MYSQLI_ASSOC);

// Insert the shop items into the cart
foreach ($shop_items as $item) {
    $mysqli->query("INSERT INTO ord_cart (member_sid, relType, rel_sid, rel_seqNum_sid, prodQty, adultQty, childQty, orderStatus) VALUES ('{$member['member_sid']}', 'prod', '{$item['pro_sid']}', '{$item['proDet_sid']}', " . rand(1, 5) . ", null, null, '001')");
}

// Insert the events into the cart
foreach ($events as $event) {
    $mysqli->query("INSERT INTO ord_cart (member_sid, relType, rel_sid, rel_seqNum_sid, prodQty, adultQty, childQty, orderStatus) VALUES ('{$member['member_sid']}', 'event', '{$event['act_sid']}', '{$event['group_sid']}', null, " . rand(1, 2) . ", " . rand(1, 2) . ", '001')");
}

$mysqli->close();
