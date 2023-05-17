<?php
require './partsNOEDIT/connect-db.php';
//取得catg分別的總數（餐廳類別排名）
$sqlcatg_1 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='1'"; //取出11
$stmt_catg_1 = $pdo->query($sqlcatg_1)->fetchAll();

$sqlcatg_2 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='2'"; //取出1
$stmt_catg_2 = $pdo->query($sqlcatg_2)->fetchAll();

$sqlcatg_3 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='3'"; //取出11
$stmt_catg_3 = $pdo->query($sqlcatg_3)->fetchAll();

$sqlcatg_4 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='4'"; //取出11
$stmt_catg_4 = $pdo->query($sqlcatg_4)->fetchAll();

$sqlcatg_5 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='5'"; //取出11
$stmt_catg_5 = $pdo->query($sqlcatg_5)->fetchAll();

$sqlcatg_6 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='6'"; //取出11
$stmt_catg_6 = $pdo->query($sqlcatg_6)->fetchAll();

$sqlcatg_7 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='7'"; //取出11
$stmt_catg_7 = $pdo->query($sqlcatg_7)->fetchAll();

$sqlcatg_8 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='8'"; //取出11
$stmt_catg_8 = $pdo->query($sqlcatg_8)->fetchAll();

$sqlcatg_9 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='9'"; //取出11
$stmt_catg_9 = $pdo->query($sqlcatg_9)->fetchAll();

$sqlcatg_10 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='10'"; //取出11
$stmt_catg_10 = $pdo->query($sqlcatg_10)->fetchAll();

$sqlcatg_11 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='11'"; //取出11
$stmt_catg_11 = $pdo->query($sqlcatg_11)->fetchAll();

$sqlcatg_12 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='12'"; //取出11
$stmt_catg_12 = $pdo->query($sqlcatg_12)->fetchAll();

$sqlcatg_13 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='13'"; //取出11
$stmt_catg_13 = $pdo->query($sqlcatg_13)->fetchAll();


//餐廳預約數排名(前10)

$sqlrestb_1 = "SELECT COUNT(book_sid) FROM `rest_book` WHERE rest_sid='30'"; //取出34
$stmt_restb_1 = $pdo->query($sqlcatg_1)->fetchAll();

$sqlrestb_2 = "SELECT COUNT(book_sid) FROM `rest_book` WHERE rest_sid='24'"; //取出31
$stmt_restb_2 = $pdo->query($sqlcatg_2)->fetchAll();

$sqlrestb_3 = "SELECT COUNT(book_sid) FROM `rest_book` WHERE rest_sid='5'"; //取出29
$stmt_restb_3 = $pdo->query($sqlcatg_2)->fetchAll();

$sqlrestb_4 = "SELECT COUNT(book_sid) FROM `rest_book` WHERE rest_sid='9'"; //取出24
$stmt_restb_4 = $pdo->query($sqlcatg_2)->fetchAll();

$sqlrestb_5 = "SELECT COUNT(book_sid) FROM `rest_book` WHERE rest_sid='32'"; //取出24
$stmt_restb_5 = $pdo->query($sqlcatg_2)->fetchAll();

$sqlrestb_6 = "SELECT COUNT(book_sid) FROM `rest_book` WHERE rest_sid='11'"; //取出24
$stmt_restb_6 = $pdo->query($sqlcatg_2)->fetchAll();

$sqlrestb_7 = "SELECT COUNT(book_sid) FROM `rest_book` WHERE rest_sid='16'"; //取出24
$stmt_restb_7 = $pdo->query($sqlcatg_2)->fetchAll();

$sqlrestb_8 = "SELECT COUNT(book_sid) FROM `rest_book` WHERE rest_sid='35'"; //取出23
$stmt_restb_8 = $pdo->query($sqlcatg_2)->fetchAll();

$sqlrestb_9 = "SELECT COUNT(book_sid) FROM `rest_book` WHERE rest_sid='6'"; //取出22
$stmt_restb_9 = $pdo->query($sqlcatg_2)->fetchAll();

$sqlrestb_10 = "SELECT COUNT(book_sid) FROM `rest_book` WHERE rest_sid='26'"; //取出22
$stmt_restb_10 = $pdo->query($sqlcatg_2)->fetchAll();



?>
<style>
    .bars {
        padding-left: 100px;
    }
</style>
<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>

<div class="bars row pt-5">
    <div class="chartBox">
        <h5>餐廳類別</h5>
        <canvas id="bar1"></canvas>
    </div>
    <div class="chartBox">
        <h5>餐廳預約</h5>
        <canvas id="bar2"></canvas>
    </div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const bar1 = document.querySelector("#bar1");


    const stackedBar = new Chart(bar1, {
        type: 'bar',
        data: data,
        options: {
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            }
        }
    });








    const labels = Utils.months({
        count: 7
    });
    const data = {
        labels: labels,
        datasets: [{
            label: 'My First Dataset',
            data: [65, 59, 80, 81, 56, 55, 40],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
            ],
            borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
            ],
            borderWidth: 1
        }]
    };

    const config = {
        type: 'bar',
        data: data,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
    };
</script>
<?php include './partsNOEDIT/html-foot.php' ?>