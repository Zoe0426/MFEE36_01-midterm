<nav class="navbar navbar-expand-lg bg-warning position-fixed navbar">
    <div class="container-fluid ms-3">
        <a class="navbar-brand text-dark" href="#">
            <i class="fa-brands fa-github-alt me-3"></i>第一女子軍團</a>
        <div class="dropdown p-2 ">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://images.pexels.com/photos/4597758/pexels-photo-4597758.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="" width="40" height="40" class="rounded-circle me-2">
                <strong>管理員</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow " aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="#">設定</a></li>
                <li><a class="dropdown-item" href="#">個人資料</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#">登出</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid px-0">

    <div class="bg-warning-subtle  min-vh-100 pt-3 sidebar">
        <!-- 登入 -->
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link text-dark ">
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
            </li>
            <!-- 商城 -->
            <li class="nav-item">
                <a href="#" class="nav-link text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt2">
                    商城
                </a>
                <div class="collapse w-100" id="collapseBt2">
                    <a href="./s_list.php" class="nav-link bg-transparent text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt2">
                        - 商品管理
                    </a>
                    <a href="#" class="nav-link bg-transparent  text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt2">
                        - 商城資料
                    </a>
                </div>
            </li>

            <!-- 餐廳 -->
            <li class="nav-item">
                <a href="#" class="nav-link text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt3">
                    餐廳
                </a>
                <div class="collapse w-100" id="collapseBt3">
                    <a href="#" class="nav-link bg-transparent text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt3">
                        - 餐廳資料
                    </a>
                    <a href="#" class="nav-link bg-transparent  text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt3">
                        - 餐廳資料
                    </a>
                </div>
                <!-- 活動 -->
            <li class="nav-item">
                <a href="#" class="nav-link text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt4">
                    活動
                </a>
                <div class="collapse w-100" id="collapseBt4">
                    <a href="#" class="nav-link bg-transparent text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt4">
                        - 活動資料
                    </a>
                    <a href="#" class="nav-link bg-transparent  text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt4">
                        - 活動資料
                    </a>
                </div>
                <!-- 論壇 -->
            <li class="nav-item">
                <a href="#" class="nav-link text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt5">
                    論壇
                </a>
                <div class="collapse w-100" id="collapseBt5">
                    <a href="#" class="nav-link bg-transparent text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt5">
                        - 論壇資料
                    </a>
                    <a href="#" class="nav-link bg-transparent  text-dark ">
                        - 論壇資料
                    </a>
                </div>
                <!-- 購物車 -->
            <li class="nav-item">
                <a href="#" class="nav-link text-dark " data-bs-toggle="collapse" data-bs-target="#collapseBt6">
                    購物車
                </a>
                <div class="collapse w-100" id="collapseBt6">
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