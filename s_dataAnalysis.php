<?php
require './partsNOEDIT/admin-require.php';
require "./partsNOEDIT/connect-db.php";
?>


<?php include './partsNOEDIT/html-head.php' ?>
<style>
    .dataA {
        width: 80%;
    }
</style>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container mt-5 pt-5">
    <div>
        <h1 class="mb-4">前五大熱銷商品</h1>
        <div class="dataA">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <div class="pb-5">
        <h1 class="mb-4">前五大銷售額</h1>
        <div class="dataA">
            <canvas id="myChart2"></canvas>
        </div>
    </div>

</div>

<?php include './partsNOEDIT/script.php' ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function getData() {
        fetch('s_dataAnalysis-api.php')
            .then(r => r.json())
            .then(obj => {
                let {
                    top5bestSales,
                    salesQtyAmount,
                    top5salesAmount,
                    totalDetail
                } = obj;

                console.log(top5bestSales)
                //console.log(salesQtyAmount)
                console.log(top5salesAmount)
                const totalSalesNum = salesQtyAmount.total_sales_amount
                // console.log(typeof top5[0].saleAmount)
                let otherNum = 0;
                const arrName = [];
                const arrNum = [];
                for (let t of top5bestSales) {
                    let sNum = t.saleQty
                    otherNum += parseInt(sNum)
                    arrNum.push(sNum)
                    arrName.push(`(${t.catDetName}) ${t.proSid}--${t.proName}`)
                    console.log(t.saleQty)
                }
                arrNum.push(otherNum)
                arrName.push('其他')
                // console.log(arrQ)
                console.log(otherNum)
                console.log(arrNum)
                arr2Name = [];
                arr2Sup = [];
                arr2SalesAmount = []
                for (let a of top5salesAmount) {
                    arr2Name.push(`(${a.catDetName}) ${a.proSid}--${a.proName}`)
                    arr2SalesAmount.push(a.saleAmount)
                }

                const dataDNchart = new Chart(document.querySelector('#myChart'), {
                    type: 'doughnut',
                    data: {
                        labels: arrName,
                        datasets: [{
                            label: '銷售量',
                            data: arrNum,
                            backgroundColor: [
                                '#60ACFC',
                                '#5BC49F',
                                '#FEB64D',
                                '#FF7C7C',
                                '#32D3EB',
                                '#9287E7'
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                position: 'left',
                                align: 'start',
                                labels: {
                                    usePointStyle: true
                                }
                            },
                            tooltips: {
                                enabled: true,
                                titleFontFamily: 'Arial',
                                titleFontSize: 14,
                                bodyFontFamily: 'Arial',
                                bodyFontSize: 14
                            }
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        }
                    }
                })

                const databar = new Chart(document.querySelector('#myChart2'), {
                    type: 'bar',
                    data: {
                        labels: arr2Name,
                        datasets: [{
                            label: "",
                            data: arr2SalesAmount,
                            backgroundColor: [
                                '#60ACFC',
                                '#5BC49F',
                                '#FEB64D',
                                '#FF7C7C',
                                // '#32D3EB',
                                '#9287E7'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                })


            })
    }

    getData()


    ;
</script>
<?php include './partsNOEDIT/html-foot.php' ?>