<?php
require './partsNOEDIT/connect-db.php';

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
    .s_specTable td:nth-child(2n) {
        background-color: #ffe0b2;
    }

    .s_specTable th {
        background-color: #f57f17;
        color: white
    }

    .s_specTable {
        text-align: center;
        width: 900px
    }

    /* .s_specTable td {
        width: auto;
    } */

    .s_specTable td:hover {
        background-color: #f57f17;
    }
</style>
<?php include './partsNOEDIT/navbar.php' ?>
<div class="row p-0 m-0">
    <div class="col-1"></div>
    <div class="col">
        <form class="pt-4" name="s_Form3" onsubmit="checkForm(event)">
            <h2>新增產品規格</h2>
            <div class="row pb-3 mt-4" id="s_proDetBox">
                <div class="col-10 mb-3 s_spec">
                    <div class="row">
                        <div class="col-4">
                            <label class="form-label">規格名稱</label>
                            <select class="form-select s_spec_sid" name="spec_sid">
                                <option value="" selected disabled>--請選擇--</option>
                                <?php foreach ($r_shopSpec as $r) : ?>
                                    <option value="<?= $r['spec_sid'] ?>"><?= $r['spec_name'] ?></option>
                                <?php endforeach; ?>
                                <option value="<?= count($r_shopSpec) + 1 ?>">新增</option>
                            </select>
                        </div>
                        <div class="col-4">
                        </div>
                        <div class="col-4">
                        </div>
                    </div>
                    <div class="form-text"></div>
                </div>
            </div>


            <table class="table table-bordered s_specTable border-secondary">
            </table>
            <div class="alert alert-danger" id="infoBar" role="alert"></div>
            <div class="mt-3 s_allbtn mb-3">
                <button type="submit" class="btn btn-primary">確認新增</button>
                <button type="submit" class="btn btn-danger ms-3" onclick="cancelcreate()">取消新增</button>
            </div>
        </form>



    </div>
    <div class="col-1"></div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    function cancelcreate() {
        history.go(-1)
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
                        setTimeout(() => {
                            location.href = 's_list.php'
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

    //==================供應商產地自動生成==================
    // const supSel = document.querySelector('#s_sup_sid')
    // supSel.addEventListener('change', () => {
    //     const supSelId = supSel.value;
    //     createSupMIW(supSelId)
    // })

    // function createSupMIW(supSelId) {
    //     const supMIW = <?= json_encode($r_shopSupMIW, JSON_UNESCAPED_UNICODE) ?>;
    //     const supMIWSel = document.querySelector('#s_sup_MIW')
    //     supMIWSel.removeAttribute('disabled')
    //     removeChild(supMIWSel)
    //     for (let a of supMIW) {
    //         if (supSelId == a.sup_sid) {
    //             createOp('option', a.sup_MIW_sid, a.sup_MIW)
    //         }
    //     }
    //     supMIWSel.append(theDocFrag)
    // }
    //====================================

    //==================商品規格自動生成==================
    const specSel = document.querySelector('.s_spec_sid')
    specSel.addEventListener('change', () => {
        const specSelId = specSel.value;
        const theLabel = document.createElement('label')
        theLabel.classList.add('form-label')
        const theInput = document.createElement('input')
        if (specSelId != 7) {
            specSel.closest('.row').children[1].innerHTML = ""
            specSel.closest('.row').children[2].innerHTML = "";
            theLabel.textContent = "細項規格"
            theInput.setAttribute('name', 'specDet_name')
            theInput.setAttribute('placeholder', '請輸入細項名稱')
            theInput.classList.add('form-control')
            specSel.closest('.row').children[1].append(theLabel, theInput);
            createTable(specSelId)
        } else {
            specSel.closest('.row').children[1].innerHTML = "";
            theLabel.textContent = "空"
            theLabel.style.visibility = 'hidden'
            theInput.setAttribute('name', 'spec_name')
            theInput.setAttribute('placeholder', '請輸入新規格名稱')
            theInput.classList.add('form-control')
            specSel.closest('.row').children[1].append(theLabel, theInput);
            const theLabel1 = document.createElement('label')
            theLabel1.classList.add('form-label')
            theLabel1.textContent = "細項規格"
            const theInput1 = document.createElement('input')
            theInput1.setAttribute('name', 'specDet_name')
            theInput1.setAttribute('placeholder', '請輸入細項名稱')
            theInput1.classList.add('form-control')
            specSel.closest('.row').children[2].append(theLabel1, theInput1);
        };
    })

    function createTable(specSelId) {
        const theDocFrag = document.createDocumentFragment();
        const theTable = document.querySelector('.s_specTable');
        theTable.innerHTML = "";
        const specDet = <?= json_encode($r_shopSpecDet, JSON_UNESCAPED_UNICODE) ?>;
        const arr = [];
        for (let a of specDet) {
            if (specSelId == a.spec_sid) {
                arr.push(a);
            }
        };
        //若小規格有數字，則由小到大排序
        if (specSelId != 1) {
            arr.sort(function(a, b) {
                let c = a.specDet_name;
                let d = b.specDet_name;
                //檢查是否為純文字
                let isPureText = /^[a-zA-Z\u4e00-\u9fa5]+$/.test(c);
                if (isPureText) {
                    // 若為文字文字
                    return c.localeCompare(d);
                } else {
                    // 包含数字和中文情况下按数字大小排序
                    let cNum = parseFloat(c);
                    let dNum = parseFloat(d);
                    return cNum - dNum;
                }
            })
        };
        console.log(arr)
        const theThead = document.createElement('thead')
        const theThr = document.createElement("tr")
        const theTh = document.createElement("th")
        theTh.setAttribute('colspan', '5')
        //theTh.setAttribute("scope", "col")
        //theTh.classList.add('table-primary')
        theTh.innerHTML = '目前已存在的' + arr[0].spec_name;
        theThr.append(theTh)
        theThead.append(theThr)
        theTable.append(theThead)

        console.log(theTable)


        for (let i = 0, imax = arr.length; i < imax; i += 5) {
            const theTr = document.createElement("tr")
            for (let j = i, max = i + 5; j < max; j++) {
                const theTd = document.createElement('td')
                //theTd.setAttribute("scope", "col")
                const theTxt = document.createTextNode(arr[j]?.specDet_name || "")
                theTd.append(theTxt)
                theTr.append(theTd)
            }
            theDocFrag.append(theTr)
        }

        theTable.append(theDocFrag)
    }
</script>
<?php include './partsNOEDIT/html-foot.php' ?>