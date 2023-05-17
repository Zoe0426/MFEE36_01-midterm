<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['admin'])) {
    header('Location: m_adminLogin.php');
    exit;
}
