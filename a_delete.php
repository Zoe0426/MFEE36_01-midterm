<?php
//require 'a_admin_required.php';
require './partsNOEDIT/connect-db.php';

$sid = isset($_GET['act_sid']) ? intval($_GET['act_sid']) : 0;

$sql = " DELETE FROM act_info WHERE act_sid={$sid}";

$pdo->query($sql);

$comeFrom = 'list.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $comeFrom = $_SERVER['HTTP_REFERER'];
}


header('Location: ' . $comeFrom);
