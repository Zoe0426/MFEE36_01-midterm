<?php
require './partsNOEDIT/connect-db.php';

$perPage = 10; # 每頁最多幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; # 用戶要看第幾頁

if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$p_sql = "SELECT COUNT(1) FROM `post_list_admin`";
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
    $sql = sprintf("SELECT * FROM `post_list_admin` JOIN `post_board` ON `post_list_admin`.`board_sid` = `post_board`.`board_sid` LIMIT %s, %s", ($page - 1) * $perPage, $perPage); //使用 sprintf 函數生成一條 SQL 查詢語句，按照 $perPage 條記錄每次分頁查詢數據。其中 %s 是占位符，($page - 1) * $perPage 和 $perPage 是要填入的具體值。
    $rows = $pdo->query($sql)->fetchAll(); //執行 SQL 查詢語句，使用 PDO 對象 $pdo 的 query 方法執行 SQL 查詢，然後調用 fetchAll 方法將查詢結果轉化為一個二維數組 $rows。
}

// // 將查詢到的分頁數據以 JSON 格式返回給前端網頁
// header('Content-Type: application/json'); //設置 HTTP 響應頭 Content-Type 為 application/json，表示要返回的是 JSON 格式的數據
// echo json_encode([ //使用 PHP 內置函數 json_encode 將一個關聯數組轉化為 JSON 格式的字符串，該關聯數組包含以下五個鍵值對：
//     'perPage' => $perPage, #每頁顯示幾筆
//     'page' => $page, #顯示的頁數
//     'totalRows' => $totalRows, #資料表內總資料數
//     'totalPages' => $totalPages, #資料顯示總頁數
//     'rows'=> $rows, #會顯示的當頁資料
// ], JSON_UNESCAPED_UNICODE); //表示在 JSON 編碼時不對 Unicode 字符進行轉義，保證中文字符在前端顯示時不會出現亂碼。
// // 最終通過 echo 函數將 JSON 格式的數據字符串返回給前端網頁。
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
    <div class="row">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col"><i class="fa-solid fa-trash-can"></i></th>
                    <th scope="col">#</th>
                    <th scope="col">管理者名稱</th>
                    <!-- <th scope="col">看板編號</th> -->
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
                        <!-- <td><?= $r['board_sid'] ?></td> -->
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
</script>
<?php include './partsNOEDIT/html-foot.php' ?>