<?php

session_start();

if (isset($_SESSION['admin'])) {
    include './a_list_admin.php';
} else {
    include './a_list_noadmin.php';
}
