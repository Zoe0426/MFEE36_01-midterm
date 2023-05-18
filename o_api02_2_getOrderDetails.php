<?php

require './partsNOEDIT/connect-db.php';

$output = [
    'success' => false,
    'order_sid' => '',
];
$ordSid = isset($_GET['orderSid']) ? $_GET['orderSid'] : '';

if (!empty($ordSid)) {
    $sqlDetails = "SELECT * FROM `ord_details` WHERE order_sid = ?;";
    $stm = $pdo->prepare($sqlDetails);
    $stm->execute([$ordSid]);
    $data = $stm->fetchAll();
    if ($stm->rowCount() > 0) {
        $output['success'] = true;
        $output['order_sid'] = $ordSid;
        $output['order_details'] = $data;
    } else {
        $output['error'] = 'no orderDetails';
    }
}


header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
