<?php
require './partsNOEDIT/connect-db.php';

$perPage = 10; # 每頁最多幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; # 用戶要看第幾頁

if ($page < 1) {
    header('Location: ?page=1');
    exit;
}
$p_sql = "SELECT COUNT(1) FROM post_list_admin WHERE board_sid = 6";
$totalRows = $pdo->query($p_sql)->fetch(PDO::FETCH_NUM)[0]; #總筆數

$totalPages = ceil($totalRows / $perPage); #總頁數
$rows = [];


$sql = "SELECT * FROM post_list_admin WHERE board_sid = 6";
$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($totalRows) { //判斷符合條件的數據總數 $totalRows 是否存在，如果存在則繼續執行，否則不執行
    if ($page > $totalPages) {
        header("Location: ?page=$totalPages"); //判斷當前頁碼 $page 是否大於總頁數 $totalPages，如果大於則進行重定向，使頁碼指向最後一頁，然後終止程式。
        exit;
    }
    $sql = sprintf("SELECT * FROM `post_list_admin` JOIN `post_board` ON `post_list_admin`.`board_sid` = `post_board`.`board_sid` WHERE `post_board`.`board_sid` = 6 LIMIT %s, %s", ($page - 1) * $perPage, $perPage); //使用 sprintf 函數生成一條 SQL 查詢語句，按照 $perPage 條記錄每次分頁查詢數據。其中 %s 是占位符，($page - 1) * $perPage 和 $perPage 是要填入的具體值。
    $rows = $pdo->query($sql)->fetchAll(); //執行 SQL 查詢語句，使用 PDO 對象 $pdo 的 query 方法執行 SQL 查詢，然後調用 fetchAll 方法將查詢結果轉化為一個二維數組 $rows。
}

?>

<!-- 下拉列表 -->
<?php
$sql_post = "SELECT * FROM `post_board`";
$stmt = $pdo->query($sql_post);
$r_post = $stmt->fetchAll();

?>

<?php include './partsNOEDIT/html-head.php' ?>
<style>
    .p_readHead {
        display: flex;
    }

    .p_page {
        margin: 10px;
    }

    .p_search {
        margin: 10px;
        margin-top: 15px;
    }

    .p_searchBtn {
        margin: 10px;
    }
</style>

<?php include './partsNOEDIT/navbar.php' ?>
<div class="container">
    <div class="p_readHead">
        <div class="row p_page">
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
                    <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
                        if ($i >= 1 and $i <= $totalPages) :
                    ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                    <?php endif;
                    endfor; ?>
                    <li class="page-item <?= $totalPages == $page ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>">
                            <i class="fa-solid fa-angle-right"></i>
                        </a>
                    </li>
                    <li class="page-item <?= $totalPages == $page ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $totalPages ?>">
                            <i class="fa-solid fa-angles-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="p_search">
            <!-- 搜尋篩選 -->
            <form>
                <label for="board">選擇看板：</label>
                <select id="board" name="board">
                    <option value="">請選擇</option>
                    <option value="All">全部</option>
                    <option value="1">寵物醫療板</option>
                    <option value="2">寵物住宿板</option>
                    <option value="3">寵物友善景點</option>
                    <option value="4">寵物安親板</option>
                    <option value="5">狗/貓聚板</option>
                    <option value="6">曬毛孩板</option>
                    <option value="7">寵物學校</option>
                    <option value="8">寵物友善餐廳/咖啡廳</option>
                    <option value="9">寵物照護</option>
                    <option value="10">寵物殯葬</option>
                    <option value="11">幼犬/貓版</option>
                    <option value="12">老犬/貓版</option>
                    <option value="13">寵物梗圖</option>
                    <!-- 其他選項 -->
                </select>
            </form>
        </div>
        <div>
            <button type="submit" class="p_searchBtn btn btn-warning ">搜尋</button>
        </div>

    </div>
    <div class="row" id="post-list">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col"><i class="fa-solid fa-trash-can"></i></th>
                    <th scope="col">#</th>
                    <th scope="col">管理者名稱</th>
                    <th scope="col">看板名稱</th>
                    <th scope="col">文章標題</th>
                    <th scope="col">文章內容</th>
                    <th scope="col">貼文日期</th>
                    <th scope="col">更新日期</th>
                    <th scope="col"><i class="fa-solid fa-pen-to-square"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r) : ?>
                    <tr>
                        <td><a href="javascript: p_delete(<?= $r['post_sid'] ?>)">
                                <i class="fa-solid fa-trash-can"></i>
                            </a></td>
                        <td><?= $r['post_sid'] ?></td>
                        <td><?= $r['admin_name'] ?></td>
                        <td><?= $r['board_name'] ?></td>
                        <td><?= $r['post_title'] ?></td>
                        <td><?= $r['post_content'] ?></td>
                        <td><?= $r['post_date'] ?></td>
                        <td><?= $r['update_date'] ?></td>
                        <td><a href="p_list_admin_update.php?post_sid=<?= $r['post_sid'] ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>


            </tbody>
        </table>
    </div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    document.querySelector('li.page-item.active a').removeAttribute('href');

    function p_delete(post_sid) {
        if (confirm(`是否要刪除編號為 ${post_sid} 的資料？`)) {
            fetch(`p_delete_api.php?post_sid=${post_sid}`)
                .then(r => r.json())
                .then(obj => {
                    console.log(obj)
                })
                .catch(er => console.log(er))
            // location.href='p_delete_api.php?post_sid=' + post_sid;
        }
    }

    const boardSelect = document.getElementById('board');
    const postList = document.getElementById('post-list');

    boardSelect.addEventListener('change', function() {
        const boardSid = boardSelect.value;

        // // 清空文章列表
        // postList.innerHTML = '';

        // 檢查是否選擇了板塊
        if (boardSid == 1) {

            window.location.href = 'p-b1.php';
        }
        if (boardSid == 2) {

            window.location.href = 'p-b2.php';
        }
        if (boardSid == 3) {

            window.location.href = 'p-b3.php';
        }
        if (boardSid == 4) {

            window.location.href = 'p-b4.php';
        }
        if (boardSid == 5) {

            window.location.href = 'p-b5.php';
        }
        if (boardSid == 6) {

            window.location.href = 'p-b6.php';
        }
        if (boardSid == 7) {

            window.location.href = 'p-b7.php';
        }
        if (boardSid == 8) {

            window.location.href = 'p-b8.php';
        }
        if (boardSid == 9) {

            window.location.href = 'p-b9.php';
        }
        if (boardSid == 10) {

            window.location.href = 'p-b10.php';
        }
        if (boardSid == 11) {

            window.location.href = 'p-b11.php';
        }
        if (boardSid == 12) {

            window.location.href = 'p-b12.php';
        }
        if (boardSid == 13) {

            window.location.href = 'p-b13.php';
        }
        if (boardSid == 'All') {

            window.location.href = 'p_readPost_api.php';
        }
    });
</script>
<?php include './partsNOEDIT/html-foot.php' ?>