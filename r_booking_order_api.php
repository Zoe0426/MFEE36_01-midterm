<?php
require './partsNOEDIT/connect-db.php';
$output = [
    'success' => false,
    'getData' => $_GET,
    'code' => 0,
    'error' => [],
];


$stmt = $pdo->query("SELECT rest_sid, COUNT(book_sid) AS book_count
FROM rest_book
GROUP BY rest_sid
ORDER BY book_count DESC;");

$data = $stmt->fetchAll();



$output['success'] = !!$stmt->rowCount();
$output['getData'] = $data;



header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
