<?php
require './partsNOEDIT/connect-db.php';

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
    #infoBar {
        /* display: none; */
        color: pink
    }

    #s_imginfo {
        padding: 5px;
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: none;
        position: absolute;
    }

    .s_proDetImgBox {
        height: 190px;
        border: 1px dashed lightgray;
    }
</style>

<?php include './partsNOEDIT/navbar.php' ?>
<div class="row">
    <div class="col-1"></div>
    <div class="col">
        <form name="s_Form1" class="s_Form1">
            <input type="file" name="shopTepProImg" accept="image/jpeg" id="s_tepProImg">
        </form>
        <div id="s_proDetTepImgBox"></div>

        <form class="pt-4" name="s_Form3" onsubmit="checkForm(event)">
            <h2>新增商品</h2>
            <div class="row pb-3 border-bottom">
                <div class="col-5">
                    <div class="w-100 s_ImgBox" onclick="shopAddMainImg()" id="s_proImgBox"><img src="" id="s_imginfo">+</div>
                    <input type="text" name="pro_img" id="s_proImg">
                </div>
                <div class="col-7">
                    <div class="mb-3">
                        <label class="form-label" for="pro_name">產品名稱</label>
                        <input type="text" class="form-control" id="pro_name" name="pro_name">
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">適用對象</label>
                        <div class="d-flex">
                            <select class="form-select" name="pro_for" id="shopForSel">
                                <option value="" selected disabled>--請選擇--</option>
                                <option value="D">狗</option>
                                <option value="C">貓</option>
                                <option value="B">皆可</option>
                            </select>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s_sup_sid">產品類別</label>
                        <div class="row">
                            <div class="col-6">
                                <select class="form-select" name="cat_sid" id="s_cat_sid">
                                    <option value="" selected disabled>--請選擇--</option>
                                    <?php foreach ($r_shopCat as $r) : ?>
                                        <option value="<?= $r['cat_sid'] ?>"><?= $r['cat_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select" name="catDet_sid" id="s_catDet_sid" disabled></select>
                            </div>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s_sup_sid">供應商</label>
                        <div class="row">
                            <div class="col-6">
                                <select class="form-select" name="sup_sid" id="s_sup_sid">
                                    <option value="" selected disabled>--請選擇--</option>
                                    <?php foreach ($r_shopSup as $r) : ?>
                                        <option value="<?= $r['sup_sid'] ?>"><?= $r['sup_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select" name="sup_MIW" id="s_sup_MIW" disabled></select>
                            </div>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label for="pro_describe" class="form-label">產品描述</label>
                        <textarea name="pro_describe" id="pro_describe" class="form-control"></textarea>
                        <div class="form-text"></div>
                    </div>
                </div>
            </div>
            <div class="row pb-3 border-bottom mt-4" id="s_proDetBox">
                <div class="col-3 mb-3">
                    <div class="mb-3 s_proDetNum">
                        <input type="text" class="form-control " name="proDet_sid[]" value="1">
                    </div>
                    <div class="mb-3">
                        <div class="s_ImgBox s_proDetImgBox"><img src="" id="s_imginfo">+</div>
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
                        <input type="number" class="form-control" name="proDet_price[]">
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s_proDet_qty">數量</label>
                        <input type="number" class="form-control" name="proDet_qty[]">
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
                    <button type="button" class="btn btn-danger s_del d-none">-</button>
                </div>
            </div>
            <div class="alert alert-danger" id="infoBar" role="alert"></div>
            <button type="submit" class="btn btn-primary">新增商品</button>
        </form>



    </div>
    <div class="col-1"></div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    const theDocFrag = document.createDocumentFragment();

    function checkForm(event) {
        event.preventDefault()
        let isPass = true;
        const fd = new FormData(document.s_Form3);
        fetch('s_proAdd-api.php', {
                method: 'POST',
                body: fd,
            }).then(r => r.json())
            .then(obj => {
                if (obj.success) {
                    infoBar.innerText = '新增成功';
                    infoBar.classList.remove('alert-danger');
                    infoBar.classList.add('alert-success');
                    infoBar.style.display = 'block'
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


    //==================  +/-的事件監聽==================
    const theProDetBox = document.querySelector('#s_proDetBox')
    theProDetBox.addEventListener('click', (event) => {
        const target = event.target
        //console.log(event)
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
                        <input type="number" class="form-control" name="proDet_price[]">
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s_proDet_qty">數量</label>
                        <input type="number" class="form-control" name="proDet_qty[]">
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
            event.target.closest('.col-3').remove()
        }


        if (target.classList.contains('s_proDetImgBox')) {
            const clickedItem = event.target.parentNode.parentNode.parentNode;
            const index = Array.from(clickedItem.children).indexOf(event.target.parentNode.parentNode);
            //console.log(index)
            const proDetImg1 = document.querySelectorAll('.s_tepProPetImg')
            proDetImg1[index].click()

            //新增細項照片+API
            proDetImg1[index].addEventListener("change", (event) => {
                const formName = event.target.parentNode;
                //console.log(formName)
                const fd = new FormData(formName);
                fetch('s_upLoadProImg-api.php', {
                        method: 'POST',
                        body: fd,
                    }).then(r => r.json())
                    .then(obj => {
                        if (obj.filename) {
                            console.log(index)
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
        removeChild(catDetSel)
        catDetSel.removeAttribute('disabled')
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
        supMIWSel.removeAttribute('disabled')
        removeChild(supMIWSel)
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
                while (specDetSel2[a].hasChildNodes()) {
                    specDetSel2[a].remove(specDetSel2[a].lastChild)
                }
            })
        }

        function createSpec2(specSelId, a) {
            specSel2[a].removeAttribute('disabled')
            while (specSel2[a].hasChildNodes()) {
                specSel2[a].remove(specSel2[a].lastChild)
            }
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
            removeChild(specDetSel2[index])
            createSpecDet(specSelId)
            specDetSel2[index].append(theDocFrag)
        }

        function createSpecDet1(specSelId, index) {
            specDetSel1[index].removeAttribute('disabled');
            removeChild(specDetSel1[index])
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
    createproDetImgBox(0)
</script>
<?php include './partsNOEDIT/html-foot.php' ?>