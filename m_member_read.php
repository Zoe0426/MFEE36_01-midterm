<?php
# MVC
require "./partsNOEDIT/connect-db.php";
$perPage = 25;
$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

# Get total number of rows
$countSql = "SELECT COUNT(*) FROM mem_member 
WHERE member_sid LIKE '%$keyword%' 
OR member_name LIKE '%$keyword%' 
OR member_email LIKE '%$keyword%' 
OR member_mobile LIKE '%$keyword%' 
OR member_gender LIKE '%$keyword%' 
OR member_pet LIKE '%$keyword%'
OR member_level LIKE '%$keyword%'
";
$t_rows = $pdo->query($countSql)->fetchColumn();

# Calculate the total number of pages
$t_pages = ceil($t_rows / $perPage);

# Fetch data for current page
$sql = "SELECT * FROM mem_member 
WHERE member_sid LIKE '%$keyword%' 
OR member_name LIKE '%$keyword%' 
OR member_email LIKE '%$keyword%' 
OR member_mobile LIKE '%$keyword%' 
OR member_gender LIKE '%$keyword%' 
OR member_pet LIKE '%$keyword%'
OR member_level LIKE '%$keyword%' 
ORDER BY member_sid ASC 
LIMIT " . ($page - 1) * $perPage . ", $perPage";
$rows = $pdo->query($sql)->fetchAll();

// print_r($rows);

?>
<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>


<?php if ($rows) : ?>

    <div class="container">
        <!-- 頁碼 -->
        <div class="row">
            <div class="col">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item <?= 1 == $page ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=1<?= isset($_GET['keyword']) ? '&keyword=' . $_GET['keyword'] : '' ?>">
                                <i class="fa-solid fa-angles-left"></i>
                            </a>
                        </li>
                        <li class="page-item <?= 1 == $page ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?><?= isset($_GET['keyword']) ? '&keyword=' . $_GET['keyword'] : '' ?>">
                                <i class="fa-solid fa-angle-left"></i>
                            </a>
                        </li>
                        <?php for ($i = $page - 3; $i <= $page + 3; $i++) :
                            if ($i >= 1 and $i <= $t_pages) :
                        ?>
                                <li class="page-item <?= $i == $page ? "active" : "" ?>">
                                    <a class="page-link" href="?page=<?= $i ?><?= isset($_GET['keyword']) ? '&keyword=' . $_GET['keyword'] : '' ?>"><?= $i ?></a>
                                </li>
                        <?php endif;
                        endfor; ?>
                        <li class="page-item <?= $t_pages == $page ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?><?= isset($_GET['keyword']) ? '&keyword=' . $_GET['keyword'] : '' ?>">
                                <i class="fa-solid fa-angle-right"></i>
                            </a>
                        </li>
                        <li class="page-item <?= $t_pages == $page ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $t_pages ?><?= isset($_GET['keyword']) ? '&keyword=' . $_GET['keyword'] : '' ?>">
                                <i class="fa-solid fa-angles-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="mb-3">
                <div class="btn btn-primary" id="toCouponSend_chart_php">看分析</div>
            </div>
            <div class="mb-3">
                <div class="btn btn-primary" id="toMember_list_php">回會員資料</div>
            </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col"><i class="fa-solid fa-trash-can"></i></th>
                        <th scope="col">#</th>
                        <th scope="col">姓名</th>
                        <th scope="col">email</th>
                        <th scope="col">手機</th>
                        <th scope="col">性別</th>
                        <th scope="col">寵物</th>
                        <th scope="col">會員等級</th>
                        <th scope="col"><i class="fa-solid fa-eye"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <td><a href="javascript: delete_it(<?= $r['member_sid'] ?>)">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                            <td><?= $r['member_sid'] ?></td>
                            <td><?= $r['member_name'] ?></td>
                            <td><?= $r['member_email'] ?></td>
                            <td><?= $r['member_mobile'] ?></td>
                            <td><?= $r['member_gender'] ?></td>
                            <td><?= $r['member_pet'] ?></td>
                            <td><?= $r['member_level'] ?></td>
                            <td><a href="edit.php?sid=<?= $r['member_sid'] ?>">
                                    <i class="fa-solid fa-eye"></i>
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

    const toMember_list_php = document.querySelector("#toMember_list_php");
    toMember_list_php.addEventListener("click", function() {
        location.href = `m_member-list.php`
    })
</script>
<?php include './partsNOEDIT/html-foot.php' ?>