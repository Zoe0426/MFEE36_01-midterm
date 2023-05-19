<?php
require './partsNOEDIT/connect-db.php';
$output = [
    'success' => false,
    'postData' => $_POST,
    'code' => 0,
    'error' => [],
];


$perPage = 20; # 每頁最多幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; # 用戶要看第幾頁

if ($page < 1) {
    header('Location: ?page=1');
    exit;
}



if (isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];
    $offset = ($page - 1) * $perPage;

    $stmt = "SELECT ri.*, rc.catg_name, COALESCE(rb.book_count, 0) AS book_count
    FROM rest_info ri
    JOIN rest_catg rc ON ri.catg_sid = rc.catg_sid
    LEFT JOIN (
        SELECT rest_sid, COUNT(book_sid) AS book_count
        FROM rest_book
        GROUP BY rest_sid
    ) rb ON ri.rest_sid = rb.rest_sid
    WHERE rest_name LIKE '%$keyword%'
    LIMIT $offset, $perPage ";

    $data = $pdo->query($stmt)->fetchAll();

    // var_dump($data);

    $output['success'] = true;
    $output['postData'] = $data;
    $output['code'] = 0;
    $output['error'] = [];
} else {

    $output['success'] = false;
    $output['error'] = 'Missing keyword parameter';
}



header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
