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
    <form name="keyword1" onsubmit="checkForm(event)">
        <div class="d-flex mb-3 pt-4 px-0">
            <!-- <div class="pe-3">
                <select class="form-select" name="catg">
                    <option value="">全部類別</option>
                    <?php foreach ($items as $i) : ?>
                        <option value="<?= $i['catg_name'] ?>"><?= $i['catg_name'] ?></option>
                    <?php endforeach ?>
                </select>
            </div> -->


            <div class="d-flex">
                <div class="pe-2">
                    <input type="text" class="form-control" name="keyword" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="餐廳名稱搜尋">
                </div>
                <button type="submit" class="search btn btn-warning">搜尋</button>
            </div>

    </form>


    <div class="hstack ms-auto">
        <div class="dropdown pe-3">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                排序
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">熱門程度</a></li>
                <li><a class="dropdown-item" href="#">餐廳評價</a></li>
            </ul>
        </div>
        <a class="btn btn-primary" href="r_formadd.php"><i class="fa-sharp fa-solid fa-circle-plus pe-2"></i>新增餐廳</a>
    </div>
</div>


<div class="row">
    <table class="table table-bordered table-striped  table-hover">
        <thead>
            <tr>
                <th scope="col">編號</th>
                <th scope="col">餐廳名稱</th>
                <th scope="col">餐廳類別</th>
                <th scope="col">餐廳電話</th>
                <th scope="col">早上開始時間</th>
                <th scope="col">晚上結束時間</th>
                <th scope="col">用餐時間</th>
                <th scope="col">星期</th>
                <th scope="col">人數上限</th>
                <th scope="col" class="text-center">詳細資訊</th>
                <th scope="col" class="text-center">編輯</th>
                <th scope="col" class="text-center">刪除</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r) : ?>
                <tr>

                    <td><?= $r['rest_sid'] ?></td>
                    <td><?= $r['rest_name'] ?></td>
                    <td><?= $r['catg_name'] ?></td>
                    <td><?= $r['rest_phone'] ?></td>
                    <td><?= $r['m_start'] ?></td>
                    <td><?= $r['n_end'] ?></td>
                    <td><?= $r['ml_time'] ?></td>
                    <td><?= $r['weekly'] ?></td>
                    <td><?= $r['p_max'] ?></td>
                    <td class="text-center"><a href="r_formbrowse.php?rest_sid=<?= $r['rest_sid'] ?>">
                            <i class="fa-solid fa-circle-info text-primary"></i>
                        </a></td>
                    <td class="text-center"><a href="r_formedit.php?rest_sid=<?= $r['rest_sid'] ?>">
                            <i class=" fa-solid fa-pen-to-square text-success"></i>
                        </a>
                    </td>
                    <td class="text-center"><a href="javascript: delete_it(<?= $r['rest_sid'] ?>)">
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
    // 刪除提示
    function delete_it(sid) {
        if (confirm(`是否要刪除編號為 ${sid} 的資料?`)) {
            location.href = 'r_delete_api.php?rest_sid=' + sid;
        }
    }
    // 獲取下拉選單元素
    // const selectElement = document.querySelector('select[name="catg"]');
    // const tableRows = document.querySelectorAll('tbody tr');

    // selectElement.addEventListener('change', function() {
    //     const selectedValue = this.value;


    //     tableRows.forEach(function(row) {
    //         const catgName = row.querySelector('td:nth-child(3)').textContent;


    //         if (selectedValue === '' || selectedValue === catgName) {
    //             row.style.display = 'table-row';
    //         } else {
    //             row.style.display = 'none';
    //         }
    //     });
    // });


    // // 獲取輸入框元素
    // const inputElement = document.querySelector('input[name="keyword"]');
    // const tableRows1 = document.querySelectorAll('tbody tr');

    // // 原始表格行的顯示狀態陣列
    // const originalDisplay = Array.from(tableRows1).map(row => row.style.display);

    // const searchButton = document.querySelector('.search');


    // searchButton.addEventListener('click', function(event) {
    //     event.preventDefault();

    //     const keyword = inputElement.value.trim().toLowerCase();
    //     tableRows.forEach(function(row, index) {
    //         const restName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

    //         if (keyword === '' || restName.includes(keyword)) {
    //             row.style.display = originalDisplay[index];
    //         } else {
    //             row.style.display = 'none';
    //         }
    //     });
    // });
    function checkForm(event) {
        event.preventDefault();
        const fd = new FormData(document.keyword1);
        fetch('r_keyword_api.php', {
                method: 'POST',
                body: fd,
            })
            .then(r => r.json())
            .then(obj => {
                console.log(obj.postData);
                if (obj.success) {
                    const data = obj.postData;
                    const tbody = document.querySelector('tbody');
                    tbody.innerHTML = '';
                    data.forEach(function(row) {
                        const tr = document.createElement('tr');

                        // 建立各個欄位的儲存格
                        const td1 = document.createElement('td');
                        td1.textContent = row.rest_sid;
                        tr.appendChild(td1);

                        const td2 = document.createElement('td');
                        td2.textContent = row.rest_name;
                        tr.appendChild(td2);

                        const td3 = document.createElement('td');
                        td3.textContent = row.catg_name;
                        tr.appendChild(td3);

                        const td4 = document.createElement('td');
                        td4.textContent = row.rest_phone;
                        tr.appendChild(td4);

                        const td5 = document.createElement('td');
                        td5.textContent = row.m_start;
                        tr.appendChild(td5);

                        const td6 = document.createElement('td');
                        td6.textContent = row.n_end;
                        tr.appendChild(td6);

                        const td7 = document.createElement('td');
                        td7.textContent = row.ml_time;
                        tr.appendChild(td7);

                        const td8 = document.createElement('td');
                        td8.textContent = row.weekly;
                        tr.appendChild(td8);

                        const td9 = document.createElement('td');
                        td9.textContent = row.p_max;
                        tr.appendChild(td9);

                        const td10 = document.createElement('td');
                        const link1 = document.createElement('a');
                        link1.href = 'r_formbrowse.php?rest_sid=' + row.rest_sid;
                        link1.innerHTML = '<i class="fa-solid fa-circle-info text-primary"></i>';
                        td10.appendChild(link1);
                        tr.appendChild(td10);

                        const td11 = document.createElement('td');
                        const link2 = document.createElement('a');
                        link2.href = 'r_formedit.php?rest_sid=' + row.rest_sid;
                        link2.innerHTML = '<i class="fa-solid fa-pen-to-square text-success"></i>';
                        td11.appendChild(link2);
                        tr.appendChild(td11);

                        const td12 = document.createElement('td');
                        const link3 = document.createElement('a');
                        link3.href = 'javascript: delete_it(' + row.rest_sid + ')';
                        link3.innerHTML = '<i class="fa-solid fa-trash-can text-danger"></i>';
                        td12.appendChild(link3);
                        tr.appendChild(td12);

                        // 將 tr 元素插入到 tbody 中
                        tbody.appendChild(tr);
                    });

                } else {
                    // API 請求失敗，處理錯誤訊息
                    console.log(obj.error);
                }
            })
            .catch(ex => {
                console.log(ex);
            })
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>