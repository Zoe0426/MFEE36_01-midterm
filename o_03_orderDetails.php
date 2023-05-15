<?php
require './partsNOEDIT/connect-db.php' ?>
<?php include './partsNOEDIT/html-head.php' ?>
<style>
    .ocd {
        border: 2px dashed #ffc107;
        border-collapse: collapse;
    }

    .o-d-none {
        display: none;
    }
</style>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container pt-4">
    <!-- =====訂單明細===== -->

    SELECT * FROM `ord_details` WHERE order_sid = 'ord00003';
    <table class="table table-striped ocd">
        <thead>
            <tr>
                <th scope="col">明細來源</th>
                <th scope="col">產品編號</th>
                <th scope="col">品項編號</th>
                <th scope="col">產品名稱</th>
                <th scope="col">規格/期別</th>
                <th scope="col">單價(商城)</th>
                <th scope="col">數量</th>
                <th scope="col">成人單價</th>
                <th scope="col">人數(ad)</th>
                <th scope="col">兒童單價</th>
                <th scope="col">人數(kid)</th>
                <th scope="col">小計</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>

</script>
<?php include './partsNOEDIT/html-foot.php' ?>