<?php
require './partsNOEDIT/connect-db.php';
$output = [
    'success' => false,
    'postData' => $_GET,
    'code' => 0,
    'error' => [],
];



$perPage = 25; # 每頁最多幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; # 用戶要看第幾頁

if ($page < 1) {
    header('Location: ?page=1');
    exit;
}



$t_sql = "SELECT COUNT(1) FROM rest_info";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0]; # 總筆數
$totalPages = ceil($totalRows / $perPage); # 總頁數
$rows = [];

$sql = "SELECT `catg_sid`, `catg_name` FROM `rest_catg`";
$items = $pdo->query($sql)->fetchAll();


if ($totalRows) {
    if ($page > $totalPages) {
        header("Location: ?page=$totalPages");
        exit;
    }
    $sql = sprintf("SELECT ri.*, rc.catg_name, COALESCE(rb.book_count, 0) AS book_count
    FROM rest_info ri
    JOIN rest_catg rc ON ri.catg_sid = rc.catg_sid
    LEFT JOIN (
        SELECT rest_sid, COUNT(book_sid) AS book_count
        FROM rest_book
        GROUP BY rest_sid
    ) rb ON ri.rest_sid = rb.rest_sid
    ORDER BY rest_sid  ASC  LIMIT %s, %s", ($page - 1) * $perPage, $perPage);

    $data = $pdo->query($sql)->fetchAll();
}



$output['success'] = true;
$output['getData'] = $data;
$output['code'] = 0;
$output['error'] = [];



header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
