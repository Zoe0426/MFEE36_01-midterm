<?php
require "./partsNOEDIT/connect-db.php";

// $perPage = 25; # 每頁最多幾筆
// $page = isset($_GET["page"]) ? intval($_GET["page"]) : 1; # 用戶要看第幾頁

// if ($page < 1) {
//     header('Location: ?page=1');
//     exit;
// }

// $t_sql = "SELECT COUNT(1) FROM mem_coupon_send";
// $t_rows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0]; #總筆數
// $t_pages = ceil($t_rows / $perPage);
// $rows = [];

// if ($t_rows) {


//     $sql = sprintf("SELECT cs.*,ct.`coupon_name`,`coupon_price`,mb.`member_name` FROM `mem_coupon_send` AS cs JOIN `mem_coupon_type` AS ct ON `cs`.`coupon_sid`=`ct`.`coupon_sid` JOIN `mem_member` AS mb ON`cs`.`member_sid`=`mb`.`member_sid` ORDER BY cs.couponSend_sid DESC LIMIT %s,%s", ($page - 1) * $perPage, $perPage);
//     $rows = $pdo->query($sql)->fetchAll();
// }

// $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
// $sql_keyword = "SELECT cs.*,ct.`coupon_name`,`coupon_price`,mb.`member_name` 
// FROM `mem_coupon_send` AS cs 
// JOIN `mem_coupon_type` AS ct ON `cs`.`coupon_sid`=`ct`.`coupon_sid` 
// JOIN `mem_member` AS mb ON`cs`.`member_sid`=`mb`.`member_sid` 
// WHERE cs.`coupon_sid` LIKE '%$keyword%' 
// OR cs.`member_sid` LIKE '%$keyword%' 
// OR cs.`coupon_status` LIKE '%$keyword%' 
// OR ct.`coupon_name` LIKE '%$keyword%' 
// OR ct.`coupon_price` LIKE '%$keyword%' 
// OR mb.`member_name` LIKE '%$keyword%' 
// ORDER BY cs.couponSend_sid DESC";
// // echo $sql_keyword;
// $stmt_keyword = $pdo->query($sql_keyword)->fetchAll();
// print_r($stmt_keyword);


// $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
// $page = isset($_GET['page']) ? $_GET['page'] : 1;
// $perPage = 25; // 每頁顯示的數量

// $offset = ($page - 1) * $perPage;
// $sql_keyword = sprintf("SELECT cs.*, ct.coupon_name, ct.coupon_price, mb.member_name 
// FROM mem_coupon_send AS cs 
// JOIN mem_coupon_type AS ct ON cs.coupon_sid = ct.coupon_sid 
// JOIN mem_member AS mb ON cs.member_sid = mb.member_sid 
// WHERE cs.coupon_sid LIKE '%%%s%%' 
// OR cs.member_sid LIKE '%%%s%%' 
// OR cs.coupon_status LIKE '%%%s%%' 
// OR ct.coupon_name LIKE '%%%s%%' 
// OR ct.coupon_price LIKE '%%%s%%' 
// OR mb.member_name LIKE '%%%s%%' 
// ORDER BY cs.couponSend_sid DESC 
// LIMIT %d, %d", $keyword, $keyword, $keyword, $keyword, $keyword, $keyword, $offset, $perPage);

// // echo $sql_keyword;
// $stmt_keyword = $pdo->query($sql_keyword)->fetchAll();

$perPage = 25;
$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

# Get total number of rows
$countSql = "SELECT COUNT(*) FROM mem_coupon_send AS cs 
JOIN mem_coupon_type AS ct ON cs.coupon_sid=ct.coupon_sid 
JOIN mem_member AS mb ON cs.member_sid=mb.member_sid 
WHERE cs.coupon_sid LIKE '%$keyword%' 
OR cs.member_sid LIKE '%$keyword%' 
OR cs.coupon_status LIKE '%$keyword%' 
OR ct.coupon_name LIKE '%$keyword%' 
OR ct.coupon_price LIKE '%$keyword%' 
OR mb.member_name LIKE '%$keyword%'";
$t_rows = $pdo->query($countSql)->fetchColumn();

# Calculate the total number of pages
$t_pages = ceil($t_rows / $perPage);

# Fetch data for current page
$sql = "SELECT cs.*,ct.coupon_name,coupon_price,mb.member_name FROM mem_coupon_send AS cs 
JOIN mem_coupon_type AS ct ON cs.coupon_sid=ct.coupon_sid 
JOIN mem_member AS mb ON cs.member_sid=mb.member_sid 
WHERE cs.coupon_sid LIKE '%$keyword%' 
OR cs.member_sid LIKE '%$keyword%' 
OR cs.coupon_status LIKE '%$keyword%' 
OR ct.coupon_name LIKE '%$keyword%' 
OR ct.coupon_price LIKE '%$keyword%' 
OR mb.member_name LIKE '%$keyword%' 
ORDER BY cs.couponSend_sid DESC 
LIMIT " . ($page - 1) * $perPage . ", $perPage";
$rows = $pdo->query($sql)->fetchAll();

?>
<!-- SELECT cs.*,ct.`coupon_name`,`coupon_price` FROM `mem_coupon_send` cs JOIN `mem_coupon_type` ct ON `cs`.`coupon_sid`=`ct`.`coupon_sid`; 
ORDER BY `mem_coupon_send`.`couponSend_sid`

-->




<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="container">
    <div class="row">
        <div class="mb-3">
            <input type="text" placeholder="請輸入關鍵字" id="keyword">
            <div class="btn btn-primary" id="search">搜尋</div>
        </div>
        <div class="mb-3">
            <div class="btn btn-primary" id="toCouponSend_chart_php">看分析</div>
        </div>
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
    <div class="row">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col"><i class="fa-solid fa-trash-can"></i></th>
                    <th scope="col">#</th>
                    <th scope="col">優惠券編號</th>
                    <th scope="col">優惠券名稱</th>
                    <th scope="col">優惠券金額</th>
                    <th scope="col">會員編號</th>
                    <th scope="col">會員姓名</th>
                    <th scope="col">使用狀況</th>
                    <th scope="col">使用狀況</th>
                    <th scope="col">新增時間</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r) : ?>
                    <tr>
                        <td><a href="javascript: delete_it('<?= $r['couponSend_sid'] ?>')">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </td>
                        <td><?= $r['couponSend_sid'] ?></td>
                        <td><?= $r['coupon_sid'] ?></td>
                        <td><?= $r['coupon_name'] ?></td>
                        <td><?= $r['coupon_price'] ?></td>
                        <td><?= $r['member_sid'] ?></td>
                        <td><?= $r['member_name'] ?></td>
                        <td><?= $r['coupon_status'] == 0 ? '未使用' : '已使用' ?></td>
                        <td><?= $r['used_time'] ?></td>
                        <td><?= $r['create_time'] ?></td>
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
            location.href = 'm_coupon_send_delete.php?couponSend_sid=' + sid;
        }

    }

    const toCouponSend_php = document.querySelector("#toCouponSend_php");
    toCouponSend_php.addEventListener("click", function() {
        location.href = 'm_coupon_send_choose.php';
    })

    const toCouponSend_chart = document.querySelector("#toCouponSend_chart_php");
    toCouponSend_chart.addEventListener("click", function() {
        location.href = 'm_coupon_send_chart.php';
    })

    const search = document.querySelector("#search");
    search.addEventListener("click", function() {
        const keyword = document.querySelector("#keyword");
        location.href = `m_coupon_send_read.php?keyword=${keyword.value}`
    })
</script>
<?php include './partsNOEDIT/html-foot.php' ?>