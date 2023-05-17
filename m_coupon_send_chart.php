<?php
require './partsNOEDIT/connect-db.php';
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

echo $stmt_coupon1;
echo $stmt_coupon2;
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
        <h4>優惠種類數量</h4>
        <canvas id="myChart1"></canvas>
    </div>
    <div class="chartBox">
        <h4>優惠種類數量</h4>
        <canvas id="myChart2"></canvas>
    </div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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

    const ctx2 = document.getElementById('myChart2');

    new Chart(ctx2, {
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
</script>
<?php include './partsNOEDIT/html-foot.php' ?>