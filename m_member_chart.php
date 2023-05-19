<?php
require './partsNOEDIT/admin-require.php';
require './partsNOEDIT/connect-db.php';



// 會員性別
$sql_member_gender = "SELECT member_gender, COUNT(*) AS usage_count
FROM mem_member

GROUP BY member_gender
";
$stmt_member_gender = $pdo->query($sql_member_gender)->fetchAll();
$result_member_gender = array();

foreach ($stmt_member_gender as $row) {
    $result_member_gender[] = $row['usage_count'];
}


// print_r($result_coupon_get) . "<br>";



// 會員等級
$sql_member_level = "SELECT member_pet, COUNT(*) AS usage_count
FROM mem_member

GROUP BY member_pet
";
$stmt_member_level = $pdo->query($sql_member_level)->fetchAll();
$result_member_level = array();
$result_member_level2 = array();

foreach ($stmt_member_level as $row) {
    $result_member_level[] = $row['usage_count'];
    $result_member_level2[] = $row['member_pet'];
}

// print_r($result_member_level2) . "<br>";




// 會員 每月數量
// 執行 SQL 查詢並取得結果
$sql = "SELECT 
DATE_FORMAT(create_time, '%Y') AS year,
DATE_FORMAT(create_time, '%m') AS month,
COUNT(*) AS count
FROM
mem_member
GROUP BY
    year, month
ORDER BY
    year, month ASC
";
$stmt = $pdo->query($sql)->fetchAll();

$month = array();
$count = array();

foreach ($stmt as $row) {
    $month[] = $row['month'];
    $count[] = $row['count'];
}

?>

<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<style>
    .chartBox {
        width: 650px;
        margin-top: 100px;
    }
</style>
<div class="container">
    <div class="chartBox">
        <h4>會員性別</h4>
        <canvas id="myChart1"></canvas>
    </div>
    <div class="chartBox">
        <h4>會員飼養寵物類型</h4>
        <canvas id="myChart2"></canvas>
    </div>
    <div class="chartBox">
        <h4>每月會員新增數量</h4>
        <canvas id="myChart3"></canvas>
    </div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 會員性別 myChart1
    const ctx1 = document.getElementById('myChart1');

    new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: ['男', '女'],
            datasets: [{
                label: '',
                data: <?php echo json_encode($result_member_gender) ?>,
                borderWidth: 1
            }]
        },
        options: {
            scales: {}
        }
    });
    // 會員寵物 myChart2

    const labels2 = <?php echo json_encode($result_member_level2) ?>;
    const data2 = {
        labels: labels2,
        datasets: [{
            label: '',
            data: <?php echo json_encode($result_member_level) ?>,
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

    const config2 = {
        type: 'bar',
        data: data2,
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
        config2
    );

    // 會員 每月數量myChart3
    const labels3 = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const data3 = {
        labels: labels3,
        datasets: [{
            label: '',
            data: <?php echo json_encode($count) ?>,
            backgroundColor: [
                'rgb(255, 99, 132)'
            ],
            borderColor: [
                'rgb(255, 99, 132)',
            ],
        }, ]
    };

    const config3 = {
        type: 'line',
        data: data3,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
    };

    var myChart = new Chart(
        document.getElementById("myChart3"),
        config3
    );
</script>
<?php include './partsNOEDIT/html-foot.php' ?>