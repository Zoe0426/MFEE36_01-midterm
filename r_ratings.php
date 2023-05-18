<?php
require './partsNOEDIT/connect-db.php';
$output = [
    'success' => false,
    'code' => 0,
    'error' => [],
];

if (!empty($_GET['rest_sid'])) {
    $selSid = intval($_GET['rest_sid']);
} else {
    $selSid = null;
}

$rest_cmtsql = "SELECT rc.rest_sid, ri.rest_name, ROUND(AVG(rc.cmt_evnt), 1) AS avg_cmt_evnt, ROUND(AVG(rc.cmt_food), 1) AS avg_cmt_food, ROUND(AVG(rc.cmt_service), 1) AS avg_cmt_service, ROUND(AVG(rc.cmt_cp), 1) AS avg_cmt_cp
FROM rest_cmt rc
JOIN rest_info ri ON rc.rest_sid = ri.rest_sid";

if (!is_null($selSid)) {
    $rest_cmtsql .= " WHERE rc.rest_sid = :restSid";
}

$rest_cmtsql .= " GROUP BY rc.rest_sid";

$statement = $pdo->prepare($rest_cmtsql);

if (!is_null($selSid)) {
    $statement->bindParam(':restSid', $selSid, PDO::PARAM_INT);
}

$statement->execute();

$data = $statement->fetch(PDO::FETCH_ASSOC);

if ($data) {
    $output['success'] = true;
    $output['getData'] = $data;
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
