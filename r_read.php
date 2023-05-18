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
    // $sql = sprintf("SELECT ri.*, rc.`catg_name` FROM `rest_info` ri JOIN `rest_catg` rc ON ri.`catg_sid` = rc.`catg_sid` ORDER BY rest_sid DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);

    $sql = sprintf("SELECT ri.*, rc.catg_name, COALESCE(rb.book_count, 0) AS book_count
    FROM rest_info ri
    JOIN rest_catg rc ON ri.catg_sid = rc.catg_sid
    LEFT JOIN (
        SELECT rest_sid, COUNT(book_sid) AS book_count
        FROM rest_book
        GROUP BY rest_sid
    ) rb ON ri.rest_sid = rb.rest_sid
    ORDER BY rest_sid DESC  LIMIT %s, %s", ($page - 1) * $perPage, $perPage);

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

    .r_th {
        background-color: #FFF7D4 !important;
    }

    .r_underline {
        border-bottom: 2.5px solid #FFD95A;
    }

    .asc,
    .book_asc {
        color: #FFB84C;
        cursor: pointer;
    }

    /* .r_thover:hover {
        background-color: #ffefb3 !important;
    } */

    /* .r_table {
        border: 1px solid #B7C4CF;
    } */
</style>

<div class="t_row pt-4">

    <div class="d-flex mb-3 pt-4 px-0">
        <div class="pe-3">
            <select class="form-select" name="catg_sid" id="catg_sid">
                <option value="">全部類別</option>
                <?php foreach ($items as $i) : ?>
                    <option value="<?= $i['catg_sid'] ?>"><?= $i['catg_name'] ?></option>
                <?php endforeach ?>
            </select>
        </div>


        <form name="keyword1" onsubmit="checkForm(event)">
            <div class="d-flex">
                <div class="pe-2">
                    <input type="text" class="form-control" name="keyword" id="keyword" placeholder="餐廳名稱搜尋">
                </div>
                <button type="submit" class="search btn btn-warning me-2">搜尋</button>
            </div>
        </form>
        <form name="reset" onsubmit="checkForm1(event)">
            <button type="submit" class="reset btn btn-light me-2">重置</button>
        </form>


        <div class=" ms-auto">
            <a class="btn btn-primary" href="r_formadd.php"><i class="fa-sharp fa-solid fa-circle-plus pe-2"></i>新增餐廳</a>
        </div>
    </div>


    <div class="row">
        <table class="r_table table table-striped table-hover">
            <thead class="r_underline">
                <tr>
                    <th class="r_th py-3" scope="col">編號 <i class="asc fa-solid fa-caret-down ms-1" id="asc"></i></th>
                    <th class="r_th py-3" scope="col">餐廳名稱</th>
                    <th class="r_th py-3" scope="col">餐廳類別</th>
                    <th class="r_th py-3" scope="col">餐廳電話</th>
                    <th class="r_th py-3" scope="col">早上開始時間</th>
                    <th class="r_th py-3" scope="col">晚上結束時間</th>
                    <th class="r_th py-3" scope="col">用餐時間</th>
                    <th class="r_th py-3" scope="col">星期</th>
                    <th class="r_th py-3" scope="col">人數上限</th>
                    <th class="r_th py-3" scope="col">預約次數 <i class="book_asc fa-solid fa-caret-down ms-1 " id="book_asc"></i></th>
                    <th class="r_th py-3 text-center" scope="col" class="text-center">細項</th>
                    <th class="r_th py-3 text-center" scope="col" class="text-center">編輯</th>
                    <th class="r_th py-3 text-center" scope="col" class="text-center">刪除</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r) : ?>
                    <tr class="r_thover">

                        <td class="py-3"><?= $r['rest_sid'] ?></td>
                        <td class="py-3"><?= $r['rest_name'] ?></td>
                        <td class="py-3"><?= $r['catg_name'] ?></td>
                        <td class="py-3"><?= $r['rest_phone'] ?></td>
                        <td class="py-3"><?= $r['m_start'] ?></td>
                        <td class="py-3"><?= $r['n_end'] ?></td>
                        <td class="py-3"><?= $r['ml_time'] ?></td>
                        <td class="py-3"><?= $r['weekly'] ?></td>
                        <td class="py-3"><?= $r['p_max'] ?></td>
                        <!-- 預約次數 -->
                        <td class="py-3"><?= $r['book_count'] ?></td>
                        <td class="text-center align-middle"><a href="r_formbrowse.php?rest_sid=<?= $r['rest_sid'] ?>">
                                <i class="fa-solid fa-circle-info text-primary"></i>
                            </a></td>
                        <td class="text-center align-middle"><a href="r_formedit.php?rest_sid=<?= $r['rest_sid'] ?>">
                                <i class=" fa-solid fa-pen-to-square text-success"></i>
                            </a>
                        </td>
                        <td class="text-center align-middle"><a href="javascript: delete_it(<?= $r['rest_sid'] ?>, '<?= $r['rest_name'] ?>')">
                                <i class="fa-solid fa-trash-can text-danger "></i>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 刪除提示
    // function delete_it(sid) {
    //     if (confirm(`是否要刪除編號為 ${sid} 的資料?`)) {
    //         location.href = 'r_delete_api.php?rest_sid=' + sid;
    //     }
    // }

    function delete_it(sid, rest_name) {
        Swal.fire({
            title: '',
            text: `是否要刪除 "編號${sid} ${rest_name}" 的資料?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#adb5bd',
            confirmButtonText: '確定',
            cancelButtonText: '取消'
        }).then((result) => {
            if (result.isConfirmed) {
                location.href = 'r_delete_api.php?rest_sid=' + sid;
            }
        });
    }

    // booking順序
    const basc = document.querySelector('#book_asc');
    let isAscending1 = true;

    basc.addEventListener('click', function() {
        const apiUrlb = isAscending1 ? 'r_book_desc_api.php' : 'r_book_asc_api.php';
        const caretIcon = document.querySelector('.book_asc');


        fetch(apiUrlb)
            .then(response => response.json())
            .then(obj => {
                console.log(obj);
                if (obj.success) {

                    const rows = obj.getData;
                    const tbody = document.querySelector('tbody');
                    tbody.innerHTML = '';

                    rows.forEach(row => {
                        const tr = document.createElement('tr');

                        const td1 = document.createElement('td');
                        td1.classList.add('py-3');
                        td1.textContent = row.rest_sid;
                        tr.appendChild(td1);

                        const td2 = document.createElement('td');
                        td2.classList.add('py-3');
                        td2.textContent = row.rest_name;
                        tr.appendChild(td2);

                        const td3 = document.createElement('td');
                        td3.classList.add('py-3');
                        td3.textContent = row.catg_name;
                        tr.appendChild(td3);

                        const td4 = document.createElement('td');
                        td4.classList.add('py-3');
                        td4.textContent = row.rest_phone;
                        tr.appendChild(td4);

                        const td5 = document.createElement('td');
                        td5.textContent = row.m_start;
                        td5.classList.add('py-3');
                        tr.appendChild(td5);

                        const td6 = document.createElement('td');
                        td6.textContent = row.n_end;
                        td6.classList.add('py-3');
                        tr.appendChild(td6);

                        const td7 = document.createElement('td');
                        td7.textContent = row.ml_time;
                        td7.classList.add('py-3');
                        tr.appendChild(td7);

                        const td8 = document.createElement('td');
                        td8.textContent = row.weekly;
                        td8.classList.add('py-3');
                        tr.appendChild(td8);

                        const td9 = document.createElement('td');
                        td9.textContent = row.p_max;
                        td9.classList.add('py-3');
                        tr.appendChild(td9);

                        const td91 = document.createElement('td');
                        td91.textContent = row.book_count;
                        td91.classList.add('py-3');
                        tr.appendChild(td91);

                        const td10 = document.createElement('td');
                        td10.classList.add('text-center');
                        td10.classList.add('align-middle');
                        const link1 = document.createElement('a');
                        link1.href = 'r_formbrowse.php?rest_sid=' + row.rest_sid;
                        link1.innerHTML = '<i class="fa-solid fa-circle-info text-primary"></i>';
                        td10.appendChild(link1);
                        tr.appendChild(td10);

                        const td11 = document.createElement('td');
                        td11.classList.add('text-center');
                        td11.classList.add('align-middle');
                        const link2 = document.createElement('a');
                        link2.href = 'r_formedit.php?rest_sid=' + row.rest_sid;
                        link2.innerHTML = '<i class="fa-solid fa-pen-to-square text-success"></i>';
                        td11.appendChild(link2);
                        tr.appendChild(td11);

                        const td12 = document.createElement('td');
                        td12.classList.add('text-center');
                        td12.classList.add('align-middle');
                        const link3 = document.createElement('a');
                        link3.href = 'javascript: delete_it(' + row.rest_sid + ')';
                        link3.innerHTML = '<i class="fa-solid fa-trash-can text-danger"></i>';
                        td12.appendChild(link3);
                        tr.appendChild(td12);

                        tbody.appendChild(tr);
                    });
                    isAscending = !isAscending
                    caretIcon.classList.toggle('fa-caret-up');
                    caretIcon.classList.toggle('fa-caret-down');
                } else {
                    console.error(obj.error);
                }
            })
            .catch(ex => {
                console.log(ex);
            })
        isAscending1 = !isAscending1;


    });

    // 商品編號順序
    const asc = document.querySelector('#asc');
    let isAscending = true;

    asc.addEventListener('click', function() {
        const apiUrl = isAscending ? 'r_sid_asc_api.php' : 'r_sid_desc_api.php';
        const caretIcon = document.querySelector('.asc');


        fetch(apiUrl)
            .then(response => response.json())
            .then(obj => {
                console.log(obj);
                if (obj.success) {
                    const rows = obj.getData;
                    const tbody = document.querySelector('tbody');
                    tbody.innerHTML = '';

                    rows.forEach(row => {
                        const tr = document.createElement('tr');

                        const td1 = document.createElement('td');
                        td1.classList.add('py-3');
                        td1.textContent = row.rest_sid;
                        tr.appendChild(td1);

                        const td2 = document.createElement('td');
                        td2.classList.add('py-3');
                        td2.textContent = row.rest_name;
                        tr.appendChild(td2);

                        const td3 = document.createElement('td');
                        td3.classList.add('py-3');
                        td3.textContent = row.catg_name;
                        tr.appendChild(td3);

                        const td4 = document.createElement('td');
                        td4.classList.add('py-3');
                        td4.textContent = row.rest_phone;
                        tr.appendChild(td4);

                        const td5 = document.createElement('td');
                        td5.textContent = row.m_start;
                        td5.classList.add('py-3');
                        tr.appendChild(td5);

                        const td6 = document.createElement('td');
                        td6.textContent = row.n_end;
                        td6.classList.add('py-3');
                        tr.appendChild(td6);

                        const td7 = document.createElement('td');
                        td7.textContent = row.ml_time;
                        td7.classList.add('py-3');
                        tr.appendChild(td7);

                        const td8 = document.createElement('td');
                        td8.textContent = row.weekly;
                        td8.classList.add('py-3');
                        tr.appendChild(td8);

                        const td9 = document.createElement('td');
                        td9.textContent = row.p_max;
                        td9.classList.add('py-3');
                        tr.appendChild(td9);

                        const td91 = document.createElement('td');
                        td91.textContent = row.book_count;
                        td91.classList.add('py-3');
                        tr.appendChild(td91);

                        const td10 = document.createElement('td');
                        td10.classList.add('text-center');
                        td10.classList.add('align-middle');
                        const link1 = document.createElement('a');
                        link1.href = 'r_formbrowse.php?rest_sid=' + row.rest_sid;
                        link1.innerHTML = '<i class="fa-solid fa-circle-info text-primary"></i>';
                        td10.appendChild(link1);
                        tr.appendChild(td10);

                        const td11 = document.createElement('td');
                        td11.classList.add('text-center');
                        td11.classList.add('align-middle');
                        const link2 = document.createElement('a');
                        link2.href = 'r_formedit.php?rest_sid=' + row.rest_sid;
                        link2.innerHTML = '<i class="fa-solid fa-pen-to-square text-success"></i>';
                        td11.appendChild(link2);
                        tr.appendChild(td11);

                        const td12 = document.createElement('td');
                        td12.classList.add('text-center');
                        td12.classList.add('align-middle');
                        const link3 = document.createElement('a');
                        link3.href = 'javascript: delete_it(' + row.rest_sid + ')';
                        link3.innerHTML = '<i class="fa-solid fa-trash-can text-danger"></i>';
                        td12.appendChild(link3);
                        tr.appendChild(td12);

                        tbody.appendChild(tr);
                    });
                    isAscending = !isAscending
                    caretIcon.classList.toggle('fa-caret-up');
                    caretIcon.classList.toggle('fa-caret-down');

                } else {
                    console.error(obj.error);
                }
            })
            .catch(ex => {
                console.log(ex);
            })

    });

    //RESET

    function checkForm1(event) {
        event.preventDefault();
        const fd = new FormData(document.reset);
        fetch('r_reset_api.php', {
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
                    const kw = document.querySelector("#keyword");
                    kw.value = "";
                    const ctg = document.querySelector("#catg_sid");
                    ctg.value = "";

                    data.forEach(function(row) {
                        const tr = document.createElement('tr');

                        // 建立各個欄位的儲存格
                        const td1 = document.createElement('td');
                        td1.textContent = row.rest_sid;
                        td1.classList.add('py-3');
                        tr.appendChild(td1);

                        const td2 = document.createElement('td');
                        td2.textContent = row.rest_name;
                        td2.classList.add('py-3');
                        tr.appendChild(td2);

                        const td3 = document.createElement('td');
                        td3.textContent = row.catg_name;
                        td3.classList.add('py-3');
                        tr.appendChild(td3);

                        const td4 = document.createElement('td');
                        td4.textContent = row.rest_phone;
                        td4.classList.add('py-3');
                        tr.appendChild(td4);

                        const td5 = document.createElement('td');
                        td5.textContent = row.m_start;
                        td5.classList.add('py-3');
                        tr.appendChild(td5);

                        const td6 = document.createElement('td');
                        td6.textContent = row.n_end;
                        td6.classList.add('py-3');
                        tr.appendChild(td6);

                        const td7 = document.createElement('td');
                        td7.textContent = row.ml_time;
                        td7.classList.add('py-3');
                        tr.appendChild(td7);

                        const td8 = document.createElement('td');
                        td8.textContent = row.weekly;
                        td8.classList.add('py-3');
                        tr.appendChild(td8);

                        const td9 = document.createElement('td');
                        td9.textContent = row.p_max;
                        td9.classList.add('py-3');
                        tr.appendChild(td9);

                        const td91 = document.createElement('td');
                        td91.textContent = row.book_count;
                        td91.classList.add('py-3');
                        tr.appendChild(td91);

                        const td10 = document.createElement('td');
                        td10.classList.add('text-center');
                        td10.classList.add('align-middle');
                        const link1 = document.createElement('a');
                        link1.href = 'r_formbrowse.php?rest_sid=' + row.rest_sid;
                        link1.innerHTML = '<i class="fa-solid fa-circle-info text-primary"></i>';
                        td10.appendChild(link1);
                        tr.appendChild(td10);

                        const td11 = document.createElement('td');
                        td11.classList.add('text-center');
                        td11.classList.add('align-middle');
                        const link2 = document.createElement('a');
                        link2.href = 'r_formedit.php?rest_sid=' + row.rest_sid;
                        link2.innerHTML = '<i class="fa-solid fa-pen-to-square text-success"></i>';
                        td11.appendChild(link2);
                        tr.appendChild(td11);

                        const td12 = document.createElement('td');
                        td12.classList.add('text-center');
                        td12.classList.add('align-middle');
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


    // 關鍵字搜尋
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
                        td1.classList.add('py-3');
                        td1.textContent = row.rest_sid;
                        tr.appendChild(td1);

                        const td2 = document.createElement('td');
                        td2.classList.add('py-3');
                        td2.textContent = row.rest_name;
                        tr.appendChild(td2);

                        const td3 = document.createElement('td');
                        td3.classList.add('py-3');
                        td3.textContent = row.catg_name;
                        tr.appendChild(td3);

                        const td4 = document.createElement('td');
                        td4.classList.add('py-3');
                        td4.textContent = row.rest_phone;
                        tr.appendChild(td4);

                        const td5 = document.createElement('td');
                        td5.classList.add('py-3');
                        td5.textContent = row.m_start;
                        tr.appendChild(td5);

                        const td6 = document.createElement('td');
                        td6.classList.add('py-3');
                        td6.textContent = row.n_end;
                        tr.appendChild(td6);

                        const td7 = document.createElement('td');
                        td7.classList.add('py-3');
                        td7.textContent = row.ml_time;
                        tr.appendChild(td7);

                        const td8 = document.createElement('td');
                        td8.classList.add('py-3');
                        td8.textContent = row.weekly;
                        tr.appendChild(td8);

                        const td9 = document.createElement('td');
                        td9.classList.add('py-3');
                        td9.textContent = row.p_max;
                        tr.appendChild(td9);

                        const td91 = document.createElement('td');
                        td91.textContent = row.book_count;
                        td91.classList.add('py-3');
                        tr.appendChild(td91);

                        const td10 = document.createElement('td');
                        td10.classList.add('text-center');
                        td10.classList.add('align-middle');
                        const link1 = document.createElement('a');
                        link1.href = 'r_formbrowse.php?rest_sid=' + row.rest_sid;
                        link1.innerHTML = '<i class="fa-solid fa-circle-info text-primary"></i>';
                        td10.appendChild(link1);
                        tr.appendChild(td10);

                        const td11 = document.createElement('td');
                        td11.classList.add('text-center');
                        td11.classList.add('align-middle');
                        const link2 = document.createElement('a');
                        link2.href = 'r_formedit.php?rest_sid=' + row.rest_sid;
                        link2.innerHTML = '<i class="fa-solid fa-pen-to-square text-success"></i>';
                        td11.appendChild(link2);
                        tr.appendChild(td11);

                        const td12 = document.createElement('td');
                        td12.classList.add('text-center');
                        td12.classList.add('align-middle');
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


    //餐廳類別
    const catgSelect = document.querySelector('#catg_sid');

    catgSelect.addEventListener('change', function() {

        const cs = catgSelect.value;
        console.log(cs);
        // 發送 API 請求
        fetch(`r_select_sid_api.php?catg_sid=${cs}`)
            .then(response => response.json())
            .then(obj => {
                console.log(obj);
                if (obj.success) {

                    const rows = obj.getData;
                    const tbody = document.querySelector('tbody');
                    tbody.innerHTML = '';

                    rows.forEach(row => {
                        const tr = document.createElement('tr');

                        // 建立各個欄位的儲存格
                        const td1 = document.createElement('td');
                        td1.classList.add('py-3');
                        td1.textContent = row.rest_sid;
                        tr.appendChild(td1);

                        const td2 = document.createElement('td');
                        td2.classList.add('py-3');
                        td2.textContent = row.rest_name;
                        tr.appendChild(td2);

                        const td3 = document.createElement('td');
                        td3.classList.add('py-3');
                        td3.textContent = row.catg_name;
                        tr.appendChild(td3);

                        const td4 = document.createElement('td');
                        td4.classList.add('py-3');
                        td4.textContent = row.rest_phone;
                        tr.appendChild(td4);

                        const td5 = document.createElement('td');
                        td5.textContent = row.m_start;
                        td5.classList.add('py-3');
                        tr.appendChild(td5);

                        const td6 = document.createElement('td');
                        td6.textContent = row.n_end;
                        td6.classList.add('py-3');
                        tr.appendChild(td6);

                        const td7 = document.createElement('td');
                        td7.textContent = row.ml_time;
                        td7.classList.add('py-3');
                        tr.appendChild(td7);

                        const td8 = document.createElement('td');
                        td8.textContent = row.weekly;
                        td8.classList.add('py-3');
                        tr.appendChild(td8);

                        const td9 = document.createElement('td');
                        td9.textContent = row.p_max;
                        td9.classList.add('py-3');
                        tr.appendChild(td9);

                        const td91 = document.createElement('td');
                        td91.textContent = row.book_count;
                        td91.classList.add('py-3');
                        tr.appendChild(td91);

                        const td10 = document.createElement('td');
                        td10.classList.add('text-center');
                        td10.classList.add('align-middle');
                        const link1 = document.createElement('a');
                        link1.href = 'r_formbrowse.php?rest_sid=' + row.rest_sid;
                        link1.innerHTML = '<i class="fa-solid fa-circle-info text-primary"></i>';
                        td10.appendChild(link1);
                        tr.appendChild(td10);

                        const td11 = document.createElement('td');
                        td11.classList.add('text-center');
                        td11.classList.add('align-middle');
                        const link2 = document.createElement('a');
                        link2.href = 'r_formedit.php?rest_sid=' + row.rest_sid;
                        link2.innerHTML = '<i class="fa-solid fa-pen-to-square text-success"></i>';
                        td11.appendChild(link2);
                        tr.appendChild(td11);

                        const td12 = document.createElement('td');
                        td12.classList.add('text-center');
                        td12.classList.add('align-middle');
                        const link3 = document.createElement('a');
                        link3.href = 'javascript: delete_it(' + row.rest_sid + ')';
                        link3.innerHTML = '<i class="fa-solid fa-trash-can text-danger"></i>';
                        td12.appendChild(link3);
                        tr.appendChild(td12);

                        tbody.appendChild(tr);
                    });
                } else {
                    console.error(obj.error);
                }
            })
            .catch(ex => {
                console.log(ex);
            })
    });
</script>
<?php include './partsNOEDIT/html-foot.php' ?>