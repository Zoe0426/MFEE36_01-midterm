<?php
# MVC

$pageName = "list";
$title = "會員清單";
require './partsNOEDIT/admin-require.php';
require "./partsNOEDIT/connect-db.php";

$perPage = 25; # 每頁最多幾筆
$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1; # 用戶要看第幾頁

if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM mem_member";
$t_rows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0]; #總筆數
$t_pages = ceil($t_rows / $perPage);
$rows = [];

if ($t_rows) {


    $sql = sprintf("SELECT * FROM mem_member ORDER BY `mem_member`.`member_sid` ASC LIMIT %s,%s", ($page - 1) * $perPage, $perPage);
    $rows = $pdo->query($sql)->fetchAll();
}



?>
<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>


<?php if ($rows) : ?>

    <div class="container">
        <!-- 頁碼 -->
        <div class="row py-3">
            <div class="col">
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
        </div>
        <div class="row">
            <div class="mb-3">
                <input type="text" placeholder="請輸入關鍵字" id="keyword">
                <div class="btn btn-primary" id="search">搜尋</div>
            </div>
            <div class="mb-3">
                <div class="btn btn-primary" id="toCouponSend_chart_php">看分析</div>
            </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">姓名</th>
                        <th scope="col">email</th>
                        <th scope="col">手機</th>
                        <th scope="col">性別</th>
                        <th scope="col">寵物</th>
                        <th scope="col">會員等級</th>
                        <th scope="col"><i class="fa-solid fa-pen-to-square"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <td><?= $r['member_sid'] ?></td>
                            <td><?= $r['member_name'] ?></td>
                            <td><?= $r['member_email'] ?></td>
                            <td><?= $r['member_mobile'] ?></td>
                            <td><?= $r['member_gender'] ?></td>
                            <td><?= $r['member_pet'] ?></td>
                            <td><?= $r['member_level'] ?></td>
                            <td><a href="m_member_update.php?member_sid=<?= $r['member_sid'] ?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>




<?php include './partsNOEDIT/script.php' ?>
<script>
    document.querySelector("li.page-item.active a").removeAttribute("href");

    function delete_it(sid) {
        if (confirm(`是否要刪除編號為 ${sid} 的資料?`)) {
            location.href = 'delete.php?sid=' + sid;
        }

    }
    const search = document.querySelector("#search");
    search.addEventListener("click", function() {
        const keyword = document.querySelector("#keyword");
        location.href = `m_member_read.php?keyword=${keyword.value}`
    })
</script>
<?php include './partsNOEDIT/html-foot.php' ?>