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

if ($totalRows) {
    if ($page > $totalPages) {
        header("Location: ?page=$totalPages");
        exit;
    }
    $sql = sprintf("SELECT * FROM rest_info ORDER BY rest_sid DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);

    $rows = $pdo->query($sql)->fetchAll();
}

?>

<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>


<div class="container pt-4">
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
        <div class="col-2 ms-auto ">
            <button type="button" class="btn btn-primary">排序</button>
            <button type="button" class="btn btn-primary">新增餐廳</button>
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
                    <th scope="col">編輯</th>
                    <th scope="col">刪除</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r) : ?>
                    <tr>

                        <td><?= $r['rest_sid'] ?></td>
                        <td><?= $r['rest_name'] ?></td>
                        <td><?= $r['catg_sid'] ?></td>
                        <td><?= $r['rest_phone'] ?></td>
                        <td><?= $r['rest_address'] ?></td>
                        <td><?= $r['m_start'] ?></td>
                        <td><?= $r['n_end'] ?></td>
                        <td><a href="#">
                                <i class=" fa-solid fa-pen-to-square text-success"></i>
                            </a>
                        </td>
                        <td><a href="#">
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

</script>
<?php include './partsNOEDIT/html-foot.php' ?>