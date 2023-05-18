<?php
require './partsNOEDIT/connect-db.php';
// $rows = [];
// $sql = "SELECT * FROM `mem_coupon_type`";
// $rows = $pdo->query($sql)->fetchAll();


$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$sql_keyword = "SELECT * FROM `mem_coupon_type` WHERE `coupon_sid` LIKE '%$keyword%' OR `coupon_code` LIKE '%$keyword%' OR `coupon_name` LIKE '%$keyword%' OR `coupon_price` LIKE '%$keyword%' ORDER BY `coupon_sid` ASC";

$sql_cupon_detail = "SELECT * FROM `mem_coupon_type` WHERE coupon_sid =:coupon_sid";
$stmt_coupon_detail = $pdo->prepare($sql_cupon_detail);
$stmt_coupon_detail->bindValue(':coupon_sid', $coupon_sid, PDO::PARAM_STR);
$stmt_coupon_detail->execute();
$r1 = $stmt_coupon_detail->fetch(PDO::FETCH_ASSOC);

$stmt_keyword = $pdo->query($sql_keyword)->fetch();

?>





<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container">
    <div class="row">
        <div class="mb-3">
            <div class="btn btn-primary" id="toCouponType_list_php">回種類清單</div>
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
            <?php if ($stmt_keyword) {
                echo "<tbody>

                <tr>
                    <td><a href=\"javascript: delete_it('{$stmt_keyword['coupon_sid']}')\">
                            <i class=\"fa-solid fa-trash-can\"></i>
                        </a>
                    </td>
                    <td> {$stmt_keyword['coupon_sid']}</td>
                    <td> {$stmt_keyword['coupon_code']} </td>
                    <td> {$stmt_keyword['coupon_name']}</td>
                    <td> {$stmt_keyword['coupon_price']}</td>
                    <td> {$stmt_keyword['coupon_startDate']} </td>
                    <td> {$stmt_keyword['coupon_expDate']}</td>
                    <td><a href=\"m_coupon_type_update.php?coupon_sid= {$stmt_keyword['coupon_sid']}\">
                            <i class=\"fa-solid fa-pen-to-square\"></i>
                        </a>
                    </td>
                </tr>

            </tbody>";
            } else {
                echo "沒資料";
            } ?>
            <!-- <tbody>

                <tr>
                    <td><a href="javascript: delete_it('<?= $stmt_keyword['coupon_sid'] ?>')">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                    <td><?= isset($stmt_keyword['coupon_sid']) ? 1 : '' ?></td>
                    <td><?= $stmt_keyword['coupon_code'] ?></td>
                    <td><?= $stmt_keyword['coupon_name'] ?></td>
                    <td><?= $stmt_keyword['coupon_price'] ?></td>
                    <td><?= $stmt_keyword['coupon_startDate'] ?></td>
                    <td><?= $stmt_keyword['coupon_expDate'] ?></td>
                    <td><a href="m_coupon_type_update.php?coupon_sid=<?= $stmt_keyword['coupon_sid'] ?>">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                </tr>

            </tbody> -->
        </table>
    </div>
    <button id="toCouponAdd_php">新增優惠券</button>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    function delete_it(sid) {
        if (confirm(`是否要刪除編號為 ${sid} 的資料?`)) {
            location.href = 'm_coupon_type_delete.php?coupon_sid=' + sid;
            setTimeout(() => {
                location.href = 'm_coupon_type-list.php';
            }, 1000)
        }

    }

    const toCouponAdd_php = document.querySelector("#toCouponAdd_php");
    toCouponAdd_php.addEventListener("click", function() {
        location.href = 'm_coupon_type_add.php';
    })


    const toCouponType_list_php = document.querySelector("#toCouponType_list_php");
    toCouponType_list_php.addEventListener("click", function() {
        location.href = `m_coupon_type-list.php`
    })
</script>
<?php include './partsNOEDIT/html-foot.php' ?>