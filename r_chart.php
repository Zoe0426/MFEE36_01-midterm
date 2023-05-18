<?php
require './partsNOEDIT/connect-db.php';
//取得catg分別的總數（餐廳類別排名）
$sqlcatg_1 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='1'"; //取出11
$stmt_catg_1 = $pdo->query($sqlcatg_1)->fetchColumn();

$arr[] = $stmt_catg_1;


$sqlcatg_2 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='2'"; //取出1
$stmt_catg_2 = $pdo->query($sqlcatg_2)->fetchColumn();
$arr[] = $stmt_catg_2;


$sqlcatg_3 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='3'"; //取出11
$stmt_catg_3 = $pdo->query($sqlcatg_3)->fetchColumn();
$arr[] = $stmt_catg_3;

$sqlcatg_4 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='4'"; //取出11
$stmt_catg_4 = $pdo->query($sqlcatg_4)->fetchColumn();
$arr[] = $stmt_catg_4;

$sqlcatg_5 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='5'"; //取出11
$stmt_catg_5 = $pdo->query($sqlcatg_5)->fetchColumn();
$arr[] = $stmt_catg_5;

$sqlcatg_6 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='6'"; //取出11
$stmt_catg_6 = $pdo->query($sqlcatg_6)->fetchColumn();
$arr[] = $stmt_catg_6;


$sqlcatg_7 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='7'"; //取出11
$stmt_catg_7 = $pdo->query($sqlcatg_7)->fetchColumn();
$arr[] = $stmt_catg_7;


$sqlcatg_8 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='8'"; //取出11
$stmt_catg_8 = $pdo->query($sqlcatg_8)->fetchColumn();
$arr[] = $stmt_catg_8;


$sqlcatg_9 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='9'"; //取出11
$stmt_catg_9 = $pdo->query($sqlcatg_9)->fetchColumn();
$arr[] = $stmt_catg_9;


$sqlcatg_10 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='10'"; //取出11
$stmt_catg_10 = $pdo->query($sqlcatg_10)->fetchColumn();
$arr[] = $stmt_catg_10;


$sqlcatg_11 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='11'"; //取出11
$stmt_catg_11 = $pdo->query($sqlcatg_11)->fetchColumn();
$arr[] = $stmt_catg_11;


$sqlcatg_12 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='12'"; //取出11
$stmt_catg_12 = $pdo->query($sqlcatg_12)->fetchColumn();
$arr[] = $stmt_catg_12;


$sqlcatg_13 = "SELECT COUNT(catg_sid) FROM `rest_info` WHERE catg_sid='13'"; //取出11
$stmt_catg_13 = $pdo->query($sqlcatg_13)->fetchColumn();
$arr[] = $stmt_catg_13;



//餐廳預約數排名(前10)

$sqlrestb_1 = "SELECT rest_sid, COUNT(book_sid) AS book_count
FROM rest_book
GROUP BY rest_sid
ORDER BY book_count DESC
LIMIT 10;";
$stmt_restb_1 = $pdo->query($sqlrestb_1)->fetchAll();
$arry[] = $stmt_restb_1;


?>
<style>
    .bars {
        padding-left: 100px;
    }

    #myChart {
        width: 200px;
        height: auto;
    }

    .catg {
        font-weight: 800;
    }

    .book {
        font-weight: 800;
        margin-top: 100px;

    }
</style>

<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>


<div class="container pt-5">
    <div class="chartBox">
        <h5 class="catg">餐廳類別</h5>
        <canvas id="myChart"></canvas>
    </div>
    <div class="">
        <h5 class="book">餐廳預約</h5>
        <canvas id="myChart2"></canvas>
    </div>

</div>

<?php include './partsNOEDIT/script.php' ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var restaurantCounts = <?= json_encode($arr, JSON_UNESCAPED_UNICODE) ?>;
    var ctx = document.getElementById('myChart').getContext('2d');

    console.log(restaurantCounts);
    const labels = ['早午餐', '下午茶', '日式料理', '韓式料理', '咖啡和茶', '火鍋', '燒烤', '中式料理', '美式料理', '一式料理', '東南亞料理', '餐酒館/酒吧', '冰品'];
    const data = {
        labels: labels,
        datasets: [{
            label: '餐廳類別統計',
            data: restaurantCounts,
            backgroundColor: [
                'rgba(255, 99, 132, 0.4)',
                'rgba(255, 159, 64, 0.4)',
                'rgba(255, 205, 86, 0.4)',
                'rgba(75, 192, 192, 0.4)',
                'rgba(54, 162, 235, 0.4)',
                'rgba(153, 102, 255, 0.4)',
                'rgba(201, 203, 207, 0.4)',
                'rgba(255, 99, 132, 0.4)',
                'rgba(255, 159, 64, 0.4)',
                'rgba(255, 205, 86, 0.4)',
                'rgba(75, 192, 192, 0.4)',
                'rgba(54, 162, 235, 0.4)',
                'rgba(153, 102, 255, 0.4)'
            ],
            borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)',
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)'
            ],
            borderWidth: 1.5
        }]
    };

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var bookCounts = <?= json_encode($arry, JSON_UNESCAPED_UNICODE) ?>;
    var ctx1 = document.getElementById('myChart2').getContext('2d');
    const labels1 = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
    const data1 = {
        labels: labels1,
        datasets: [{
            label: '餐廳預約統計',
            data: bookCounts,
            backgroundColor: [
                'rgba(255, 99, 132, 0.4)',
                'rgba(255, 159, 64, 0.4)',
                'rgba(255, 205, 86, 0.4)',
                'rgba(75, 192, 192, 0.4)',
                'rgba(54, 162, 235, 0.4)',
                'rgba(153, 102, 255, 0.4)',
                'rgba(201, 203, 207, 0.4)',
                'rgba(255, 99, 132, 0.4)',
                'rgba(255, 159, 64, 0.4)',
                'rgba(255, 205, 86, 0.4)',
            ],
            borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)',
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)'
            ],
            borderWidth: 1.5
        }]
    };

    var myChart2 = new Chart(ctx1, {
        type: 'bar',
        data: data,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<?php include './partsNOEDIT/html-foot.php' ?>