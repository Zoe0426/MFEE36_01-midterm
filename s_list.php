<?php
require './partsNOEDIT/connect-db.php' ?>
<?php include './partsNOEDIT/html-head.php' ?>
<?php include './partsNOEDIT/navbar.php' ?>

<div class="container">
    <div class="row">
        <nav id="nav"></nav>
    </div>
    <div class="row">
        <table id="form1" class="table table-bordered table-striped">
    </div>
    </table>
</div>
<table id="form1">
</table>


<?php include './partsNOEDIT/script.php' ?>
<script>
    const form1 = document.querySelector('#form1');
    const nav = document.querySelector('#nav');
    let docFrag = document.createDocumentFragment();

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
            let theRow = document.createElement('tr')
            for (let i of firstRow) {
                let theTh = document.createElement('th');
                theTh.setAttribute("scope", "col")
                let theTxt = document.createTextNode(i);
                theTh.append(theTxt);
                docFrag.append(theTh);
            }
            theRow.append(docFrag)
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
                for (let k of turnToArr) {
                    let theTd = document.createElement('td');
                    let theTxt = document.createTextNode(k);
                    theTd.append(theTxt);
                    theTr.append(theTd);
                }
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
            theUl.classList.add('pagination', 'justify-content-end')
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

        console.log(`perPage:${perPage}, page:${page}, totalRows:${totalRows},totalPages:${totalPages}`)
    }

    function changePage(pageNow) {
        fetch(`s_list-api.php?page=${pageNow}`)
            .then(r => r.json())
            .then(obj => {
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



            })
    }
    changePage(1);

    // const datas = ;
    // console.log(datas)
    // let {
    //     proDet_sid,
    //     pro_sid,
    //     proDet_name,
    //     proDet_price,
    //     proDet_qty,
    //     proDet_img,
    //     pro_forAge,
    //     pro_for,
    //     pro_name,
    //     pro_describe,
    //     pro_img,












    // } = datas;
</script>
<?php include './partsNOEDIT/html-foot.php' ?>