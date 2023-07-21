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


// Select 1-20 random shop items
$result = $mysqli->query("SELECT 
        sp.product_sid, 
        spd.product_detail_sid, 
        sp.name as rel_name, 
        spd.name as rel_seq_name, 
        spd.price as product_price
    FROM shop_product sp 
    JOIN shop_product_detail spd ON sp.product_sid = spd.product_sid 
    ORDER BY RAND() LIMIT " . rand(1, 20));

if (!$result) {
    echo "Query Failed: " . $mysqli->error;
    exit();
}

$shop_items = $result->fetch_all(MYSQLI_ASSOC);
//var_dump($shop_items);


//Select 1-10 random events
$result = $mysqli->query("SELECT 
        ai.activity_sid, 
        ag.activity_group_sid,
        ai.name as rel_name,
        ag.date as rel_seq_name,
        ag.price_adult as ad_price,
        ag.price_kid as kid_price
    FROM activity_info ai 
    JOIN activity_group ag ON ai.activity_sid = ag.activity_sid 
    ORDER BY RAND() LIMIT " . rand(1, 10));

if (!$result) {
    echo "Query Failed: " . $mysqli->error;
    exit();
}

$events = $result->fetch_all(MYSQLI_ASSOC);
//var_dump($events);


//Insert the shop items into order_detail

foreach ($shop_items as $item) {
    $p_qty = rand(1, 5);
    $rel_sub = $item['product_price'] * $p_qty;

    $mysqli->query("INSERT INTO
    order_details(
        order_sid,rel_type, rel_sid,
        rel_seq_sid,rel_name,rel_seq_name,
        product_price,product_qty,adult_price,
        adult_qty,child_price,child_qty,
        rel_subtotal)
    VALUES
    (
        'ORD00003', 'shop', '{$item['product_sid']}',
        '{$item['product_detail_sid']}','{$item['rel_name']}', '{$item['rel_seq_name']}',
        '{$item['product_price']}',$p_qty, null,
        null,null,null, 
        $rel_sub
    )");
}

//Insert the events into the cart
foreach ($events as $event) {
     $ad_qty = rand(1, 2);
     $kid_qty = rand(0, 3);
     $sub = $event['ad_price']*$ad_qty+$event['kid_price']*$kid_qty;
    $mysqli->query("INSERT INTO 
    order_details(
        order_sid,rel_type, rel_sid,
        rel_seq_sid,rel_name,rel_seq_name,
        product_price,product_qty,adult_price,
        adult_qty,child_price,child_qty,
        rel_subtotal)
     VALUES ('ORD00003', 'activity', '{$event['activity_sid']}', 
     '{$event['activity_group_sid']}','{$event['rel_name']}','{$event['rel_seq_name']}', 
     null,null,'{$event['ad_price']}',
     $ad_qty,'{$event['kid_price']}',$kid_qty,
    $sub )");
   
}

$mysqli->close();


