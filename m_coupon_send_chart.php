<?php
require './partsNOEDIT/connect-db.php';
// 優惠券持有數量
$sql_coupon1 = "SELECT COUNT(coupon_sid) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00001'";
$stmt_coupon1 = $pdo->query($sql_coupon1)->fetch(PDO::FETCH_NUM)[0];

$sql_coupon2 = "SELECT COUNT(coupon_sid) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00002'";
$stmt_coupon2 = $pdo->query($sql_coupon2)->fetch(PDO::FETCH_NUM)[0];

$sql_coupon3 = "SELECT COUNT(coupon_sid) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00003'";
$stmt_coupon3 = $pdo->query($sql_coupon3)->fetch(PDO::FETCH_NUM)[0];

$sql_coupon4 = "SELECT COUNT(coupon_sid) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00004'";
$stmt_coupon4 = $pdo->query($sql_coupon4)->fetch(PDO::FETCH_NUM)[0];

$sql_coupon5 = "SELECT COUNT(coupon_sid) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00005'";
$stmt_coupon5 = $pdo->query($sql_coupon5)->fetch(PDO::FETCH_NUM)[0];

$sql_coupon6 = "SELECT COUNT(coupon_sid) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00006'";
$stmt_coupon6 = $pdo->query($sql_coupon6)->fetch(PDO::FETCH_NUM)[0];

// 優惠券使用數量
$sql_coupon_use1 = "SELECT COUNT(coupon_status=1) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00001'";
$stmt_coupon_use1 = $pdo->query($sql_coupon_use1)->fetch(PDO::FETCH_NUM)[0];

$sql_coupon_use2 = "SELECT COUNT(coupon_status=1) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00002'";
$stmt_coupon_use2 = $pdo->query($sql_coupon_use2)->fetch(PDO::FETCH_NUM)[0];

$sql_coupon_use3 = "SELECT COUNT(coupon_status=1) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00003'";
$stmt_coupon_use3 = $pdo->query($sql_coupon_use3)->fetch(PDO::FETCH_NUM)[0];

$sql_coupon_use4 = "SELECT COUNT(coupon_status=1) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00004'";
$stmt_coupon_use4 = $pdo->query($sql_coupon_use4)->fetch(PDO::FETCH_NUM)[0];

$sql_coupon_use5 = "SELECT COUNT(coupon_status=1) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00005'";
$stmt_coupon_use5 = $pdo->query($sql_coupon_use5)->fetch(PDO::FETCH_NUM)[0];

$sql_coupon_use6 = "SELECT COUNT(coupon_status=1) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00006'";
$stmt_coupon_use6 = $pdo->query($sql_coupon_use6)->fetch(PDO::FETCH_NUM)[0];



?>

<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<style>
    .chartBox {
        width: 650px;
    }
</style>
<div class="container">
    <div class="chartBox">
        <h4>優惠券持有數量</h4>
        <canvas id="myChart1"></canvas>
    </div>
    <div class="chartBox">
        <h4>優惠使用數量</h4>
        <canvas id="myChart2"></canvas>
    </div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 優惠券持有數量
    const ctx1 = document.getElementById('myChart1');

    new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: ['全站100', '全站200', '全站300', '全站400', '全站500', '全站600'],
            datasets: [{
                label: '# of Votes',
                data: [<?php echo $stmt_coupon1 ?>, <?php echo $stmt_coupon2 ?>, <?php echo $stmt_coupon3 ?>, <?php echo $stmt_coupon4 ?>, <?php echo $stmt_coupon5 ?>, <?php echo $stmt_coupon6 ?>],
                borderWidth: 1
            }]
        },
        options: {
            scales: {}
        }
    });
    // 優惠券使用數量

    const labels = ['全站100', '全站200', '全站300', '全站400', '全站500', '全站600'];
    const data = {
        labels: ['全站100', '全站200', '全站300', '全站400', '全站500', '全站600'],
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

    var myChart = new Chart(
        document.getElementById("myChart2"),
        config
    );
</script>
<?php include './partsNOEDIT/html-foot.php' ?>