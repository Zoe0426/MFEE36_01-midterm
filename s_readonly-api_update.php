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
                <h2 class="me-auto">預覽商品</h2>
                <div class="d-flex align-items-end">
                    <div class="form-check me-3">
                        <input class="form-check-input" type="radio" name="shelf_status" id="on" value="1" <?= $r_shopInfo['shelf_status'] == 1 ? "checked" : "" ?>>
                        <label class="form-check-label" for="on">
                            上架
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="shelf_status" id="off" value="2" <?= $r_shopInfo['shelf_status'] == 2 ? "checked" : "" ?>>
                        <label class="form-check-label" for="off">
                            下架
                        </label>
                    </div>
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
                            <select class="form-select" name="for_pet_type" id="shopForSel">
                                <option value="" disabled>--請選擇--</option>
                                <option value="D" <?= $r_shopInfo['for_pet_type'] == 'D' ? "selected" : "" ?>>狗</option>
                                <option value="C" <?= $r_shopInfo['for_pet_type'] == 'C' ? "selected" : "" ?>>貓</option>
                                <option value="B" <?= $r_shopInfo['for_pet_type'] == 'B' ? "selected" : "" ?>>皆可</option>
                            </select>
                        </div>
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s_supplier_sid">產品類別</label>
                        <div class="row">
                            <div class="col-6">
                                <select class="form-select" name="category_sid" id="s_category_sid">
                                    <option value="" disabled>--請選擇--</option>
                                    <?php foreach ($r_shopCat as $r) : ?>
                                        <option value="<?= $r['category_sid'] ?>" <?= $r_shopInfo['category_sid'] == $r['category_sid'] ? "selected" : "" ?>><?= $r['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
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
                        <label class="form-label" for="s_supplier_sid">供應商</label>
                        <div class="row">
                            <div class="col-6">
                                <select class="form-select" name="supplier_sid" id="s_supplier_sid">
                                    <option value="" disabled>--請選擇--</option>
                                    <?php foreach ($r_shopSup as $r) : ?>
                                        <option value="<?= $r['supplier_sid'] ?>" <?= $r_shopInfo['supplier_sid'] == $r['supplier_sid'] ? "selected" : "" ?>><?= $r['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select" name="made_in_where" id="s_made_in_where">
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
                                    <select class="form-select s_specific_sid1" name="specific_sid1[]">
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
                                <select class="form-select" name="for_age[]">
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
                <button type="button" class="btn btn-secondary" onclick="backToList()">返回列表</button>
                <button type="button" class="btn btn-primary ms-3" onclick="toEdit()">編輯商品</button>
            </div>
        </form>

    </div>
    <div class="col-1"></div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('input, textarea, select').forEach(function(element) {
        element.setAttribute('readonly', 'true');
        element.setAttribute('disabled', 'true');
    })

    function backToList() {
        location.href = 's_list_update.php'
    }

    function toEdit() {
        const proSid = document.querySelector('#s_product_sid>input').value
        console.log(proSid);
        location.href = `s_edit_update.php?product_sid=${proSid}`
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>