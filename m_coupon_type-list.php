<?php
require './partsNOEDIT/connect-db.php';
$rows = [];
$sql = "SELECT * FROM `mem_coupon_type`";
$rows = $pdo->query($sql)->fetchAll();


$coupon_sid = isset($_GET['coupon_sid']) ? $_GET['coupon_sid'] : '';

$sql_cupon_detail = "SELECT `coupon_sid`, `coupon_code`, `coupon_name`, `coupon_price`, `coupon_startDate`, `coupon_expDate` FROM `mem_coupon_type` WHERE coupon_sid=:coupon_sid";
$stmt_coupon_detail = $pdo->prepare($sql_cupon_detail);
$stmt_coupon_detail->bindValue(':coupon_sid', $coupon_sid, PDO::PARAM_STR);
$stmt_coupon_detail->execute();
$r1 = $stmt_coupon_detail->fetch(PDO::FETCH_ASSOC);


?>





<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container">
    <div class="row">
        <div class="mb-3">
            <input type="text" placeholder="請輸入關鍵字" id="keyword">
            <div class="btn btn-primary" id="search">搜尋</div>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col"><i class="fa-solid fa-trash-can"></i></th>
                    <th scope="col">優惠券編號</th>
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
                        <td><a href="javascript: delete_it('<?= $r['coupon_sid'] ?>')">
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
            location.href = 'm_coupon_type_delete.php?coupon_sid=' + sid;
        }

    }

    const toCouponAdd_php = document.querySelector("#toCouponAdd_php");
    toCouponAdd_php.addEventListener("click", function() {
        location.href = 'm_coupon_type_add.php';
    })

    const search = document.querySelector("#search");
    search.addEventListener("click", function() {
        const keyword = document.querySelector("#keyword");
        location.href = `m_coupon_type_read.php?keyword=${keyword.value}`
    })
</script>
<?php include './partsNOEDIT/html-foot.php' ?>