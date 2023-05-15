<?php

require './partsNOEDIT/connect-db.php';

$perPage = 25; # 每頁最多幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; # 用戶要看第幾頁

if ($page < 1) {
    header('Location: ?page=1');
    exit;
}



$t_sql = "SELECT COUNT(1) FROM rest_info";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0]; # 總筆數
$totalPages = ceil($totalRows / $perPage); # 總頁數
$rows = [];

$sql = "SELECT `catg_sid`, `catg_name` FROM `rest_catg`";
$items = $pdo->query($sql)->fetchAll();


if ($totalRows) {
    if ($page > $totalPages) {
        header("Location: ?page=$totalPages");
        exit;
    }
    $sql = sprintf("SELECT ri.*, rc.`catg_name` FROM `rest_info` ri JOIN `rest_catg` rc ON ri.`catg_sid` = rc.`catg_sid` ORDER BY rest_sid DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);

    $rows = $pdo->query($sql)->fetchAll();
}

?>

<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>
<style>
    .t_row {
        width: 100%;
        padding: 0 40px;
    }
</style>

<div class="t_row pt-4">
    <div class="row mb-3 p-0">
        <div class="col-3 p-0">
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="關鍵字搜尋">
        </div>
        <div class="col-2">
            <select class="form-select" name="catg">
                <option value="">餐廳類別</option>
                <?php foreach ($items as $i) : ?>
                    <option value="<?= $i['catg_sid'] ?>"><?= $i['catg_name'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-warning">搜尋</button>
        </div>

        <div class="col-3  hstack">
            <div class="dropdown pe-3">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    排序
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">熱門程度</a></li>
                    <li><a class="dropdown-item" href="#">餐廳評價</a></li>
                </ul>
            </div>
            <a class="btn btn-primary" href="r_formadd.php">新增餐廳</a>
        </div>
    </div>


    <div class="row">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">編號</th>
                    <th scope="col">餐廳名稱</th>
                    <th scope="col">餐廳類別</th>
                    <th scope="col">餐廳電話</th>
                    <th scope="col">餐廳地址</th>
                    <th scope="col">早上開始時間</th>
                    <th scope="col">晚上結束時間</th>
                    <th scope="col">人數上限</th>
                    <th scope="col">詳細資訊</th>
                    <th scope="col">編輯</th>
                    <th scope="col">刪除</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r) : ?>
                    <tr>

                        <td><?= $r['rest_sid'] ?></td>
                        <td><?= $r['rest_name'] ?></td>
                        <td><?= $r['catg_name'] ?></td>
                        <td><?= $r['rest_phone'] ?></td>
                        <td><?= $r['rest_address'] ?></td>
                        <td><?= $r['m_start'] ?></td>
                        <td><?= $r['n_end'] ?></td>
                        <td><?= $r['p_max'] ?></td>
                        <td><a href="r_formbrowse.php?rest_sid=<?= $r['rest_sid'] ?>">
                                <i class="fa-solid fa-circle-info text-primary"></i>
                            </a></td>
                        <td><a href="r_formedit.php?rest_sid=<?= $r['rest_sid'] ?>">
                                <i class=" fa-solid fa-pen-to-square text-success"></i>
                            </a>
                        </td>
                        <td><a href="javascript: delete_it(<?= $r['rest_sid'] ?>)">
                                <i class="fa-solid fa-trash-can text-danger"></i>
                            </a></td>

                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

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
</div>
<?php include './partsNOEDIT/script.php' ?>
<script>
    function delete_it(sid) {
        if (confirm(`是否要刪除編號為 ${sid} 的資料?`)) {
            location.href = 'r_delete_api.php?rest_sid=' + sid;
        }
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>