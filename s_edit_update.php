<?php
require './partsNOEDIT/connect-db.php';

$product_detail_sid = isset($_GET['product_detail_sid']) ? $_GET['product_detail_sid'] : '';
$product_sid = isset($_GET['product_sid']) ? $_GET['product_sid'] : '';

//主商品資料
$sql_shopInfo = sprintf("SELECT * FROM `shop_product` WHERE `product_sid`='%s'", $product_sid);
$r_shopInfo = $pdo->query($sql_shopInfo)->fetch();


//細項商品資料
$sql_shopSpecInfo = sprintf("SELECT * FROM `shop_product_specific` ps
JOIN `shop_product_detail` pd ON ps.`product_detail_sid`=pd.`product_detail_sid` AND ps.`product_sid`=pd.`product_sid` WHERE ps.`product_sid`='%s'", $product_sid);
$r_shopSpecInfo = $pdo->query($sql_shopSpecInfo)->fetchAll();

$r_shopSpecInfo2 = [];

foreach ($r_shopSpecInfo as $item) {
    $key = $item['product_detail_sid'];

    if (!isset($r_shopSpecInfo2[$key])) {
        $r_shopSpecInfo2[$key] = [
            'product_sid' => $item['product_sid'],
            'product_detail_sid' => $item['product_detail_sid'],
            'name' => $item['name'],
            'price' => $item['price'],
            'qty' => $item['qty'],
            'img' => $item['img'],
            'for_age' => $item['for_age'],
            'specific_sid' => [$item['specific_sid']],
            'specific_detail_sid' => [$item['specific_detail_sid']]
        ];
    } else {
        $r_shopSpecInfo2[$key]['specific_sid'][] = $item['specific_sid'];
        $r_shopSpecInfo2[$key]['specific_detail_sid'][] = $item['specific_detail_sid'];
    }
}

$r_shopSpecInfo2 = array_values($r_shopSpecInfo2);

//下拉的大類別列表
$sql_shopCat = "SELECT distinct `category_sid`, `name` FROM `shop_category` ORDER BY `name`";
$r_shopCat = $pdo->query($sql_shopCat)->fetchAll();

//下拉的子類別列表
$sql_shopCatDet = "SELECT * FROM `shop_category` ORDER BY `detail_num` ";
$r_shopCatDet = $pdo->query($sql_shopCatDet)->fetchAll();

//下拉的供應商列表
$sql_shopSup = "SELECT distinct `supplier_sid`, `name` FROM `shop_supplier` ORDER BY `name`";
$r_shopSup = $pdo->query($sql_shopSup)->fetchAll();

//下拉的產地列表
$sql_shopSupMIW = "SELECT * FROM `shop_supplier`";
$r_shopSupMIW = $pdo->query($sql_shopSupMIW)->fetchAll();

//下拉的規格類別料表
$sql_shopSpec = "SELECT distinct `specific_sid`, `name` FROM `shop_specific` ORDER BY `specific_sid`";
$r_shopSpec = $pdo->query($sql_shopSpec)->fetchAll();

//下拉的各規格明細料表

$sql_shopSpecDet = "SELECT * FROM `shop_specific`";
$r_shopSpecDet = $pdo->query($sql_shopSpecDet)->fetchAll();

?>

<?php include './partsNOEDIT/html-head.php' ?>
<style>
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
        /* width: 500px; */
        height: 430px;
    }

    .s_Form1,
    #s_proImg,
    .s_proDetImg,
    #s_proDetTepImgBox,
    .s_proDetNum,
    #infoBar,
    #s_product_sid,
    #s_proOnWeb {
        display: none;
        /* color: pink */
    }

    #s_imginfo {
        padding: 5px;
        width: 100%;
        height: 100%;
        object-fit: contain;
        /* display: none; */
        position: absolute;
    }

    .s_proDetImgBox {
        height: 190px;
        /* border: 1px dashed lightgray; */
    }

    .s_allbtn {
        display: flex;
        justify-content: end;
    }

    .form-text {
        color: red;
    }
</style>

<?php include './partsNOEDIT/navbar.php' ?>
<div class="row p-0 m-0">
    <div class="col-1"></div>
    <div class="col">
        <form name="s_Form1" class="s_Form1">
            <input type="file" name="shopTepProImg" accept="image/jpeg" id="s_tepProImg">
        </form>
        <div id="s_proDetTepImgBox">
            <?php foreach ($r_shopSpecInfo2 as $v) : ?>
                <form name="s_Form2<?= intval($v['product_detail_sid']) ?>" class="s_Form1">
                    <input type="file" name="shopTepProImg" accept="image/jpeg" class="s_tepProPetImg">
                </form>
            <?php endforeach; ?>
        </div>

        <form class="pt-4 pb-4" name="s_Form3" onsubmit="checkForm(event)">
            <div class="d-flex">
                <h2 class="me-auto">編輯商品</h2>
                <div class="d-flex align-items-center">
                    <div class="form-check me-3">
                        <input class="form-check-input" type="radio" name="shelf_status" id="on" value="1" <?= $r_shopInfo['shelf_status'] == 1 ? "checked" : "" ?>>
                        <label class="form-check-label" for="on">
                            上架
                        </label>
                    </div>
                    <div class="form-check me-5">
                        <input class="form-check-input" type="radio" name="shelf_status" id="off" value="2" <?= $r_shopInfo['shelf_status'] == 2 ? "checked" : "" ?>>
                        <label class="form-check-label" for="off">
                            下架
                        </label>
                    </div>
                </div>
                <div class="mt-3 s_allbtn mb-3">
                    <button type="button" class="btn btn-warning" onclick="createSpec()">新增規格</button>
                </div>
            </div>
            <div id="s_proOnWeb"><input type="text" name="shelf_date" value="<?= $r_shopInfo['shelf_date'] ?>"></div>
            <div class="row pb-3 border-bottom">
                <div class="col-5">
                    <div class="w-100 s_ImgBox" onclick="shopAddMainImg()" id="s_proImgBox"><img src="./s_Imgs/<?= $r_shopInfo['img'] ?>" id="s_imginfo">+</div>
                    <input type="text" name="img" id="s_proImg" value=<?= $r_shopInfo['img'] ?>>
                </div>
                <div class="col-7">
                    <div class="mb-3" id="s_product_sid">
                        <label class="form-label" for="product_sid">產品編號</label>
                        <input type="text" class="form-control" name="product_sid" value=<?= $r_shopInfo['product_sid'] ?>>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="name">產品名稱</label>
                        <input type="text" class="form-control" id="name" name="name" data-required="1" value=<?= htmlentities($r_shopInfo['name']) ?>>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">適用對象</label>
                        <div class="d-flex">
                            <select class="form-select" name="for_pet_type" id="shopForSel" data-required="1">
                                <option value="" disabled>--請選擇--</option>
                                <option value="D" <?= $r_shopInfo['for_pet_type'] == 'D' ? "selected" : "" ?>>狗</option>
                                <option value="C" <?= $r_shopInfo['for_pet_type'] == 'C' ? "selected" : "" ?>>貓</option>
                                <option value="B" <?= $r_shopInfo['for_pet_type'] == 'B' ? "selected" : "" ?>>皆可</option>
                            </select>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">

                        <div class="row">
                            <div class="col-6">
                                <label class="form-label" for="s_supplier_sid">產品類別</label>
                                <select class="form-select" name="category_sid" id="s_category_sid" data-required="1">
                                    <option value="" disabled>--請選擇--</option>
                                    <?php foreach ($r_shopCat as $r) : ?>
                                        <option value="<?= $r['category_sid'] ?>" <?= $r_shopInfo['category_sid'] == $r['category_sid'] ? "selected" : "" ?>><?= $r['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="s_category_detail_sid">細項類別</label>
                                <select class="form-select" name="category_detail_sid" id="s_category_detail_sid">
                                    <?php foreach ($r_shopCatDet as $r) : ?>
                                        <?php if ($r['category_sid'] == $r_shopInfo['category_sid']) : ?>
                                            <option value="<?= $r['category_detail_sid'] ?>" <?= $r_shopInfo['category_detail_sid'] == $r['category_detail_sid'] ? "selected" : "" ?>><?= $r['detail_name'] ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">

                        <div class="row">
                            <div class="col-6">
                                <label class="form-label" for="s_supplier_sid">供應商</label>
                                <select class="form-select" name="supplier_sid" id="s_supplier_sid" data-required="1">
                                    <option value="" disabled>--請選擇--</option>
                                    <?php foreach ($r_shopSup as $r) : ?>
                                        <option value="<?= $r['supplier_sid'] ?>" <?= $r_shopInfo['supplier_sid'] == $r['supplier_sid'] ? "selected" : "" ?>><?= $r['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="s_made_in_where">製造地</label>
                                <select class="form-select" name="made_in_where" id="s_made_in_where" data-required="1">
                                    <?php foreach ($r_shopSupMIW as $r) : ?>
                                        <?php if ($r['supplier_sid'] == $r_shopInfo['supplier_sid']) : ?>
                                            <option value="<?= $r['supplier_sid'] ?>" <?= $r_shopInfo['supplier_sid'] == $r['supplier_sid'] ? "selected" : "" ?>><?= $r['made_in_where'] ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">產品描述</label>
                        <textarea name="description" id="description" class="form-control" data-required="1"><?= htmlentities($r_shopInfo['description']) ?></textarea>
                        <div class="form-text"></div>
                    </div>
                </div>
            </div>
            <div class="row pb-3 border-bottom mt-4" id="s_proDetBox">
                <?php foreach ($r_shopSpecInfo2 as $k => $v) : ?>
                    <div class="col-3 mb-3">
                        <div class="mb-3 s_proDetNum">
                            <input type="text" class="form-control " name="product_detail_sid[]" value="<?= intval($v['product_detail_sid']) ?>">
                        </div>
                        <div class="mb-3">
                            <div class="s_ImgBox s_proDetImgBox"><img <?= $v['img'] == "" ? "" : sprintf("src='./s_imgs/%s'", $v['img']) ?> id="s_imginfo">+</div>
                            <input type="text" name="img1[]" class="s_proDetImg" value=<?= $v['img'] ?>>
                        </div>
                        <div class="mb-3 s_spec">
                            <label class="form-label">規格一</label>
                            <div class="row">
                                <div class="col-6">
                                    <select class="form-select s_specific_sid1" name="specific_sid1[]" data-required="1">
                                        <option value="" disabled>--請選擇--</option>
                                        <?php foreach ($r_shopSpec as $r) : ?>
                                            <option value="<?= $r['specific_sid'] ?>" <?= $r['specific_sid'] == $v['specific_sid'][0] ? "selected" : "" ?>><?= $r['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select class="form-select s_specific_detail_sid1" name="specific_detail_sid1[]">
                                        <?php foreach ($r_shopSpecDet as $r) : ?>
                                            <?php if ($r['specific_sid'] == $v['specific_sid'][0]) : ?>
                                                <option value="<?= $r['specific_detail_sid'] ?>" <?= $r['specific_detail_sid'] == $v['specific_detail_sid'][0] ? "selected" : "" ?>><?= $r['detail_name'] ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3 s_spec">
                            <label class="form-label">規格二</label>
                            <div class="row">
                                <div class="col-6">
                                    <select class="form-select s_specific_sid2" name="specific_sid2[]">
                                        <option value="" selected disabled>--請選擇--</option>
                                        <?php foreach ($r_shopSpec as $r) : ?>
                                            <?php if ($r['specific_sid'] != $v['specific_sid'][0]) : ?>
                                                <option value="<?= $r['specific_sid'] ?>" <?php if (!empty($v['specific_sid'][1]) && $r['specific_sid'] == $v['specific_sid'][1]) {
                                                                                                echo "selected";
                                                                                            } ?>><?= $r['name'] ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <!-- <option value="">--請選擇--</option> -->
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select class="form-select s_specific_detail_sid2" name="specific_detail_sid2[]">
                                        <?php foreach ($r_shopSpecDet as $r) : ?>
                                            <?php if ($r['specific_sid'] == $v['specific_sid'][1]) : ?>
                                                <option value="<?= $r['specific_detail_sid'] ?>" <?= $r['specific_detail_sid'] == $v['specific_detail_sid'][1] ? "selected" : "" ?>><?= $r['detail_name'] ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="s_price">價格</label>
                            <input type="number" class="form-control" name="price[]" data-required="1" value=<?= $v['price'] ?>>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="s_qty">數量</label>
                            <input type="number" class="form-control" name="qty[]" data-required="1" value=<?= $v['qty'] ?>>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">適用年齡</label>
                            <div class="d-flex">
                                <select class="form-select" name="for_age[]" data-required="1">
                                    <option value="" selected disabled>--請選擇--</option>
                                    <option value="1" <?= $v['for_age'] == 1 ? "selected" : "" ?>>幼年</option>
                                    <option value="2" <?= $v['for_age'] == 2 ? "selected" : "" ?>>成年</option>
                                    <option value="3" <?= $v['for_age'] == 3 ? "selected" : "" ?>>高齡</option>
                                    <option value="4" <?= $v['for_age'] == 4 ? "selected" : "" ?>>皆可</option>
                                </select>
                            </div>
                            <div class="form-text"></div>
                        </div>
                        <button type="button" class="btn btn-success s_add">+</button>
                        <button type="button" class="btn btn-danger s_del">-</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="alert alert-danger" id="infoBar" role="alert"></div>
            <div class="s_allbtn mt-3">
                <button type="button" class="btn btn-secondary" id="s_allcancel">取消編輯</button>
                <button type="button" class="btn btn-primary ms-3" onclick="checkForm(event)">確認編輯</button>
                <button type="button" class="btn btn-danger ms-3" data-bs-toggle="modal" data-bs-target="#s_alldel1">整筆刪除</button>
            </div>
        </form>


        <!-- Modal -->
        <div class="modal fade" id="s_alldel1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">請再次確認</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">是否刪除此項商品的全部資料?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="button" id="s_alldel" class="btn btn-primary">確認</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-1"></div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const theDocFrag = document.createDocumentFragment();

    function resetRemind() {
        const filedall = document.querySelectorAll('form [data-required="1"]')
        for (let fa of filedall) {
            fa.addEventListener('blur', () => {
                fa.style.border = '1px solid #CCC';
                fa.closest('.mb-3').lastChild.textContent = '';
            })
        }
    }
    resetRemind()

    function createSpec() {
        location.href = 's_proSpecAdd_update.php'
    }
    const allCancel = document.querySelector("#s_allcancel");
    allCancel.addEventListener('click', () => {
        location.href = 's_list_update.php'
    })

    const allDel = document.querySelector('#s_alldel')
    allDel.addEventListener('click', () => {
        const theProSid = document.s_Form3.product_sid.value
        const fd = new FormData(document.s_Form3);
        fetch('s_delete-api_update.php', {
            method: 'POST',
            body: fd,
        }).then(() => {
            location.href = 's_list_update.php'
        })
    })

    function checkForm(event) {
        event.preventDefault()
        const filedType = document.querySelectorAll('form [data-required="1"]')

        let isPass = true;

        //恢復所有欄位的外觀
        for (let f of filedType) {
            f.style.border = '1px solid #CCC';
            f.closest('.mb-3').lastChild.textContent = '';
        }

        //部分欄位皆必填
        for (let f of filedType) {
            if (!f.value) {
                isPass = false;
                f.style.border = '1px solid red';
                f.closest('.mb-3').lastChild.textContent = '* 請輸入資料';
            }
        }

        const spec1 = document.querySelectorAll('.s_specific_sid1')
        const spec2 = document.querySelectorAll('.s_specific_sid2')
        const specDet1 = document.querySelectorAll('.s_specific_detail_sid1')
        const specDet2 = document.querySelectorAll('.s_specific_detail_sid2')
        const obj = {};

        for (let i = 0, max = spec1.length; i < max; i++) {
            const selspec1 = `${spec1[i].value}-${specDet1[i].value}-${spec2[i].value}-${specDet2[i].value}`
            const selspec2 = `${spec2[i].value}-${specDet2[i].value}-${spec1[i].value}-${specDet1[i].value}`
            if (obj[selspec1] == undefined) {
                obj[selspec1] = i;
                obj[selspec2] = i;
            } else {
                spec1[obj[selspec1]].style.border = '1px solid red';
                specDet1[obj[selspec1]].style.border = '1px solid red';
                spec2[obj[selspec1]].style.border = '1px solid red';
                specDet2[obj[selspec1]].style.border = '1px solid red';
                spec1[obj[selspec1]].closest('.mb-3').lastChild.textContent = '* 規格重複請修正';
                spec2[obj[selspec1]].closest('.mb-3').lastChild.textContent = '* 規格重複請修正';

                spec1[i].style.border = '1px solid red';
                specDet1[i].style.border = '1px solid red';
                spec2[i].style.border = '1px solid red';
                specDet2[i].style.border = '1px solid red';
                spec1[i].closest('.mb-3').lastChild.textContent = '* 規格重複請修正';
                spec2[i].closest('.mb-3').lastChild.textContent = '* 規格重複請修正';

                console.log('有重複')
            }
        }

        if (isPass) {
            const fd = new FormData(document.s_Form3);
            fetch('s_edit-api_update.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                .then(obj => {
                    if (obj.success) {
                        infoBar.innerText = '編輯成功';
                        infoBar.classList.remove('alert-danger');
                        infoBar.classList.add('alert-success');
                        infoBar.style.display = 'block';
                        setTimeout(() => {
                            location.href = 's_list_update.php'
                        }, 2000)
                    } else {
                        infoBar.innerText = '資料沒有編輯';
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
                    infoBar.innerText = '編輯發生錯誤，請通知後端人員';
                    infoBar.classList.add('alert-danger');
                    infoBar.classList.remove('alert-success');
                    infoBar.style.display = 'block'
                    setTimeout(() => {
                        infoBar.style.display = 'none'
                    }, 2000)
                })
        }
    }


    //==================  +/-的事件監聽==================
    const theProDetBox = document.querySelector('#s_proDetBox')
    theProDetBox.addEventListener('click', (event) => {
        const target = event.target;
        if (target.classList.contains('s_add')) {
            const parentCol = event.target.parentNode;
            const index = Array.from(parentCol.parentNode.children).indexOf(parentCol);
            const theCopy = `<div class="col-3 mb-3">
            <div class="mb-3 s_proDetNum">
                        <input type="text" class="form-control" name="product_detail_sid[]" value="${index+2}">
                    </div>
                    <div class="mb-3">
                        <div class="s_ImgBox s_proDetImgBox" ><img src="" id="s_imginfo">+</div>
                        <input type="text" name="img1[]" class="s_proDetImg">
                    </div>
                    <div class="mb-3 s_spec">
                        <label class="form-label">規格一</label>
                        <div class="row">
                            <div class="col-6">
                                <select class="form-select s_specific_sid1" name="specific_sid1[]" data-required="1">
                                    <option value="" selected disabled>--請選擇--</option>
                                    <?php foreach ($r_shopSpec as $r) : ?>
                                        <option value="<?= $r['specific_sid'] ?>"><?= $r['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select s_specific_detail_sid1" name="specific_detail_sid1[]" disabled></select>
                            </div>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3 s_spec">
                        <label class="form-label">規格二</label>
                        <div class="row">
                            <div class="col-6">
                                <select class="form-select s_specific_sid2" name="specific_sid2[]" disabled> <option value="" selected disabled>--請選擇--</option></select>
                            </div>
                            <div class="col-6">
                                <select class="form-select s_specific_detail_sid2" name="specific_detail_sid2[]" disabled></select>
                            </div>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s_price">價格</label>
                        <input type="number" class="form-control" name="price[]" data-required="1">
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s_qty">數量</label>
                        <input type="number" class="form-control" name="qty[]" data-required="1">
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">適用年齡</label>
                        <div class="d-flex">
                            <select class="form-select" name="for_age[]" data-required="1">
                                <option value="" selected disabled>--請選擇--</option>
                                <option value="1">幼年</option>
                                <option value="2">成年</option>
                                <option value="3">高齡</option>
                                <option value="4">皆可</option>
                            </select>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <button type="button" class="btn btn-success s_add">+</button>
                    <button type="button" class="btn btn-danger s_del">-</button>
                </div>`
            theProDetBox.insertAdjacentHTML("beforeend", theCopy)


            createProDetBox()
            createproDetImgBox(index + 1)
            resetRemind()
        }
        if (target.classList.contains('s_del')) {
            console.log("del")
            event.target.closest('.col-3').remove()
        }

        //console.log(target.parentNode.classList.contains('s_proDetImgBox'));
        if (target.parentNode.classList.contains('s_proDetImgBox')) {
            const theBox = target.closest('.col-3');
            const index = Array.from(theProDetBox.children).indexOf(theBox);
            const proDetImg1 = document.querySelectorAll('.s_tepProPetImg')
            console.log(index)
            proDetImg1[index].click()

            //新增細項照片+API
            proDetImg1[index].addEventListener("change", (event) => {
                //console.log(event.target)
                const formName = event.target.parentNode;
                //console.log(formName)
                const fd = new FormData(formName);
                fetch('s_upLoadProImg-api.php', {
                        method: 'POST',
                        body: fd,
                    }).then(r => r.json())
                    .then(obj => {
                        if (obj.filename) {
                            //console.log(index)
                            const s_proDetImgBox = document.querySelectorAll('.s_proDetImgBox');
                            const s_proDetImg = document.querySelectorAll('.s_proDetImg');
                            // s_proImgBox.innerText = "";
                            s_proDetImgBox[index].firstChild.src = `./s_Imgs/${obj.filename}`;
                            s_proDetImgBox[index].firstChild.style.display = "block";
                            s_proDetImg[index].value = obj.filename;
                        }
                    })
            })
        }
    })

    document.addEventListener('DOMContentLoaded', () => {
        let buttons = document.querySelectorAll('.s_del')
        buttons[0].style.display = "none";
    })

    //新增主照片+API
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
                //console.log(obj)
                if (obj.filename) {
                    const s_proImgBox = document.querySelector('#s_proImgBox');
                    const s_proImg = document.querySelector('#s_proImg');
                    // s_proImgBox.innerText = "";
                    s_proImgBox.firstChild.src = `./s_Imgs/${obj.filename}`;
                    s_proImgBox.firstChild.style.display = "block";
                    s_proImg.value = obj.filename;
                }
            }).catch(ex => {
                console.log(ex)
            })
    })

    //==================清除結點==================
    function removeChild(a) {
        while (a.hasChildNodes()) {
            a.remove(a.lastChild)
        }
    }
    //==================新增option==================
    function createOp(a, b, c) {
        const theOp = document.createElement(a);
        theOp.setAttribute("value", b);
        const theTxt = document.createTextNode(c);
        theOp.append(theTxt);
        theDocFrag.append(theOp);
        return theDocFrag
    }

    //==================產品子類別自動生成==================
    const catSel = document.querySelector("#s_category_sid");
    catSel.addEventListener("change", () => {
        const catSelId = catSel.value
        createCatDet(catSelId)
    });

    function createCatDet(catSelId) {
        const catDet = <?= json_encode($r_shopCatDet, JSON_UNESCAPED_UNICODE) ?>;
        const catDetSel = document.querySelector('#s_category_detail_sid')
        catDetSel.innerHTML = "";
        // catDetSel.removeAttribute('disabled')
        for (let a of catDet) {
            if (catSelId == a.category_sid) {
                createOp('option', a.category_detail_sid, a.detail_name)
            }
        }
        catDetSel.append(theDocFrag)
    }

    //==================供應商產地自動生成==================
    const supSel = document.querySelector('#s_supplier_sid')
    supSel.addEventListener('change', () => {
        const supSelId = supSel.value;
        createSupMIW(supSelId)
    })

    function createSupMIW(supSelId) {
        const supMIW = <?= json_encode($r_shopSupMIW, JSON_UNESCAPED_UNICODE) ?>;
        const supMIWSel = document.querySelector('#s_made_in_where')
        // supMIWSel.removeAttribute('disabled')
        supMIWSel.innerHTML = ""
        for (let a of supMIW) {
            if (supSelId == a.supplier_sid) {
                createOp('option', a.made_in_where, a.made_in_where)
            }
        }
        supMIWSel.append(theDocFrag)
    }
    //====================================


    //==================商品規格自動生成==================
    function createProDetBox(k) {
        const spec = <?= json_encode($r_shopSpec, JSON_UNESCAPED_UNICODE) ?>;
        const specDet = <?= json_encode($r_shopSpecDet, JSON_UNESCAPED_UNICODE) ?>;
        const specSel1 = document.querySelectorAll('.s_specific_sid1')
        const specDetSel1 = document.querySelectorAll(".s_specific_detail_sid1")
        const specSel2 = document.querySelectorAll('.s_specific_sid2')
        const specDetSel2 = document.querySelectorAll(".s_specific_detail_sid2")
        console.log(specSel2)
        for (let a = 0, amax = specSel1.length; a < amax; a++) {
            specSel1[a].addEventListener('change', () => {
                const specSelId = specSel1[a].value;
                createSpecDet1(specSelId, a);
                createSpec2(specSelId, a);
                specDetSel2[a].innerHTML = ""
                // while (specDetSel2[a].hasChildNodes()) {
                //     specDetSel2[a].remove(specDetSel2[a].lastChild)
                // }
                console.log(a)
                console.log(specSelId)
            })
        }

        for (let c = 0, cmax = specSel2.length; c < cmax; c++) {
            specSel2[c].addEventListener('change', () => {
                const specSelId = specSel2[c].value;
                createSpecDet2(specSelId, c);
            })
        }

        function createSpec2(specSelId, a) {
            //console.log(123)
            specSel2[a].removeAttribute('disabled')
            specSel2[a].innerHTML = ""
            // while (specSel2[a].hasChildNodes()) {
            //     specSel2[a].remove(specSel2[a].lastChild)
            // }
            const theOp = document.createElement('option');
            theOp.setAttribute("value", "");
            const theTxt = document.createTextNode('--請選擇--')
            theOp.append(theTxt)
            theDocFrag.append(theOp);
            for (let b of spec) {
                if (b.specific_sid != specSelId) {
                    createOp('option', b.specific_sid, b.name)
                }
            }
            specSel2[a].append(theDocFrag)
            for (let c = 0, cmax = specSel2.length; c < cmax; c++) {
                specSel2[c].addEventListener('change', () => {
                    const specSelId = specSel2[c].value;
                    createSpecDet2(specSelId, c);
                })
            }
        }

        function createSpecDet2(specSelId, index) {
            specDetSel2[index].removeAttribute('disabled');
            specDetSel2[index].innerHTML = ""
            // removeChild(specDetSel2[index])
            createSpecDet(specSelId)
            specDetSel2[index].append(theDocFrag)
            console.log(specDetSel2[index])
        }

        function createSpecDet1(specSelId, index) {
            specDetSel1[index].removeAttribute('disabled');
            specDetSel1[index].innerHTML = ""
            // removeChild(specDetSel1[index])
            createSpecDet(specSelId)
            specDetSel1[index].append(theDocFrag)
        }

        function createSpecDet(specSelId) {
            const arr = [];
            for (let a of specDet) {
                if (specSelId == a.specific_sid) {
                    arr.push(a);
                }
            }
            //若小規格有數字，則由小到大排序
            arr.sort(function(a, b) {
                let c = parseInt(a.detail_name);
                let d = parseInt(b.detail_name);
                return c - d
            })
            for (let b of arr) {
                createOp('option', b.specific_detail_sid, b.detail_name)
            }
        }
    }
    createProDetBox()

    //==================自動生成各項目照片框==================
    function createproDetImgBox(a) {
        const proDetImg = document.querySelector('#s_proDetTepImgBox')
        const formName = `s_Form2${a}`
        const theForm = document.createElement('form')
        theForm.setAttribute('name', formName)
        theForm.classList.add('s_Form', 's_detImg')
        const theInput = ` <input type="file" name="shopTepProImg" accept="image/jpeg" class="s_tepProPetImg">`
        theForm.innerHTML = theInput
        proDetImg.append(theForm)
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>