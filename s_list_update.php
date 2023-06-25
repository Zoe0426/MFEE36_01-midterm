<?php
require './partsNOEDIT/connect-db.php';

$sql_shopCatDet = "SELECT * FROM `shop_category` ORDER BY `detail_num` ";
$r_shopCatDet = $pdo->query($sql_shopCatDet)->fetchAll();
// header('Content-Type: application/json');
// print_r($r_shopCatDet);

?>
<?php include './partsNOEDIT/html-head.php' ?>
<style>
    .s_proDetTh,
    .s_proDetTd {
        text-align: center;
    }

    .s_proDetTr:hover {
        background-color: #e0e0e0;
    }

    .s_proDetTd:nth-child(7) {
        text-align: left;
    }

    .s_proDetTd:nth-child(8),
    .s_proDetTd:nth-child(9) {
        text-align: right;
    }

    #s_searchway {
        width: 100px;
    }

    #s_search_sid,
    #s_search_rank {
        width: 200px
    }

    #s_searchBar {
        width: 200px;
        display: none;
    }

    .s_proDetImg {
        width: 100px;

    }
</style>
<?php /*include './partsNOEDIT/navbar.php' */ ?>

<div class="container p-3 mt-5">
    <div class="d-flex my-3 px-0">
        <form name="s_formS" class="me-auto s_formS d-flex justify-content-between">
            <div class="hstack align-items-end">
                <label class="form-label s_label" for="pro_name" id="s_searchway"> 商品搜尋：</label>
                <select class="form-select me-3" name="search_sid" id="s_search_sid">
                    <option value="" selected disabled>--請選擇--</option>
                    <option value="1">商品名稱</option>
                    <option value="2">商品類別</option>
                    <option value="3">適用對象</option>
                    <option value="4">適用年齡</option>
                    <option value="5">商品狀態</option>
                </select>
                <div id="s_searchBar"></div>
                <button type="button" class="btn btn-primary ms-3 me-3" onclick="search()" disabled><i class="fa-solid fa-magnifying-glass"></i></button>
                <button type="button" class="btn btn-danger me-auto" onclick="reSet(event)" disabled><i class="fa-solid fa-rotate-right"></i></button>

            </div>
            <div class="hstack align-items-end ps-3">
                <label class="form-label s_label" for="search_rank" id="s_searchway"> 排序方式:</label>
                <select class="form-select me-3" name="search_rank" id="s_search_rank">
                    <option value="" selected disabled>--請選擇--</option>
                    <option value="1">價格由高到低</option>
                    <option value="2">價格由低到高</option>
                    <option value="3">編輯時間</option>
                    <option value="4">商品編號</option>
                </select>
            </div>
        </form>
        <button type="button" class="btn btn-warning" onclick="createItem()">新增商品</button>
    </div>
    <div class="row">
        <table id="s_form1" class="table table-bordered table-striped"> </table>
    </div>
    <div class="row" id="s_result"></div>
    <div class="row">
        <nav id="nav"></nav>
    </div>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    const form1 = document.querySelector('#s_form1');
    const nav = document.querySelector('#nav');
    let docFrag = document.createDocumentFragment();
    const searchID = document.querySelector('#s_search_sid')
    const searchBar = document.querySelector('#s_searchBar')
    const searchRank = document.querySelector('#s_search_rank')
    const theResult = document.querySelector('#s_result')
    const theTable = document.querySelector('#s_form1')
    const theNav = document.querySelector('#nav')
    searchRank.addEventListener('change', () => {
        const r = searchRank.value
        if (searchID.value == 0) {
            changePage(1, r);
        } else {
            search()
        }

    })

    function search() {
        const searchSel = searchID.value;
        const searchWord = document.querySelector("#s_searchWord").value
        const fd = new FormData(document.s_formS);
        fetch('s_search-api_update.php', {
                method: "POST",
                body: fd,
            }).then(r => r.json())
            .then(obj => {

                if (obj.message) {
                    theResult.innerHTML = "";
                    let {
                        perPage,
                        page,
                        totalRows,
                        totalPages,
                        rows
                    } = obj;
                    create(perPage, page, totalRows, totalPages, rows);
                    let cliK = document.querySelectorAll('.page-link')
                    for (let i = 0, max = cliK.length; i < max; i++) {
                        cliK[i].addEventListener('click', () => {
                            let k = cliK[i].innerHTML
                            //console.log(isNaN(k))
                            if (i == 0) {
                                changePage(1)
                            } else if (i == 1) {
                                let act = document.querySelector('.active').innerHTML
                                changePage(Number(act) - 1)
                            } else if (i == cliK.length - 2) {
                                let act = document.querySelector('.active').innerHTML
                                changePage(Number(act) + 1)
                            } else if (i == cliK.length - 1) {
                                changePage(totalPages)
                            } else {
                                changePage(Number(k))
                            }
                        })
                    }
                } else {
                    theResult.innerHTML = "";
                    theNav.innerHTML = ""
                    theTable.innerHTML = ""
                    const theH1 = document.createElement('h1')
                    const theTxt = document.createTextNode("查無此項商品")
                    theH1.append(theTxt);
                    theResult.append(theH1)

                }

            })


        console.log(searchWord)
    }

    function reSet() {
        theResult.innerHTML = "";
        changePage(1)
        searchID.selectedIndex = 0;
        searchBar.style.display = "none";
        const BTNs = document.querySelectorAll('.s_formS button')
        for (let i = 0, max = BTNs.length; i < max; i++) {
            BTNs[i].setAttribute("disabled", "")
        }

    }
    searchID.addEventListener('change', () => {
        const searchSel = searchID.value;
        const BTNs = document.querySelectorAll('.s_formS button')
        for (let i = 0, max = BTNs.length; i < max; i++) {
            console.log(i)
            BTNs[i].removeAttribute("disabled")
        }
        while (searchBar.hasChildNodes()) {
            searchBar.removeChild(searchBar.lastChild)
        }
        switch (searchSel) {
            case '1':
                docFrag = createInp()
                break;
            case '2':
                docFrag = createOpC()
                break;
            case '3':
                docFrag = createOpF()
                break;
            case '4':
                docFrag = createOpA()
                break;
            case '5':
                docFrag = createOpU()
                break;
        }
        searchBar.append(docFrag);
        searchBar.style.display = "block";
    })


    function createInp() {
        const theFragDoc = document.createDocumentFragment()
        const theInput = document.createElement('input')
        theInput.setAttribute('type', 'text')
        theInput.setAttribute('name', 'search_word')
        theInput.setAttribute("id", "s_searchWord")
        theInput.setAttribute('data-required', '1')
        theInput.setAttribute('placeholder', '請輸入關鍵字')
        theInput.classList.add("form-control")
        theFragDoc.append(theInput)
        return theFragDoc
    }

    function createOpC() {
        const theFragDoc = document.createDocumentFragment()
        const datas = <?= json_encode($r_shopCatDet, JSON_UNESCAPED_SLASHES) ?>;
        const theSel = document.createElement('select')
        theSel.classList.add("form-select")
        theSel.setAttribute('name', 'search_word')
        theSel.setAttribute("id", "s_searchWord")
        for (let d of datas) {
            const theOp = document.createElement('option');
            theOp.setAttribute("value", d.category_detail_sid)
            const theTxt = document.createTextNode(d.detail_name)
            theOp.append(theTxt)
            theSel.append(theOp)
        }
        theFragDoc.append(theSel)
        return theFragDoc
    }

    function createOpF() {
        const theFragDoc = document.createDocumentFragment()
        const theSel = document.createElement('select')
        theSel.classList.add("form-select")
        theSel.setAttribute('name', 'search_word')
        theSel.setAttribute("id", "s_searchWord")
        const theOp1 = document.createElement('option');
        theOp1.setAttribute("value", "D")
        const theTxt1 = document.createTextNode("狗")
        theOp1.append(theTxt1)
        const theOp2 = document.createElement('option');
        theOp2.setAttribute("value", "C")
        const theTxt2 = document.createTextNode("貓")
        theOp2.append(theTxt2)
        const theOp3 = document.createElement('option');
        theOp3.setAttribute("value", "B")
        const theTxt3 = document.createTextNode("皆可")
        theOp3.append(theTxt3)
        theSel.append(theOp1, theOp2, theOp3)
        theFragDoc.append(theSel)
        return theFragDoc
    }

    function createOpA() {
        const theFragDoc = document.createDocumentFragment()
        const theSel = document.createElement('select')
        theSel.classList.add("form-select")
        theSel.setAttribute('name', 'search_word')
        theSel.setAttribute("id", "s_searchWord")
        const theOp1 = document.createElement('option');
        theOp1.setAttribute("value", 1)
        const theTxt1 = document.createTextNode("幼年")
        theOp1.append(theTxt1)
        const theOp2 = document.createElement('option');
        theOp2.setAttribute("value", 2)
        const theTxt2 = document.createTextNode("成年")
        theOp2.append(theTxt2)
        const theOp3 = document.createElement('option');
        theOp3.setAttribute("value", 3)
        const theTxt3 = document.createTextNode("高齡")
        theOp3.append(theTxt3)
        const theOp4 = document.createElement('option');
        theOp4.setAttribute("value", 4)
        const theTxt4 = document.createTextNode("全齡")
        theOp4.append(theTxt4)
        theSel.append(theOp1, theOp2, theOp3, theOp4)
        theFragDoc.append(theSel)
        return theFragDoc
    }

    function createOpU() {
        const theFragDoc = document.createDocumentFragment()
        const theSel = document.createElement('select')
        theSel.classList.add("form-select")
        theSel.setAttribute('name', 'search_word')
        theSel.setAttribute("id", "s_searchWord")
        const theOp1 = document.createElement('option');
        theOp1.setAttribute("value", "1")
        const theTxt1 = document.createTextNode("上架中")
        theOp1.append(theTxt1)
        const theOp2 = document.createElement('option');
        theOp2.setAttribute("value", "2")
        const theTxt2 = document.createTextNode("下架中")
        theOp2.append(theTxt2)
        theSel.append(theOp1, theOp2)
        theFragDoc.append(theSel)
        return theFragDoc
    }

    function createItem() {
        location.href = 's_proAdd_update.php'
    }

    form1.addEventListener('click', (event) => {
        let tar = event.target
        if (tar.classList.contains('fa-circle-info')) {
            const firstTd = tar.closest('tr').querySelector('td:first-child')
            const sendTd = tar.closest('tr').querySelector('td:nth-child(2)')
            let firstContent = firstTd.textContent;
            let sendContent = sendTd.textContent;
            //console.log(firstContent)
            //console.log(sendContent)
            location.href = `s_readonly-api_update.php?product_detail_sid=${sendContent}&product_sid=${firstContent}`
        }
        if (tar.classList.contains('fa-pen-to-square')) {
            const firstTd = tar.closest('tr').querySelector('td:first-child')
            const sendTd = tar.closest('tr').querySelector('td:nth-child(2)')
            let firstContent = firstTd.textContent;
            let sendContent = sendTd.textContent;
            //console.log(firstContent)
            //console.log(sendContent)
            location.href = `s_edit_update.php?product_detail_sid=${sendContent}&product_sid=${firstContent}`
        }
        // if (tar.classList.contains('fa-trash-can')) {
        //     const firstTd = tar.closest('tr').querySelector('td:first-child')
        //     const sendTd = tar.closest('tr').querySelector('td:nth-child(2)')
        //     let firstContent = firstTd.textContent;
        //     let sendContent = sendTd.textContent;
        //     console.log(firstContent)
        //     console.log(sendContent)
        //     location.href = `s_del-api.php?proDet_sid=${sendContent}&pro_sid=${firstContent}`
        // }

    })

    function create(perPage, page, totalRows, totalPages, rows) {
        while (nav.hasChildNodes()) {
            //console.log('123')
            nav.removeChild(nav.lastChild)
        }
        while (form1.hasChildNodes()) {
            //console.log('123')
            form1.removeChild(form1.lastChild)
        }
        //新增TH內容
        function createTH() {
            let firstRow = Object.keys(rows[0]);
            let theTHead = document.createElement('thead')
            let theRow = document.createElement('tr');
            for (let i of firstRow) {
                let theTh = document.createElement('th');
                theTh.setAttribute("scope", "col")
                theTh.classList.add("s_proDetTh")
                let theTxt = document.createTextNode(i);
                theTh.append(theTxt);
                theRow.append(theTh);
            }

            let theDetTh = document.createElement('th');
            theDetTh.setAttribute("scope", "col")
            theDetTh.classList.add("s_proDetTh")
            let theEditTh = document.createElement('th');
            theEditTh.setAttribute("scope", "col")
            theEditTh.classList.add("s_proDetTh")
            let theDelTh = document.createElement('th');
            //theDelTh.setAttribute("scope", "col")
            //theDelTh.classList.add("s_proDetTh")
            theDetTh.textContent = '詳細資訊';
            theEditTh.textContent = '編輯';
            //theDelTh.textContent = '刪除';

            //theRow.append(theDetTh, theEditTh, theDelTh)
            theRow.append(theDetTh, theEditTh)
            theTHead.append(theRow);
            form1.append(theTHead);
        };
        createTH();

        //新增TD內容
        function createEachRow() {
            let theTBody = document.createElement('tbody')
            for (let j of rows) {
                let turnToArr = Object.values(j);
                let theTr = document.createElement('tr')
                theTr.classList.add("s_proDetTr")

                turnToArr.forEach((k, index) => {
                    if (index === 2) {
                        let theTd = document.createElement('td');
                        theTd.classList.add("s_proDetTd")
                        let theImg = document.createElement('img');
                        theImg.setAttribute('src', `./s_imgs/${k}`)
                        theImg.classList.add("s_proDetImg")
                        theTd.append(theImg);
                        theTr.append(theTd);
                    } else {
                        let theTd = document.createElement('td');
                        theTd.classList.add("s_proDetTd")
                        let theTxt = document.createTextNode(k);
                        theTd.append(theTxt);
                        theTr.append(theTd);
                    }

                })

                // for (let k of turnToArr) {

                // }
                let theDetTd = document.createElement('td');
                let theDetTxt = createEl2('i', 'fa-solid', 'fa-circle-info')
                theDetTd.classList.add("s_proDetTh")
                theDetTd.style.color = "#3B71CA"
                theDetTd.append(theDetTxt)

                let theEditTd = document.createElement('td');
                let theEditTxt = createEl2('i', 'fa-regular', 'fa-pen-to-square')
                theEditTd.classList.add("s_proDetTh")
                theEditTd.style.color = "#14A44D"
                theEditTd.append(theEditTxt)

                // let theDelTd = document.createElement('td');
                // let theDelTxt = createEl2('i', 'fa-regular', 'fa-trash-can')
                // theDelTd.classList.add("s_proDetTh")
                // theDelTd.style.color = "#DC4C64"
                // theDelTd.append(theDelTxt)

                theTr.append(theDetTd)
                theTr.append(theEditTd)
                // theTr.append(theDelTd)

                docFrag.append(theTr);
            }
            theTBody.append(docFrag)
            form1.append(theTBody)
        }
        createEachRow();

        //創造元素與屬性
        function createEl(a, b) {
            let k = document.createElement(a);
            k.classList.add(b)
            return k
        }

        function createEl2(a, b, c) {
            let k = document.createElement(a);
            k.classList.add(b, c)
            return k
        }


        //新增分頁
        function createPage() {
            let theUl = document.createElement('ul')
            theUl.classList.add('pagination', 'justify-content-center')
            //第一頁
            let theLi = createEl('li', 'page-item')
            theA = createEl('a', 'page-link')
            if (page == 1) {
                theLi.classList.add('disabled')
            }
            let theI = createEl2('i', 'fa-solid', 'fa-backward-step')
            theA.append(theI);
            theLi.append(theA);
            theUl.append(theLi);
            //上一頁
            theLi = createEl('li', 'page-item')
            theA = createEl('a', 'page-link')
            if (page == 1) {
                theLi.classList.add('disabled')
            }
            theI = createEl2('i', 'fa-solid', 'fa-chevron-left')
            theA.append(theI);
            theLi.append(theA);
            theUl.append(theLi);
            //頁碼
            for (let i = page - 5; i <= page + 5; i++) {
                if (i >= 1 && i <= totalPages) {
                    let theLi = createEl('li', 'page-item')
                    theA = createEl('a', 'page-link')
                    let theTxt = document.createTextNode(i);
                    if (i == page) {
                        theA.classList.add('active')
                    }
                    theA.append(theTxt);
                    theLi.append(theA);
                    theUl.append(theLi);
                }
            }
            nav.append(theUl)


            //下一頁
            theLi = createEl('li', 'page-item')
            theA = createEl('a', 'page-link')
            if (page == totalPages) {
                theLi.classList.add('disabled')
            }
            theI = createEl2('i', 'fa-solid', 'fa-chevron-right')
            theA.append(theI);
            theLi.append(theA);
            theUl.append(theLi);


            //最後一頁
            theLi = createEl('li', 'page-item')
            theA = createEl('a', 'page-link')
            if (page == totalPages) {
                theLi.classList.add('disabled')
            }
            theI = createEl2('i', 'fa-solid', 'fa-forward-step')
            theA.append(theI);
            theLi.append(theA);
            theUl.append(theLi);

        }

        createPage();

        //console.log(`perPage:${perPage}, page:${page}, totalRows:${totalRows},totalPages:${totalPages}`)
    }

    function changePage(pageNow, rankNow) {
        fetch(`s_list-api_update.php?page=${pageNow}&search_rank=${rankNow}`)
            .then(r => r.json())
            .then(obj => {
                let {
                    perPage,
                    page,
                    totalRows,
                    totalPages,
                    rows
                } = obj;
                const searchRank = document.querySelector('#s_search_rank').value
                console.log(searchRank)
                create(perPage, page, totalRows, totalPages, rows);
                let cliK = document.querySelectorAll('.page-link')
                for (let i = 0, max = cliK.length; i < max; i++) {
                    cliK[i].addEventListener('click', () => {
                        let k = cliK[i].innerHTML
                        //console.log(isNaN(k))
                        if (i == 0) {
                            changePage(1, searchRank)
                        } else if (i == 1) {
                            let act = document.querySelector('.active').innerHTML
                            changePage((Number(act) - 1), searchRank)
                        } else if (i == cliK.length - 2) {
                            let act = document.querySelector('.active').innerHTML
                            changePage((Number(act) + 1), searchRank)
                        } else if (i == cliK.length - 1) {
                            changePage(totalPages, searchRank)
                        } else {
                            changePage((Number(k)), searchRank)
                        }
                    })
                }
            })
    }
    changePage(1, 4);
</script>
<?php include './partsNOEDIT/html-foot.php' ?>