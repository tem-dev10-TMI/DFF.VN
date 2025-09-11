<div class="hmain">
        <div class="main">
            <div class="header-top">
                <div class="m-menu "><span class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav5"
                        aria-controls="navbarNav5" aria-expanded="false"><a href="javascript:void(0)"><i
                                class="fas fa-bars"></i></a></span>
                </div>
                <div class="header-logo">
                    <a href="index.html">
                        <img alt="Mạng xã hội kinh tế tài chính DFF" title="Mạng xã hội kinh tế tài chính DFF"
                            src="../img.dff.vn/static/img/logo.png" /></a>
                    <div class="box-search">
                        <div class="input-group ">
                            <span class="input-group-append">
                                <button
                                    class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5 btn-seach"
                                    module-load="onSearch" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            <input class="form-control border-end-0 border rounded-pill"
                                onkeypress="return OnEnter(event)" placeholder="Tìm kiếm" type="search" />
                        </div>
                        <div class="header-info"><i class="far fa-clock"></i><span class="currentDate"> </span></div>
                    </div>
                </div>

                <div class="header-right">
                    <ul>
                        <li><span><a href="#"><i class="fas fa-bars"></i></a></span> </li>
                        <li class="mnqtop"><span><a class="dropdown-toggle " data-bs-toggle="dropdown"
                                    aria-expanded="false" title="Tạo mới" href="javascript:void(0)"><i
                                        class="fas fa-plus"></i></a>
                                <ul class="dropdown-menu hide">
                                    <li><a style="position:relative" class="dropdown-item btquick"
                                            href="javascript:void(0)" module-load="loadwrite"><i
                                                class="fas fa-plus"></i><span class="number"><i
                                                    class="bi bi-lightning-charge-fill"></i></span> Viết bài nhanh</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" data-url="/write.html"
                                            module-load="redirect"><i class="fas fa-plus"></i> Viết bài thường</a></li>
                                </ul>
                            </span>
                        </li>
                        <li class="n-alert"><span data-bs-toggle="collapse" data-bs-target="#id_alert"
                                aria-controls="id_alert" aria-expanded="false"><a href="javascript:void(0)"
                                    title="Thông báo"><i class="fas fa-bell"></i></a> <span class="number">4</span>
                            </span>
                        </li>
                        <li class="top-pro "><span class="signin"><a module-load="signin" href="javascript:void(0)"><img
                                        src="vendor/dffvn/content/img/user.svg"></a> </span>
                            <span class="dropdown signed hide">
                                <a class="dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="vendor/dffvn/content/img/user.svg">
                                </a>

                                <ul class="dropdown-menu hide">
                                    <li>
                                        <div class="profiles">
                                            <ul>
                                            </ul>
                                            <div class="add">
                                                <a href="index.html">Xem tất cả Profile</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="menu-ai hide"><a class="dropdown-item" href="index.html"><i
                                                class="fas fa-dice-d20"></i> Hỗ trợ AI</a></li>
                                    <li><a class="dropdown-item" href="index.html"><i class="fas fa-plus"></i> Viết
                                            bài</a></li>
                                    <li><a class="dropdown-item" href="index.html"><i class="fas fa-user"></i>
                                            Profile</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" module-load="info"><i
                                                class="fas fa-info-circle"></i> Thông tin tài khoản</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" module-load="changepass"><i
                                                class="fas fa-unlock"></i> Đổi mật khẩu</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" module-load="logout"><i
                                                class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                                </ul>
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="collapse box-alert" id="id_alert">

                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold active position-relative" id="pills-home-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab"
                                aria-controls="pills-home" aria-selected="true">Thông báo</button>
                        </li>

                    </ul>
                    <div class="tab-content" id="pills-tabContent">

                    </div>
                </div>
                <div class="m-search"><span><a href="javascript:void(0)"><i class="fas fa-search"></i></a></span></div>

            </div>
        </div>
    </div>
    <!-- khu tự trị header nha cái này để hiện thị header ở phía trên  -->





    <div class="top-stock">
        <div class="marquee">
            <div class="item co-VNINDEX">
                <div class="irow label">
                    <span>VNINDEX</span>
                    <span class="value"></span>
                </div>
                <div class="irow content">
                    <span>
                        <i class=""></i>
                        <index></index>
                    </span>
                    <span class="per"></span>
                </div>
            </div>
            <div class="item co-HNX">
                <div class="irow label">
                    <span>HNX</span>
                    <span class="value"></span>
                </div>
                <div class="irow content">
                    <span>
                        <i class=""></i>
                        <index></index>
                    </span>
                    <span class="per"></span>
                </div>
            </div>
            <div class="item co-VN30F1M">
                <div class="irow label">
                    <span>VN30F1M</span>
                    <span class="value"></span>
                </div>
                <div class="irow content">
                    <span>
                        <i class=""></i>
                        <index></index>
                    </span>
                    <span class="per"></span>
                </div>
            </div>
            <div class="item co-VN30">
                <div class="irow label">
                    <span>VN30</span>
                    <span class="value"></span>
                </div>
                <div class="irow content">
                    <span>
                        <i class=""></i>
                        <index></index>
                    </span>
                    <span class="per"></span>
                </div>
            </div>
            <div class="item co-UPCOM">
                <div class="irow label">
                    <span>UPCOM</span>
                    <span class="value"></span>
                </div>
                <div class="irow content">
                    <span>
                        <i class=""></i>
                        <index></index>
                    </span>
                    <span class="per"></span>
                </div>
            </div>

            <div class="item co-Slave">
                <div class="irow label">
                    <span>Bạc</span>
                    <span class="value"></span>
                </div>
                <div class="irow content">
                    <span>
                        <i class=""></i>
                        <index></index>
                    </span>
                    <span class="per"></span>
                </div>
            </div>
            <div class="item co-Oil">
                <div class="irow label">
                    <span>Dầu Thô WTI</span>
                    <span class="value"></span>
                </div>
                <div class="irow content">
                    <span>
                        <i class=""></i>
                        <index></index>
                    </span>
                    <span class="per"></span>
                </div>
            </div>


            <div class="item co-BTC">
                <div class="irow label">
                    <span><a target="_blank" href="coins-bitcoin.html">Bitcoin</a></span>
                    <span class="value"></span>
                </div>
                <div class="irow content">
                    <span>
                        <i class=""></i>
                        <index></index>
                    </span>
                    <span class="per"></span>
                </div>
            </div>

            <div class="item co-ETH">
                <div class="irow label">
                    <span><a target="_blank" href="coins-ethereum.html">Ethereum</a></span>
                    <span class="value"></span>
                </div>
                <div class="irow content">
                    <span>
                        <i class=""></i>
                        <index></index>
                    </span>
                    <span class="per"></span>
                </div>
            </div>

            <div class="item co-BNB">
                <div class="irow label">
                    <span><a target="_blank" href="coins-binancecoin.html">BNB</a></span>
                    <span class="value"></span>
                </div>
                <div class="irow content">
                    <span>
                        <i class=""></i>
                        <index></index>
                    </span>
                    <span class="per"></span>
                </div>
            </div>



        </div>
    </div>