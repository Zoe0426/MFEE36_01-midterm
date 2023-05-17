<?php
require './partsNOEDIT/connect-db.php';
// $rows = [];
// $sql = "SELECT * FROM `mem_coupon_type`";
// $rows = $pdo->query($sql)->fetchAll();


$coupon_sid = isset($_GET['coupon_sid']) ? $_GET['coupon_sid'] : '';

$sql_cupon_detail = "SELECT * FROM `mem_coupon_type` WHERE coupon_sid =:coupon_sid";
$stmt_coupon_detail = $pdo->prepare($sql_cupon_detail);
$stmt_coupon_detail->bindValue(':coupon_sid', $coupon_sid, PDO::PARAM_STR);
$stmt_coupon_detail->execute();
$r1 = $stmt_coupon_detail->fetch(PDO::FETCH_ASSOC);



print_r($r1);

?>





<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container">
    <div class="row">
        <div class="mb-3">
            <label for="coupon_name" class="form-label">優惠券名稱</label>
            <select name="coupon_sid" id="coupon_sid" data-required="1">

                <option value="<?= $r1['coupon_sid'] ?> "><?= $r1['coupon_name'] ?></option>

            </select>
            <div class="btn btn-primary" id="search">搜尋</div>
        </div>
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

                <tr>
                    <td><a href="javascript: delete_it('<?= $r1['coupon_sid'] ?>')">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                    <td><?= $r1['coupon_sid'] ?></td>
                    <td><?= $r1['coupon_code'] ?></td>
                    <td><?= $r1['coupon_name'] ?></td>
                    <td><?= $r1['coupon_price'] ?></td>
                    <td><?= $r1['coupon_startDate'] ?></td>
                    <td><?= $r1['coupon_expDate'] ?></td>
                    <td><a href="m_coupon_type_update.php?coupon_sid=<?= $r1['coupon_sid'] ?>">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                </tr>

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
        const coupon_sid = document.querySelector("#coupon_sid");
        location.href = `m_coupon_type_read.php?coupon_sid=${coupon_sid.value}`
    })
</script>
<?php include './partsNOEDIT/html-foot.php' ?>