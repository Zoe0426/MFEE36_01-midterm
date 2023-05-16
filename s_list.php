<?php
require './partsNOEDIT/connect-db.php' ?>
<?php include './partsNOEDIT/html-head.php' ?>
<style>
    .s_proDetTh,
    .s_proDetTd {
        text-align: center;
    }

    .s_proDetTr:hover {
        background-color: #e0e0e0;
    }

    .s_proDetTd:nth-child(3),
    .s_proDetTd:nth-child(4),
    .s_proDetTd:nth-child(5),
    .s_proDetTd:nth-child(6),
    .s_proDetTd:nth-child(7) {
        text-align: left;
    }

    .s_proDetTd:nth-child(7) {
        max-width: 100px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<?php include './partsNOEDIT/navbar.php' ?>

<div class="container p-3 mt-5">
    <div class="row">
        <nav id="nav"></nav>
    </div>
    <div class="row">
        <table id="s_form1" class="table table-bordered table-striped">
    </div>
    </table>
</div>

<?php include './partsNOEDIT/script.php' ?>
<script>
    const form1 = document.querySelector('#s_form1');
    const nav = document.querySelector('#nav');
    let docFrag = document.createDocumentFragment();


    form1.addEventListener('click', (event) => {
        let tar = event.target
        if (tar.classList.contains('fa-circle-info')) {
            const firstTd = tar.closest('tr').querySelector('td:first-child')
            const sendTd = tar.closest('tr').querySelector('td:nth-child(2)')
            let firstContent = firstTd.textContent;
            let sendContent = sendTd.textContent;
            console.log(firstContent)
            console.log(sendContent)
            location.href = `s_readonly-api.php?proDet_sid=${sendContent}&pro_sid=${firstContent}`
        }
        if (tar.classList.contains('fa-pen-to-square')) {
            const firstTd = tar.closest('tr').querySelector('td:first-child')
            const sendTd = tar.closest('tr').querySelector('td:nth-child(2)')
            let firstContent = firstTd.textContent;
            let sendContent = sendTd.textContent;
            console.log(firstContent)
            console.log(sendContent)
            location.href = `s_edit.php?proDet_sid=${sendContent}&pro_sid=${firstContent}`
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
                for (let k of turnToArr) {
                    let theTd = document.createElement('td');
                    theTd.classList.add("s_proDetTd")
                    let theTxt = document.createTextNode(k);
                    theTd.append(theTxt);
                    theTr.append(theTd);
                }
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
</script>
<?php include './partsNOEDIT/html-foot.php' ?>