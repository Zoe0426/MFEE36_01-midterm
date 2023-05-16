<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['admin'])) {
    header('Location: index_noEdit.php');
    exit;
}
