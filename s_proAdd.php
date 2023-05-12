<?php
require './partsNOEDIT/connect-db.php';

//下拉的大類別列表
$sql_shopCat = "SELECT distinct `cat_sid`, `cat_name` FROM `shop_cat` ORDER BY `cat_name`";
$r_shopCat = $pdo->query($sql_shopCat)->fetchAll();

//下拉的供應商列表
$sql_shopSup = "SELECT distinct `sup_sid`, `sup_name` FROM `shop_sup` ORDER BY `sup_name`";
$r_shopSup = $pdo->query($sql_shopSup)->fetchAll();

//下拉的規格料表
$sql_shopSpec = "SELECT distinct `spec_sid`, `spec_name` FROM `shop_spec` ORDER BY `spec_sid`";
$r_shopSpec = $pdo->query($sql_shopSpec)->fetchAll();
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

    #s_Form1,
    #s_proImg {
        display: none;
    }

    #s_imginfo {
        padding: 5px;
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: none;
        position: absolute;
    }


    #s_proDetImgBox {
        height: 190px;
        border: 1px dashed lightgray;
    }
</style>

<?php include './partsNOEDIT/navbar.php' ?>
<div class="row">
    <div class="col-1"></div>
    <div class="col">
        <form name="s_Form1" id="s_Form1">
            <input type="file" name="shopTepProImg" accept="image/jpeg" id="shopTepProImg">
        </form>
        <form class="pt-4" name="s_Form2" return flase>
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
                                <select class="form-select" name="cat_sid" id="shopCatSel">
                                    <?php foreach ($r_shopCat as $r) : ?>
                                        <option value="<?= $r['cat_sid'] ?>"><?= $r['cat_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select" name="catDet_sid" id="shopCatDetSel"></select>
                            </div>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s_sup_sid">供應商</label>
                        <div class="row">
                            <div class="col-6">
                                <select class="form-select" name="sup_sid" id="s_sup_sid">
                                    <?php foreach ($r_shopSup as $r) : ?>
                                        <option value="<?= $r['sup_sid'] ?>"><?= $r['sup_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select" name="sup_MIW" id="s_sup_MIW"></select>
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
            <div class="row pb-3 border-bottom mt-4">
                <div class="col-3 mb-3">
                    <!-- 要更改function -->
                    <div>
                        <div class="s_ImgBox" onclick="shopAddMainImg()" id="s_proDetImgBox"><img src="" id="s_imginfo">+</div>
                        <input type="text" name="pro_img" id="s_proImg">
                    </div>
                    <div class="mt-3">
                        <label class="form-label">規格一</label>
                        <div class="row s_spec">
                            <div class="col-6">
                                <select class="form-select s_spec_sid" name="spec_sid1[]">
                                    <?php foreach ($r_shopSpec as $r) : ?>
                                        <option value="<?= $r['spec_sid'] ?>"><?= $r['spec_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <script>
                                const shopSpecSel = document.querySelectorAll('.s_spec_sid');
                                const shopSpec = document.querySelectorAll('.s_spec');
                                for (let a = 0, amax = shopSpecSel.length; a < amax; a++) {
                                    shopSpecSel[a].addEventListener('change', () => {
                                        const selS = shopSpecSel[a].value;
                                        SpecSel(selS, a);
                                    });
                                }

                                function SpecSel(speSel, a) {
                                    fetch(`s_proSep-api.php?spec_sid1=${speSel}`)
                                        .then(r => r.json())
                                        .then(obj => {


                                            console.log(obj)
                                            const thedocFrag = document.createDocumentFragment();
                                            const theDiv = document.createElement("div");
                                            theDiv.classList.add('col-6');
                                            const theSel = document.createElement('select');
                                            theSel.classList.add('form-select', 's_specDet_sid');
                                            theSel.setAttribute("name", "specDet_sid1[]");
                                            for (let b = 0, bmax = obj.length; b < bmax; b++) {

                                                theOpt = document.createElement('option');
                                                theOpt.setAttribute("value", obj[b].specDet_sid);
                                                theTxt = document.createTextNode(obj[b].specDet_name);
                                                theOpt.append(theTxt);
                                                thedocFrag.append(theOpt);
                                            }
                                            theSel.append(thedocFrag);
                                            theDiv.append(theSel);
                                            shopSpec[a].append(theDiv)

                                        })
                                }
                                SpecSel(1, 0)
                            </script>
                        </div>



                        <div class="form-text"></div>
                    </div>
                </div>
            </div>


            <button type="submit" class="btn btn-primary">Submit</button>
        </form>



    </div>
    <div class="col-1"></div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    //新增主照片+API
    const shopTepProImg = document.querySelector("#shopTepProImg");

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
                    s_proImgBox.firstChild.src = `./shopImgs/${obj.filename}`;
                    s_proImgBox.firstChild.style.display = "block";
                    s_proImg.value = obj.filename;
                }
            }).catch(ex => {
                console.log(ex)
            })
    })

    //產品子類別API+生成option
    const shopCatSel = document.querySelector('#shopCatSel');
    const shopCatDetSel = document.querySelector('#shopCatDetSel');
    shopCatSel.addEventListener("change", () => {
        const selC = shopCatSel.value
        selShopCat(selC)
    });

    function selShopCat(catSel) {
        fetch(`s_proCat-api.php?cat_sid=${catSel}`)
            .then(r => r.json())
            .then(obj => {
                while (shopCatDetSel.hasChildNodes()) {
                    shopCatDetSel.removeChild(shopCatDetSel.lastChild)
                }
                //console.log(obj)
                for (let o of obj) {
                    const theOp = document.createElement('option');
                    theOp.setAttribute('value', o.catDet_sid);
                    const theText = document.createTextNode(o.catDet_name);
                    theOp.append(theText)
                    shopCatDetSel.append(theOp);
                }
            })
    }
    selShopCat('G')



    //供應商產地API+生成option
    const s_sup_sid = document.querySelector('#s_sup_sid');
    const sup_MIW = document.querySelector('#s_sup_MIW');
    s_sup_sid.addEventListener("change", () => {
        $selS = s_sup_sid.value
        selShopSup($selS)
    });

    function selShopSup(supSel) {
        fetch(`s_proSup-api.php?sup_sid=${supSel}`)
            .then(r => r.json())
            .then(obj => {
                while (sup_MIW.hasChildNodes()) {
                    sup_MIW.removeChild(sup_MIW.lastChild)
                }
                //console.log(obj)
                for (let o of obj) {
                    const theOp = document.createElement('option');
                    theOp.setAttribute('value', o.sup_MIW_sid);
                    const theText = document.createTextNode(o.sup_MIW);
                    theOp.append(theText)
                    sup_MIW.append(theOp);
                }
            })
    }
    selShopSup(1)
</script>
<?php include './partsNOEDIT/html-foot.php' ?>