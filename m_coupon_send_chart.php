<?php
require './partsNOEDIT/connect-db.php';


// 優惠券使用數量
// $sql_coupon_use1 = "SELECT COUNT(coupon_status=1) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00001'";
// $stmt_coupon_use1 = $pdo->query($sql_coupon_use1)->fetch(PDO::FETCH_NUM)[0];

// $sql_coupon_use2 = "SELECT COUNT(coupon_status=1) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00002'";
// $stmt_coupon_use2 = $pdo->query($sql_coupon_use2)->fetch(PDO::FETCH_NUM)[0];

// $sql_coupon_use3 = "SELECT COUNT(coupon_status=1) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00003'";
// $stmt_coupon_use3 = $pdo->query($sql_coupon_use3)->fetch(PDO::FETCH_NUM)[0];

// $sql_coupon_use4 = "SELECT COUNT(coupon_status=1) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00004'";
// $stmt_coupon_use4 = $pdo->query($sql_coupon_use4)->fetch(PDO::FETCH_NUM)[0];

// $sql_coupon_use5 = "SELECT COUNT(coupon_status=1) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00005'";
// $stmt_coupon_use5 = $pdo->query($sql_coupon_use5)->fetch(PDO::FETCH_NUM)[0];

// $sql_coupon_use6 = "SELECT COUNT(coupon_status=1) FROM `mem_coupon_send` WHERE coupon_sid='COUPON00006'";
// $stmt_coupon_use6 = $pdo->query($sql_coupon_use6)->fetch(PDO::FETCH_NUM)[0];


// coupon 明稱
$sql_coupon_name = "SELECT `coupon_name` FROM mem_coupon_type";
$stmt_coupon_name = $pdo->query($sql_coupon_name)->fetchAll();

$result_coupon_name = array();

foreach ($stmt_coupon_name as $row) {
    $result_coupon_name[] = $row['coupon_name'];
}
// print_r($stmt_coupon_name) . "<br>";
// print_r($result) . "<br>";

// coupon 持有數量
$sql_coupon_get = "SELECT coupon_sid, COUNT(*) AS usage_count
FROM mem_coupon_send

GROUP BY coupon_sid
";
$stmt_coupon_get = $pdo->query($sql_coupon_get)->fetchAll();
$result_coupon_get = array();

foreach ($stmt_coupon_get as $row) {
    $result_coupon_get[] = $row['usage_count'];
}

print_r($result_coupon_get) . "<br>";

// coupon 使用數量
$sql_coupon_use = "SELECT coupon_sid, COUNT(*) AS usage_count
FROM mem_coupon_send
WHERE coupon_status = 1
GROUP BY coupon_sid
";
$stmt_coupon_num = $pdo->query($sql_coupon_use)->fetchAll();
$result_coupon_num = array();

foreach ($stmt_coupon_num as $row) {
    $result_coupon_num[] = $row['usage_count'];
}

// print_r($stmt_coupon_num) . "<br>";
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
            labels: <?php echo json_encode($result_coupon_name) ?>,
            datasets: [{
                label: '',
                data: <?php echo json_encode($result_coupon_get) ?>,
                borderWidth: 1
            }]
        },
        options: {
            scales: {}
        }
    });
    // 優惠券使用數量

    const labels = '優惠券使用數量';
    const data = {
        labels: <?php echo json_encode($result_coupon_name) ?>,
        datasets: [{
            label: '',
            data: <?php echo json_encode($result_coupon_num) ?>,
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