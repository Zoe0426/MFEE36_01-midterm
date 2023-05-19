<?php
// require './partsNOEDIT/admin-require.php';
require './partsNOEDIT/admin-require.php';
require "./partsNOEDIT/connect-db.php";
$perPage = 10; # 每頁最多幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; # 用戶要看第幾頁
$text = isset($_GET['text']) ? $_GET['text'] : ''; //一定要先宣告他在做搜尋啦

if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$p_sql = "SELECT COUNT(1) FROM `post_board`"; //COUNT(1)的1是true的意思，資料庫裡的資料表如果有的話就會跑
$totalRows = $pdo->query($p_sql)->fetch(PDO::FETCH_NUM)[0]; #總筆數
//1. $pdo->query($p_sql)：使用 PDO 對象 $pdo 的 query 方法執行 SQL 查詢語句 $p_sql，並返回一個 PDOStatement 對象。
//2. ->fetch(PDO::FETCH_NUM)：對 PDOStatement 對象調用 fetch 方法，以數字索引方式讀取該結果集的一行數據，並將其轉化為一個數組。因為在此處沒有指定讀取的欄位名稱，所以 PDO::FETCH_NUM 用於指示以數字索引的方式讀取數據。
//3. [0]：將讀取到的數組的第一個元素（即第一列第一行的數據）取出，賦值給 $totalRows 變量。這個數值就是符合條件的數據總數。
$totalPages = ceil($totalRows / $perPage); #總頁數
$rows = [];
if ($totalRows) { //判斷符合條件的數據總數 $totalRows 是否存在，如果存在則繼續執行，否則不執行
    if ($page > $totalPages) {
        header("Location: ?page=$totalPages"); //判斷當前頁碼 $page 是否大於總頁數 $totalPages，如果大於則進行重定向，使頁碼指向最後一頁，然後終止程式。
        exit;
    }

    if (isset($_GET['text']) && $_GET['text'] !== "") {
        $sql = "SELECT * FROM `post_board` WHERE `board_name` LIKE '%$text%' ORDER BY `board_sid` ASC";
    } else {
        $sql = sprintf("SELECT * FROM `post_board` ORDER BY `board_sid` ASC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    }
    $rows = $pdo->query($sql)->fetchAll();
}


?>

<?php include './partsNOEDIT/html-head.php' ?>
<style>

</style>

<?php include './partsNOEDIT/navbar.php' ?>
<div class="container">
    <div class="p_readHead">
        <div class="row p_page">
            <nav aria-label="Page navigation example">
                <ul class="pagination mt-3">
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
            <!-- <label for="search">關鍵字搜尋：</label> -->
            <div class="input-group flex-nowrap w-100">
                <span class="input-group-text" id="addon-wrapping"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="search" class="form-control col-xs-4" id="keyword" placeholder="關鍵字查詢" value="<?= isset($_GET['text']) ? $_GET['text'] : "" ?>">
                <button type="submit" class="p_searchBtn btn btn-warning" id="search">搜尋</button>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="container">
            <table class="table table-bordered table-striped mt-3" id="post-list">
                <thead>
                    <tr>
                        <th scope="col"><i class="fa-solid fa-trash-can"></i></th>
                        <th scope="col">看板編號</th>
                        <th scope="col">看板名稱</th>
                        <th scope="col"><i class="fa-solid fa-pen-to-square"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <td><a href="javascript: p_delete(<?= $r['board_sid'] ?>)">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a></td>
                            <td><?= $r['board_sid'] ?></td>
                            <td><?= $r['board_name'] ?></td>
                            <td><a href="p_list_boardAdd_update.php?board_sid=<?= $r['board_sid'] ?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>


                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    //刪除
    document.querySelector('li.page-item.active a').removeAttribute('href');

    function p_delete(board_sid) {
        if (confirm(`是否要刪除編號為 ${board_sid} 的資料？`)) {
            fetch(`p_deleteBoard_api.php?board_sid=${board_sid}`)
                .then(r => r.json())
                .then(obj => {
                    console.log(obj)
                })
                .catch(er => console.log(er))
            //location.href = 'p_delete_api.php?post_sid=' + post_sid;
        }
    }

    //關鍵字搜尋
    let keyword = document.querySelector("#keyword");
    let search = document.querySelector("#search");
    search.addEventListener('click', function() {
        let keywordVal = keyword.value;
        location.href = 'p_readPost_board.php?text=' + keywordVal;
    })
</script>
<?php include './partsNOEDIT/html-foot.php' ?>