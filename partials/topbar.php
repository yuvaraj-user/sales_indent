<header id="page-topbar" class="isvertical-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="../global/photos/VijayRasiSeedsLogo.png" alt="" height="26">
                    </span>
                    <span class="logo-lg">
                        <img src="../global/photos/VijayRasiSeedsLogo.png" alt="" height="26">
                    </span>
                </a>

                <a href="index.html" class="logo logo-light">
                    <span class="logo-lg">
                        <img src="../global/photos/VijayRasiSeedsLogo.png" alt="" height="30">
                    </span>
                    <span class="logo-sm">
                        <img src="../global/photos/VijayRasiSeedsLogo.png" alt="" height="26">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm font-size-24 header-item waves-effect vertical-menu-btn" style="display: block !important;">
                <i class="bx bx-menu align-middle"></i>
            </button>

            <!-- start page title -->
            <div class="page-title-box align-self-center d-none d-md-block">
                <div class="d-flex align-items-center">
                    <!-- <i class="bx bx-home-alt icon font-size-20 me-2 text-primary"></i> -->
                    <!-- <h4 class="page-title mb-0 app-font font-size-13">Indent Dashboard</h4></div> -->
                    <h4 class="page-title mb-0 app-font font-size-13"></h4>
                </div>
            </div>
            <!-- end page title -->

        </div>

        <div class="d-flex">
          
            
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown-v"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="../global/photos/signin.png"
                    alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-2 fw-medium font-size-15"><?=  $_SESSION['Name']; ?></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="p-3 border-bottom d-flex justify-content-center flex-column align-items-center">
                        <h6 class="mb-0"><?=  $_SESSION['Name']; ?></h6>
                        <p class="mb-0 font-size-11 text-white badge bg-danger mt-2"><?=  $_SESSION['EmpID']; ?></p>
                    </div>
                    <a class="dropdown-item" href="../common/profile.php"><i class="mdi mdi-key-chain text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Change Password</span></a>
                    <a class="dropdown-item" href="../logout.php"><i class="mdi mdi-logout text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Logout</span></a>
                </div>
            </div>
        </div>
    </div>
</header>