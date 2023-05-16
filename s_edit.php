<?php
require './partsNOEDIT/connect-db.php';

$proDet_sid = isset($_GET['proDet_sid']) ? $_GET['proDet_sid'] : '';
$pro_sid = isset($_GET['pro_sid']) ? $_GET['pro_sid'] : '';

//主商品資料
$sql_shopInfo = sprintf("SELECT * FROM `shop_pro` WHERE `pro_sid`='%s'", $pro_sid);
$r_shopInfo = $pdo->query($sql_shopInfo)->fetch();


//細項商品資料
$sql_shopSpecInfo = sprintf("SELECT * FROM `shop_prospec` ps
JOIN `shop_prodet` pd ON ps.`prodDet_sid`=pd.`proDet_sid` AND ps.`prod_sid`=pd.`pro_sid` WHERE ps.`prod_sid`='%s'", $pro_sid);
$r_shopSpecInfo = $pdo->query($sql_shopSpecInfo)->fetchAll();

$r_shopSpecInfo2 = [];

foreach ($r_shopSpecInfo as $item) {
    $key = $item['prodDet_sid'];

    if (!isset($r_shopSpecInfo2[$key])) {
        $r_shopSpecInfo2[$key] = [
            'prod_sid' => $item['prod_sid'],
            'prodDet_sid' => $item['prodDet_sid'],
            'proDet_name' => $item['proDet_name'],
            'proDet_price' => $item['proDet_price'],
            'proDet_qty' => $item['proDet_qty'],
            'proDet_img' => $item['proDet_img'],
            'pro_forAge' => $item['pro_forAge'],
            'spec_sid' => [$item['spec_sid']],
            'specDet_sid' => [$item['specDet_sid']]
        ];
    } else {
        $r_shopSpecInfo2[$key]['spec_sid'][] = $item['spec_sid'];
        $r_shopSpecInfo2[$key]['specDet_sid'][] = $item['specDet_sid'];
    }
}

$r_shopSpecInfo2 = array_values($r_shopSpecInfo2);

//下拉的大類別列表
$sql_shopCat = "SELECT distinct `cat_sid`, `cat_name` FROM `shop_cat` ORDER BY `cat_name`";
$r_shopCat = $pdo->query($sql_shopCat)->fetchAll();

//下拉的子類別列表
$sql_shopCatDet = "SELECT * FROM `shop_cat` ORDER BY `catDet_num` ";
$r_shopCatDet = $pdo->query($sql_shopCatDet)->fetchAll();

//下拉的供應商列表
$sql_shopSup = "SELECT distinct `sup_sid`, `sup_name` FROM `shop_sup` ORDER BY `sup_name`";
$r_shopSup = $pdo->query($sql_shopSup)->fetchAll();

//下拉的產地列表
$sql_shopSupMIW = "SELECT * FROM `shop_sup`";
$r_shopSupMIW = $pdo->query($sql_shopSupMIW)->fetchAll();

//下拉的規格類別料表
$sql_shopSpec = "SELECT distinct `spec_sid`, `spec_name` FROM `shop_spec` ORDER BY `spec_sid`";
$r_shopSpec = $pdo->query($sql_shopSpec)->fetchAll();

//下拉的各規格明細料表

$sql_shopSpecDet = "SELECT * FROM `shop_spec`";
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
    #s_pro_sid,
    #s_proOnWeb {
        display: none;
        color: pink
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
        border: 1px dashed lightgray;
    }

    .s_allbtn {
        display: flex;
        justify-content: end;
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
                <form name="s_Form2<?= intval($v['prodDet_sid']) ?>" class="s_Form1">
                    <input type="file" name="shopTepProImg" accept="image/jpeg" class="s_tepProPetImg">
                </form>
            <?php endforeach; ?>
        </div>

        <form class="pt-4 pb-4" name="s_Form3" onsubmit="checkForm(event)">
            <div class="d-flex">
                <h2 class="me-auto">新增商品</h2>
                <div class="d-flex align-items-end">
                    <div class="form-check me-3">
                        <input class="form-check-input" type="radio" name="pro_status" id="on" value="1" <?= $r_shopInfo['pro_status'] == 1 ? "checked" : "" ?>>
                        <label class="form-check-label" for="on">
                            上架
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pro_status" id="off" value="2" <?= $r_shopInfo['pro_status'] == 2 ? "checked" : "" ?>>
                        <label class="form-check-label" for="off">
                            下架
                        </label>
                    </div>
                </div>
            </div>
            <div id="s_proOnWeb"><input type="text" name="pro_onWeb" value="<?= $r_shopInfo['pro_onWeb'] ?>"></div>
            <div class="row pb-3 border-bottom">
                <div class="col-5">
                    <div class="w-100 s_ImgBox" onclick="shopAddMainImg()" id="s_proImgBox"><img src="./s_Imgs/<?= $r_shopInfo['pro_img'] ?>" id="s_imginfo">+</div>
                    <input type="text" name="pro_img" id="s_proImg" value=<?= $r_shopInfo['pro_img'] ?>>
                </div>
                <div class="col-7">
                    <div class="mb-3" id="s_pro_sid">
                        <label class="form-label" for="pro_sid">產品編號</label>
                        <input type="text" class="form-control" name="pro_sid" value=<?= $r_shopInfo['pro_sid'] ?>>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="pro_name">產品名稱</label>
                        <input type="text" class="form-control" id="pro_name" name="pro_name" data-required="1" value=<?= htmlentities($r_shopInfo['pro_name']) ?>>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">適用對象</label>
                        <div class="d-flex">
                            <select class="form-select" name="pro_for" id="shopForSel">
                                <option value="" disabled>--請選擇--</option>
                                <option value="D" <?= $r_shopInfo['pro_for'] == 'D' ? "selected" : "" ?>>狗</option>
                                <option value="C" <?= $r_shopInfo['pro_for'] == 'C' ? "selected" : "" ?>>貓</option>
                                <option value="B" <?= $r_shopInfo['pro_for'] == 'B' ? "selected" : "" ?>>皆可</option>
                            </select>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s_sup_sid">產品類別</label>
                        <div class="row">
                            <div class="col-6">
                                <select class="form-select" name="cat_sid" id="s_cat_sid">
                                    <option value="" disabled>--請選擇--</option>
                                    <?php foreach ($r_shopCat as $r) : ?>
                                        <option value="<?= $r['cat_sid'] ?>" <?= $r_shopInfo['cat_sid'] == $r['cat_sid'] ? "selected" : "" ?>><?= $r['cat_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select" name="catDet_sid" id="s_catDet_sid">
                                    <?php foreach ($r_shopCatDet as $r) : ?>
                                        <?php if ($r['cat_sid'] == $r_shopInfo['cat_sid']) : ?>
                                            <option value="<?= $r['catDet_sid'] ?>" <?= $r_shopInfo['catDet_sid'] == $r['catDet_sid'] ? "selected" : "" ?>><?= $r['catDet_name'] ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s_sup_sid">供應商</label>
                        <div class="row">
                            <div class="col-6">
                                <select class="form-select" name="sup_sid" id="s_sup_sid">
                                    <option value="" disabled>--請選擇--</option>
                                    <?php foreach ($r_shopSup as $r) : ?>
                                        <option value="<?= $r['sup_sid'] ?>" <?= $r_shopInfo['sup_sid'] == $r['sup_sid'] ? "selected" : "" ?>><?= $r['sup_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select" name="sup_MIW" id="s_sup_MIW">
                                    <?php foreach ($r_shopSupMIW as $r) : ?>
                                        <?php if ($r['sup_sid'] == $r_shopInfo['sup_sid']) : ?>
                                            <option value="<?= $r['sup_sid'] ?>" <?= $r_shopInfo['sup_sid'] == $r['sup_sid'] ? "selected" : "" ?>><?= $r['sup_MIW'] ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label for="pro_describe" class="form-label">產品描述</label>
                        <textarea name="pro_describe" id="pro_describe" class="form-control" data-required="1"><?= htmlentities($r_shopInfo['pro_describe']) ?></textarea>
                        <div class="form-text"></div>
                    </div>
                </div>
            </div>
            <div class="row pb-3 border-bottom mt-4" id="s_proDetBox">
                <?php foreach ($r_shopSpecInfo2 as $k => $v) : ?>
                    <div class="col-3 mb-3">
                        <div class="mb-3 s_proDetNum">
                            <input type="text" class="form-control " name="proDet_sid[]" value="<?= intval($v['prodDet_sid']) ?>">
                        </div>
                        <div class="mb-3">
                            <div class="s_ImgBox s_proDetImgBox"><img <?= $v['proDet_img'] == "" ? "" : sprintf("src='./s_imgs/%s'", $v['proDet_img']) ?> id="s_imginfo">+</div>
                            <input type="text" name="pro_img1[]" class="s_proDetImg" value=<?= $v['proDet_img'] ?>>
                        </div>
                        <div class="mb-3 s_spec">
                            <label class="form-label">規格一</label>
                            <div class="row">
                                <div class="col-6">
                                    <select class="form-select s_spec_sid1" name="spec_sid1[]">
                                        <option value="" disabled>--請選擇--</option>
                                        <?php foreach ($r_shopSpec as $r) : ?>
                                            <option value="<?= $r['spec_sid'] ?>" <?= $r['spec_sid'] == $v['spec_sid'][0] ? "selected" : "" ?>><?= $r['spec_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select class="form-select s_specDet_sid1" name="specDet_sid1[]">
                                        <?php foreach ($r_shopSpecDet as $r) : ?>
                                            <?php if ($r['spec_sid'] == $v['spec_sid'][0]) : ?>
                                                <option value="<?= $r['specDet_sid'] ?>" <?= $r['specDet_sid'] == $v['specDet_sid'][0] ? "selected" : "" ?>><?= $r['specDet_name'] ?></option>
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
                                    <select class="form-select s_spec_sid2" name="spec_sid2[]">
                                        <?php foreach ($r_shopSpec as $r) : ?>
                                            <option value="<?= $r['spec_sid'] ?>" <?= $r['spec_sid'] == $v['spec_sid'][1] ? "selected" : "" ?>><?= $r['spec_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select class="form-select s_specDet_sid2" name="specDet_sid2[]">
                                        <?php foreach ($r_shopSpecDet as $r) : ?>
                                            <?php if ($r['spec_sid'] == $v['spec_sid'][1]) : ?>
                                                <option value="<?= $r['specDet_sid'] ?>" <?= $r['specDet_sid'] == $v['specDet_sid'][1] ? "selected" : "" ?>><?= $r['specDet_name'] ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="s_proDet_price">價格</label>
                            <input type="number" class="form-control" name="proDet_price[]" data-required="1" value=<?= $v['proDet_price'] ?>>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="s_proDet_qty">數量</label>
                            <input type="number" class="form-control" name="proDet_qty[]" data-required="1" value=<?= $v['proDet_qty'] ?>>
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">適用年齡</label>
                            <div class="d-flex">
                                <select class="form-select" name="pro_forAge[]">
                                    <option value="" selected disabled>--請選擇--</option>
                                    <option value="1" <?= $v['pro_forAge'] == 1 ? "selected" : "" ?>>幼年</option>
                                    <option value="2" <?= $v['pro_forAge'] == 2 ? "selected" : "" ?>>成年</option>
                                    <option value="3" <?= $v['pro_forAge'] == 3 ? "selected" : "" ?>>高齡</option>
                                    <option value="4" <?= $v['pro_forAge'] == 4 ? "selected" : "" ?>>皆可</option>
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
            <div class="s_allbtn">
                <button type="button" class="btn btn-secondary s_allcancel" onclick="allcancel">取消編輯</button>
                <button type="button" class="btn btn-warning ms-3 s_alledit">確認編輯</button>
                <button type="button" class="btn btn-danger ms-3 s_alldel">整筆刪除</button>
            </div>
        </form>
    </div>
    <div class="col-1"></div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    const theDocFrag = document.createDocumentFragment();

    function allcancel() {

    }


    function checkForm(event) {
        event.preventDefault()
        const filedType = document.querySelectorAll('form [data-required="1"]')

        let isPass = true;

        //恢復所有欄位的外觀
        for (let f of filedType) {
            f.style.border = '1px solid #CCC';
            f.nextElementSibling.innerText = '';
        }

        //部分欄位皆必填
        for (let f of filedType) {
            if (!f.value) {
                isPass = false;
                f.style.border = '1px solid red';
                f.nextElementSibling.innerText = '請輸入資料';
            }
        }

        if (isPass) {
            const fd = new FormData(document.s_Form3);
            fetch('s_edit-api.php', {
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
                            location.href = './s_list.php'
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
                        <input type="text" class="form-control" name="proDet_sid[]" value="${index+2}">
                    </div>
                    <div class="mb-3">
                        <div class="s_ImgBox s_proDetImgBox" ><img src="" id="s_imginfo">+</div>
                        <input type="text" name="pro_img1[]" class="s_proDetImg">
                    </div>
                    <div class="mb-3 s_spec">
                        <label class="form-label">規格一</label>
                        <div class="row">
                            <div class="col-6">
                                <select class="form-select s_spec_sid1" name="spec_sid1[]">
                                    <option value="" selected disabled>--請選擇--</option>
                                    <?php foreach ($r_shopSpec as $r) : ?>
                                        <option value="<?= $r['spec_sid'] ?>"><?= $r['spec_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select s_specDet_sid1" name="specDet_sid1[]" disabled></select>
                            </div>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3 s_spec">
                        <label class="form-label">規格二</label>
                        <div class="row">
                            <div class="col-6">
                                <select class="form-select s_spec_sid2" name="spec_sid2[]" disabled></select>
                            </div>
                            <div class="col-6">
                                <select class="form-select s_specDet_sid2" name="specDet_sid2[]" disabled></select>
                            </div>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s_proDet_price">價格</label>
                        <input type="number" class="form-control" name="proDet_price[]" data-required="1">
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s_proDet_qty">數量</label>
                        <input type="number" class="form-control" name="proDet_qty[]" data-required="1">
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">適用年齡</label>
                        <div class="d-flex">
                            <select class="form-select" name="pro_forAge[]">
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
    const catSel = document.querySelector("#s_cat_sid");
    catSel.addEventListener("change", () => {
        const catSelId = catSel.value
        createCatDet(catSelId)
    });

    function createCatDet(catSelId) {
        const catDet = <?= json_encode($r_shopCatDet, JSON_UNESCAPED_UNICODE) ?>;
        const catDetSel = document.querySelector('#s_catDet_sid')
        catDetSel.innerHTML = "";
        // catDetSel.removeAttribute('disabled')
        for (let a of catDet) {
            if (catSelId == a.cat_sid) {
                createOp('option', a.catDet_sid, a.catDet_name)
            }
        }
        catDetSel.append(theDocFrag)
    }

    //==================供應商產地自動生成==================
    const supSel = document.querySelector('#s_sup_sid')
    supSel.addEventListener('change', () => {
        const supSelId = supSel.value;
        createSupMIW(supSelId)
    })

    function createSupMIW(supSelId) {
        const supMIW = <?= json_encode($r_shopSupMIW, JSON_UNESCAPED_UNICODE) ?>;
        const supMIWSel = document.querySelector('#s_sup_MIW')
        // supMIWSel.removeAttribute('disabled')
        supMIWSel.innerHTML = ""
        for (let a of supMIW) {
            if (supSelId == a.sup_sid) {
                createOp('option', a.sup_MIW_sid, a.sup_MIW)
            }
        }
        supMIWSel.append(theDocFrag)
    }
    //====================================

    //==================商品規格自動生成==================
    function createProDetBox(k) {
        const spec = <?= json_encode($r_shopSpec, JSON_UNESCAPED_UNICODE) ?>;
        const specDet = <?= json_encode($r_shopSpecDet, JSON_UNESCAPED_UNICODE) ?>;
        const specSel1 = document.querySelectorAll('.s_spec_sid1')
        const specDetSel1 = document.querySelectorAll(".s_specDet_sid1")
        const specSel2 = document.querySelectorAll('.s_spec_sid2')
        const specDetSel2 = document.querySelectorAll(".s_specDet_sid2")

        for (let a = 0, amax = specSel1.length; a < amax; a++) {
            specSel1[a].addEventListener('change', () => {
                const specSelId = specSel1[a].value;
                createSpecDet1(specSelId, a);
                createSpec2(specSelId, a);
                specDetSel2[a].innerHTML = ""
                // while (specDetSel2[a].hasChildNodes()) {
                //     specDetSel2[a].remove(specDetSel2[a].lastChild)
                // }
            })
        }

        function createSpec2(specSelId, a) {
            specSel2[a].removeAttribute('disabled')
            specSel2[a].innerHTML = ""
            // while (specSel2[a].hasChildNodes()) {
            //     specSel2[a].remove(specSel2[a].lastChild)
            // }
            for (let b of spec) {
                if (b.spec_sid != specSelId) {
                    createOp('option', b.spec_sid, b.spec_name)
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
                if (specSelId == a.spec_sid) {
                    arr.push(a);
                }
            }
            //若小規格有數字，則由小到大排序
            arr.sort(function(a, b) {
                let c = parseInt(a.specDet_name);
                let d = parseInt(b.specDet_name);
                return c - d
            })
            for (let b of arr) {
                createOp('option', b.specDet_sid, b.specDet_name)
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