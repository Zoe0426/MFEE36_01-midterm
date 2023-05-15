<?php
require './partsNOEDIT/connect-db.php';

$perPage = 15; # 每頁最多幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; # 用戶要看第幾頁

if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM act_info";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0]; # 總筆數
$totalPages = ceil($totalRows / $perPage); # 總頁數
$rows = [];

if ($totalRows) {
    if ($page > $totalPages) {
        header("Location: ?page=$totalPages");
        exit;
    }


    $sql = sprintf("SELECT ai.`act_sid`,`act_name`,`act_content`,ag.`group_date`,`group_time`,`ppl_max`,`act_post_date` FROM `act_info` ai JOIN `act_group` ag ON ai.`act_sid`=ag.`act_sid`ORDER BY `act_sid` DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);



    $rows = $pdo->query($sql)->fetchAll();
}



?>
<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>



<div class="container m-5">
    <div class="row col-2">
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
                    <th scope="col">活動名稱</th>
                    <th scope="col">活動內容</th>
                    <th scope="col">日期</th>
                    <th scope="col">時段</th>
                    <th scope="col">人數上限</th>
                    <th scope="col">上架時間</th>
                    <th scope="col"><i class="fa-solid fa-pen-to-square"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r) : ?>
                    <tr>
                        <td><a href="javascript: delete_it(<?= $r['act_sid'] ?>)">
                                <i class="fa-solid fa-trash-can"></i>
                            </a></td>
                        <td><?= $r['act_sid'] ?></td>
                        <td><?= $r['act_name'] ?></td>
                        <td><?= $r['act_content'] ?></td>
                        <td><?= $r['group_date'] ?></td>
                        <td><?= $r['group_time'] ?></td>
                        <td><?= $r['ppl_max'] ?></td>
                        <td><?= $r['act_post_date'] ?></td>
                        <td><a href="a_edit.php?act_sid=<?= $r['act_sid'] ?>">
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

    function delete_it(sid) {
        if (confirm(`是否要刪除編號為 ${sid} 的資料?`)) {
            fetch('a_delete.php?act_sid=' + sid)
                .then(response => response.json())
                .then(data => console.log(data))
                .catch(error => console.error(error));
            location.reload();
        }

    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>