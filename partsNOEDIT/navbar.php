<nav class="navbar navbar-expand-lg bg-warning position-fixed navbar">
    <div class="container-fluid ms-3">
        <a class="navbar-brand text-dark" href="#">
            <i class="fa-brands fa-github-alt me-3"></i>第一女子軍團</a>
        <div class="dropdown p-2 ">

            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="true">
                <img src="https://images.pexels.com/photos/4597758/pexels-photo-4597758.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="" width="40" height="40" class="rounded-circle me-2">
                <?php if (isset($_SESSION['admin'])) : ?>
                    <div>Hi! <?= $_SESSION['admin']['admin_name'] ?></div>
                <?php else : ?>
                    <div>管理員</div>
                <?php endif; ?>
            </a>

            <ul class="dropdown-menu dropdown-menu-dark text-small shadow " aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="#">設定</a></li>
                <li><a class="dropdown-item" href="#">個人資料</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <?php if (isset($_SESSION['admin'])) : ?>
                    <li><a class="dropdown-item" href="m_adminLogout.php">登出</a></li>
                <?php else : ?>
                    <li><a class="dropdown-item" href="m_adminLogin.php">登入</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid px-0">

    <div class="bg-warning-subtle  min-vh-100 pt-3 sidebar">
        <!-- 登入 -->
        <ul class="nav nav-pills flex-column mb-auto">
            <!-- <li class="nav-item">
                <a href="m_adminLogin.php" class="nav-link text-dark ">
                    登入
                </a>
                <div class="collapse w-100" id="collapseBt1">
                    <a href="#" class="nav-link bg-transparent text-dark ">
                        - 個人資料
                    </a>
                    <a href="#" class="nav-link bg-transparent  text-dark ">
                        - 個人資料
                    </a>
                </div>
            </li> -->
            <li class="nav-item">
                <a href="#" class="nav-link text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt1">
                    會員管理
                </a>
                <div class="collapse w-100" id="collapseBt1">
                    <a href="m_member-list.php" class="nav-link bg-transparent text-dark ">
                        - 會員資料
                    </a>
                </div>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt2">
                    優惠券管理
                </a>
                <div class="collapse w-100" id="collapseBt2">
                    <a href="m_coupon_type-list.php" class="nav-link bg-transparent text-dark ">
                        - 種類清單
                    </a>
                </div>
                <div class="collapse w-100" id="collapseBt2">
                    <a href="m_coupon_send_list.php" class="nav-link bg-transparent text-dark ">
                        - 發送紀錄
                    </a>
                </div>
                <div class="collapse w-100" id="collapseBt2">
                    <a href="m_coupon_send_chart.php" class="nav-link bg-transparent text-dark ">
                        - 優惠券分析
                    </a>
                </div>
            </li>
            <!-- 商城 -->
            <li class="nav-item">
                <a href="#" class="nav-link text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt3">
                    商城
                </a>
                <div class="collapse w-100" id="collapseBt2">
                    <a href="./s_list.php" class="nav-link bg-transparent text-dark">
                        - 商品管理
                    </a>
                    <a href="./s_proSpecAdd.php" class="nav-link bg-transparent  text-dark">
                        - 規格管理
                    </a>
                </div>
            </li>

            <!-- 餐廳 -->
            <li class="nav-item">
                <a href="#" class="nav-link text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt4">
                    餐廳
                </a>
                <div class="collapse w-100" id="collapseBt3">
                    <a href="./r_read.php" class="nav-link bg-transparent text-dark ">
                        - 餐廳資料
                    </a>
                </div>
                <!-- 活動 -->
            <li class="nav-item">
                <a href="#" class="nav-link text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt5">
                    活動
                </a>
                <div class="collapse w-100" id="collapseBt5">
                    <a href="a_add.php" class="nav-link bg-transparent text-dark ">
                        - 活動上架
                    </a>
                    <a href="a_list_admin_TypeS.php" class="nav-link bg-transparent text-dark ">
                        - 活動列表
                    </a>
                </div>
                <!-- 論壇 -->
            <li class="nav-item">
                <a href="#" class="nav-link text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt6">
                    論壇
                </a>
                <div class="collapse w-100" id="collapseBt6">
                    <a href="#" class="nav-link bg-transparent text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt5">
                        - 論壇資料
                    </a>
                    <a href="#" class="nav-link bg-transparent  text-dark ">
                        - 論壇資料
                    </a>
                </div>
                <!-- 購物車 -->
            <li class="nav-item">
                <a href="#" class="nav-link text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt7">
                    購物車
                </a>
                <div class="collapse w-100" id="collapseBt7">
                    <a href="./index_noEdit.php" class="nav-link bg-transparent text-dark ">
                        - 購物車資料
                    </a>
                    <a href="./o_order_details.php" class="nav-link bg-transparent  text-dark ">
                        - 購物車資料
                    </a>
                </div>
        </ul>

    </div>
    <div class="content">