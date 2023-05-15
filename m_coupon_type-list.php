<?php
require './partsNOEDIT/connect-db.php';
$rows = [];
$sql = "SELECT * FROM `mem_coupon_type`";
$rows = $pdo->query($sql)->fetchAll();

?>





<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container">
    <div class="row">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col"><i class="fa-solid fa-trash-can"></i></th>
                    <th scope="col">#</th>
                    <th scope="col">優惠券代碼</th>
                    <th scope="col">優惠券名稱</th>
                    <th scope="col">優惠券金額</th>
                    <th scope="col">開始日</th>
                    <th scope="col">結束日</th>
                    <th scope="col"><i class="fa-solid fa-pen-to-square"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r) : ?>
                    <tr>
                        <td><a href="javascript: delete_it(<?= $r['coupon_sid'] ?>)">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </td>
                        <td><?= $r['coupon_sid'] ?></td>
                        <td><?= $r['coupon_code'] ?></td>
                        <td><?= $r['coupon_name'] ?></td>
                        <td><?= $r['coupon_price'] ?></td>
                        <td><?= $r['coupon_startDate'] ?></td>
                        <td><?= $r['coupon_expDate'] ?></td>
                        <td><a href="m_coupon_type_update.php?coupon_sid=<?= $r['coupon_sid'] ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <button id="toCouponAdd_php">新增優惠券</button>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    function delete_it(sid) {
        if (confirm(`是否要刪除編號為 ${sid} 的資料?`)) {
            location.href = 'delete.php?sid=' + sid;
        }

    }

    const toCouponAdd_php = document.querySelector("#toCouponAdd_php");
    toCouponAdd_php.addEventListener("click", function() {
        location.href = 'm_coupon_type_add.php';
    })

    // const toCouponEdit_php = document.querySelectorAll(".coupon_type_edit");
    // toCouponEdit_php.addEventListener("click", function() {
    //     location.href = 'm_coupon_type_update.php';
    // })
</script>
<?php include './partsNOEDIT/html-foot.php' ?>