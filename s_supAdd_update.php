<?php
require './partsNOEDIT/connect-db.php';

//供應商列表
$sql_shopSup = "SELECT * FROM `shop_supplier` ORDER BY `name`";
$r_shopSup = $pdo->query($sql_shopSup)->fetchAll();

?>

<?php include './partsNOEDIT/html-head.php' ?>
<style>
    .s_Form1,
    #infoBar,
    #img {
        display: none;
    }

    .s_ImgBox {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 50px;
        color: lightgray;
        position: relative;
    }

    #s_proImgBox {
        margin-top: 10px;
        border: 2px dashed lightgray;
        width: 150px;
        height: 150px;
    }

    #s_imginfo {
        padding: 5px;
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: none;
        position: absolute;
    }
</style>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="row p-0 m-0">
    <div class="col-1"></div>
    <div class="col">
        <form name="s_Form1" class="s_Form1">
            <input type="file" name="shopTepProImg" accept="image/jpeg" id="s_tepProImg">
        </form>
        <div id="s_proDetTepImgBox"></div>
        <form class="pt-4" name="s_Form3" onsubmit="checkForm(event)">
            <div class="d-flex align-item-center">
                <h2 class="me-auto pt-3 m-0">新增供應商</h2>
                <div class="mt-3 s_allbtn mb-3">
                    <button type="submit" class="btn btn-primary">確認新增</button>
                    <button type="button" class="btn btn-danger ms-3" onclick="cancelcreate()">取消新增</button>
                </div>
            </div>
            <div class="row pb-3 mt-4" id="s_proDetBox">
                <div class="col-10 mb-3 s_spec">
                    <div class="row">
                        <div class="col-4">
                            <div class="w-100 s_ImgBox" onclick="shopAddMainImg()" id="s_proImgBox">
                                <img src="" id="s_imginfo">+
                            </div>
                            <input type="text" name="img" id="img">
                        </div>
                        <div class="col-4">
                            <label class="form-label">供應商名稱</label>
                            <input type="text" class="form-control" id="name" name="name" data-required="1">
                            <div class="form-text"></div>
                        </div>
                        <div class="col-4">
                            <label class="form-label">產地</label>
                            <input type="text" class="form-control" id="made_in_where" name="made_in_where" data-required="1">
                            <div class="form-text"></div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered s_supTable border-secondary">
                <?php foreach ($r_shopSup as $r) : ?>
                    <tr>
                        <td><?= $r['supplier_sid'] ?></td>
                        <td><?= $r['name'] ?></td>
                        <td><?= $r['made_in_where'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="alert alert-danger" id="infoBar" role="alert"></div>
        </form>
    </div>
    <div class="col-1"></div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    function cancelcreate() {
        location.href = 's_proAdd_update.php'
    }

    //新增供應商照片+API
    const shopTepProImg = document.querySelector("#s_tepProImg");

    function shopAddMainImg() {
        //模擬點擊
        shopTepProImg.click();
    }

    shopTepProImg.addEventListener("change", () => {
        const fd = new FormData(document.s_Form1);
        fetch('s_upLoadProImg-api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                console.log(obj)
                if (obj.filename) {
                    const s_imginfo = document.querySelector('#s_imginfo');
                    const img = document.querySelector('#img');
                    s_imginfo.src = `./s_Imgs/${obj.filename}`;
                    s_imginfo.style.display = "block";
                    img.value = obj.filename;
                }
            }).catch(ex => {
                console.log(ex)
            })
    })

    function checkForm(event) {
        event.preventDefault()
        const filedSup = document.querySelectorAll('form [data-required="1"]')
        let isPass = true;

        //恢復所有欄位的外觀
        for (let f of filedSup) {
            f.style.border = '1px solid #CCC';
            f.closest(".col-4").lastChild.innerText = '';
        }

        const theSup = <?= json_encode($r_shopSup, JSON_UNESCAPED_UNICODE) ?>;
        console.log(theSup)
        //部分欄位皆必填
        for (let f of filedSup) {
            if (!f.value) {
                isPass = false;
                f.style.border = '1px solid red';
                f.closest(".col-4").lastChild.innerText = '請輸入資料';
            }
            for (let a of theSup) {
                if (f.value == a.name) {
                    isPass = false;
                    f.style.border = '1px solid red';
                    f.closest(".col-4").lastChild.innerText = `"${f.value}"已存在`;
                }
            }
        }

        if (isPass) {
            const fd = new FormData(document.s_Form3);
            fetch('s_supAdd-api_update.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                .then(obj => {
                    if (obj.success) {
                        infoBar.innerText = '新增成功';
                        infoBar.classList.remove('alert-danger');
                        infoBar.classList.add('alert-success');
                        infoBar.style.display = 'block'
                        setTimeout(() => {
                            location.href = 's_proAdd_update.php'
                        }, 2000)
                    } else {
                        infoBar.innerText = '新增失敗';
                        infoBar.classList.add('alert-danger');
                        infoBar.classList.remove('alert-success');
                        infoBar.style.display = 'block'
                    }
                    setTimeout(() => {
                        infoBar.style.display = 'none'
                    }, 2000)
                    console.log(obj);
                }).catch(ex => {
                    console.log(ex); //除錯使用
                    infoBar.innerText = '新增發生錯誤，請通知後端人員';
                    infoBar.classList.add('alert-danger');
                    infoBar.classList.remove('alert-success');
                    infoBar.style.display = 'block'
                    setTimeout(() => {
                        infoBar.style.display = 'none'
                    }, 2000)
                })
        }

    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>