<?php
require './partsNOEDIT/connect-db.php'



?>


<?php include './partsNOEDIT/html-head.php' ?>
<style>
    /* .dataA {
        width: 80%;
    } */
</style>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container mt-5 pt-5">
    <div class="row">

        <div class="col-6">
            <h1 class="mb-4">前五大熱銷商品</h1>
            <div class="dataA">
                <canvas id="myChart"></canvas>
            </div>

        </div>
        <div class="col-6">
            <h1 class="mb-4">前五大銷售額</h1>
            <div class="dataA">
                <canvas id="myChart2"></canvas>
            </div>
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
                    top5,
                    salesQtyAmount
                } = obj;

                console.log(top5)
                console.log(salesQtyAmount)
                const totalSalesNum = salesQtyAmount.total_sales_amount
                // console.log(typeof top5[0].saleAmount)
                let otherNum = 0;
                const arrName = [];
                const arrNum = [];
                for (let t of top5) {
                    let sNum = t.slaeAmount
                    otherNum += parseInt(sNum)
                    arrNum.push(sNum)
                    arrName.push(`(${t.cat})   ${t.proName}`)

                    // arrQ.push((t.slaeAmount / salesQtyAmount.total_sales_amount))
                    // arrN.push(t.proName)
                    // sumQ += parseInt(t.slaeAmount)
                }
                arrNum.push(otherNum)
                arrName.push('其他')
                // console.log(arrQ)
                console.log(arrNum)
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

                // const databar = new chart(document.querySelector("#myChart2"), {
                //     type: 'bar',
                //     data:

                // })

            })
    }

    getData()


    const ctx = document.getElementById('myChart2');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
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
    });
</script>
<?php include './partsNOEDIT/html-foot.php' ?>