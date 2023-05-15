<?php
require "./partsNOEDIT/connect-db.php";

$perPage = 25; # 每頁最多幾筆
$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1; # 用戶要看第幾頁

if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM mem_coupon_send";
$t_rows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0]; #總筆數
$t_pages = ceil($t_rows / $perPage);
$rows = [];

if ($t_rows) {


    $sql = sprintf("SELECT * FROM mem_coupon_send ORDER BY `mem_coupon_send`.`couponSend_sid` DESC LIMIT %s,%s", ($page - 1) * $perPage, $perPage);
    $rows = $pdo->query($sql)->fetchAll();
}

?>





<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container">
    <div class="row">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item <?= 1 == $page ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=1">
                        <i class="fa-solid fa-angles-left"></i>
                    </a>
                </li>
                <li class="page-item <?= 1 == $page ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">
                        <i class="fa-solid fa-angle-left"></i>
                    </a>
                </li>
                <?php for ($i = $page - 3; $i <= $page + 3; $i++) :
                    if ($i >= 1 and $i <= $t_pages) :
                ?>
                        <li class="page-item <?= $i == $page ? "active" : "" ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                <?php endif;
                endfor; ?>
                <li class="page-item <?= $t_pages == $page ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">
                        <i class="fa-solid fa-angle-right"></i>
                    </a>
                </li>
                <li class="page-item <?= $t_pages == $page ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $t_pages ?>">
                        <i class="fa-solid fa-angles-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col"><i class="fa-solid fa-trash-can"></i></th>
                    <th scope="col">#</th>
                    <th scope="col">優惠券編號</th>
                    <th scope="col">會員編號</th>
                    <th scope="col">使用狀況</th>
                    <th scope="col">更新時間</th>
                    <th scope="col">新增時間</th>
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
                        <td><?= $r['couponSend_sid'] ?></td>
                        <td><?= $r['coupon_sid'] ?></td>
                        <td><?= $r['member_sid'] ?></td>
                        <td><?= $r['coupon_status'] ?></td>
                        <td><?= $r['update_time'] ?></td>
                        <td><?= $r['create_time'] ?></td>
                        <td><a href="m_coupon_type_update.php?coupon_sid=<?= $r['coupon_sid'] ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <button id="toCouponSend_php">發送優惠券</button>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    function delete_it(sid) {
        if (confirm(`是否要刪除編號為 ${sid} 的資料?`)) {
            location.href = 'm_coupon_type_delete.php?coupon_sid=' + sid;
        }

    }

    const toCouponSend_php = document.querySelector("#toCouponSend_php");
    toCouponSend_php.addEventListener("click", function() {
        location.href = 'm_coupon_send_info.php';
    })
</script>
<?php include './partsNOEDIT/html-foot.php' ?>