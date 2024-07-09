<?php 
ob_start();
include '../auto_load.php';
include 'menu_access.php';
?>
<html lang="en">

    <head>

        <meta charset="utf-8" />
        <title>Rasi Seeds (P) Ltd - Corporate Portal</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />
        <!-- App favicon -->
          <link rel="shortcut icon" href="../global/photos/favicon.ico" />

        <!-- plugin css -->
        <link href="assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

        <!-- datepicker css -->
        <link rel="stylesheet" href="assets/libs/flatpickr/flatpickr.min.css">

        <!-- select 2 css -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <!-- datatable css -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
        <link href="assets/libs/tui-chart/tui-chart.min.css" rel="stylesheet" type="text/css" />

        <!-- google web font css start  -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <!-- google web font css end  -->

        <!-- daterangepicker css -->
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <!-- sweeetalert css -->
        <link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

<link rel="stylesheet" href="https://uicdn.toast.com/chart/latest/toastui-chart.min.css" />

        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tui-chart/3.11.3/tui-chart.min.css" integrity="sha512-5jmJRA2B5yoKh3IDxrQYL1gAEbi4RAlr9KVUSoVJ0dpYEtq9ZODDq45cFpvsktrChwhjqdv6YxLpANaraw6Gmw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.slim.min.js" integrity="sha512-sNylduh9fqpYUK5OYXWcBleGzbZInWj8yCJAU57r1dpSK9tP2ghf/SRYCMj+KsslFkCOt3TvJrX2AV/Gc3wOqA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-countto/1.1.0/jquery.countTo.min.js" integrity="sha512-ZbM86dAmjIe3nPA2k8j3G//NO/zBYNnZ8wi+yUKh8VH24CHr0aDhDHoEM4IvGl+Sz6ga7ONnGBDxS+BTVJ+K2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

<script src="https://cdn.jsdelivr.net/npm/jquery-countto@1.2.0/jquery.countTo.min.js"></script>
        <style>
            @import url(https://fonts.googleapis.com/css?family=Roboto);

            * {
                    font-family: Roboto, sans-serif;
            }

            @media only screen and (min-width: 768px) and (max-width: 1024px)  {
                * {
                  font-size: 11px !important;
                }

                .dataTables_paginate {
                    display: contents;
                }

                .dataTables_scroll {
                    margin-bottom: 20px;
                }

                .approvals {
                    margin-top: 10px;
                }
            }

            @media only screen and (max-width: 767px)  {
                * {
                  font-size: 10px !important;
                }

                .dataTables_paginate {
                    display: contents;
                }

                .approvals {
                    margin-top: 10px;
                }
            }

            .app-font {
                font-family: 'Roboto', sans-serif;
                font-size: 18px !important;
            }
            html,body {
/*              font-family: "Roboto", sans-serif;*/
              font-size: 13px !important;
              min-height: 0px !important;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
               /* background: #1f58c7 !important;
                color: white !important;*/
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
              /*  background: #1f58c7 !important;
                color: white !important;*/
            }  
            /* HTML: <div class="loader"></div> */
            .loader {
              width: 50px;
              aspect-ratio: 1;
              display: grid;
              animation: l14 4s infinite;
              position: absolute;
              top: 45%;
              left: 50%;
            }
            .loader::before,
            .loader::after {    
              content: "";
              grid-area: 1/1;
              border: 6px solid;
              border-radius: 50%;
              border-color: var(--app-color) ;
              mix-blend-mode: darken;
              animation: l14 1s infinite linear;
            }
            .loader::after {
              border-color: #0000 #0000 blue blue;
              animation-direction: reverse;
            }
            @keyframes l14{ 
              100%{transform: rotate(1turn)}
            }
            #preloader
            {
                text-align: center;
                height: 100%;
                width: 100%;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: rgba(255,255,255 , 0.9);
                z-index: 9999;
            }

            .textbox-grey {
                background: #8080803b !important;
            }
            .required {
                color:red;
            }
            .select2-selection--single {
                height: 35px !important;
            }
            .select2-selection__rendered {
                margin-top: 2px !important;
            }
            .change_select_width > .select2-container {
                width: 100% !important;
            }
            .cus_change_select > .select2-container > .selection > .select2-selection--single > .select2-selection__arrow {
                right: 12px;
            }
            input[type='text'],textarea,input[type='checkbox'] {
                border: 1px solid #808080b5 !important;
            }
            .f-14 {
                font-size: 14px;
            }
            input[type='search']:focus {
                outline: none;
                border: 1px solid blue;
            }

            .dt-buttons a {
                background: #1f58c7;
            }
            .product_division 
            { 
                pointer-events:none;
            }
            .avatar {
                height: 2rem !important;
                width: 2rem !important;
            }
            .table.dataTable {
                font-family: "Roboto", sans-serif;
                font-size: 14px;
                direction: ltr;
                position: relative;
                clear: both;
                zoom: 1;
            }

            .DTFC_LeftBodyLiner {
                overflow-y: unset !important;
            }

            .dis-none {
                display: none !important;
            }
            .widget_card_ht {
                height: 200px !important;
            }
            .td_align{
                text-align: right;
            }
            .report-head-bg {
                background: linear-gradient(90deg, rgba(0,0,36,1) 0%, rgba(67,67,175,1) 0%, rgba(0,212,255,1) 100%);
            }
            .table {
                cursor: pointer !important;
            }
            .v-middle {
                vertical-align: middle !important;
            }

            @keyframes blink {
                0% {
                    opacity:1;
                }
                50% {
                    opacity:0;
                }
                100% {
                    opacity:1;
                }
            } 

            .blink_anime {
                animation: blink 2s infinite ease-in;
            }

            .widget-shade {
                box-shadow: 7px 7px 5px #a5cad4;
/*                background-image: url(https://t4.ftcdn.net/jpg/02/10/45/95/360_F_210459536_XmLDEcKq2DpeNLVmheuWeu9NM9aGKnih.jpg);*/
/*                background-position: bottom;*/
            }
            .f-40 {
                font-size: 40px !important;
            }
            .text-truncate:active,.text-truncate:hover {
                overflow: visible;
            } 

            .f-30 {
                font-size: 30px !important;
            }

            .widget-body {
                height: 153px !important;
            }

            .dataTables_empty {
                vertical-align: middle !important;
            }

            @media only screen and (max-width : 992px) {
                .logo-dark {
                    margin-top: 27px;
                }    
                .footer {
                    left: 0px !important;
                }
            }

            .dt-search-br {
                border-color: #9b9191;
            }

            .indent-sumary-tbl {
                width: 20%;
            }

            /* template style changes start   */

            :root {
                --app-oldcolor: #4a3484;
              --app-color: #1475bf;
            }

            .vertical-menu {
                background: var(--app-color);
                border: none;
            }

            .navbar-brand-box {
                background: var(--app-color);
            }

            li > a > i,li > a > span  {
                color: white !important;
            }

            li > a:hover {
                color: black !important;
            }
            #sidebar-menu ul li.mm-active>a {
                 background-color: #ff00a2 !important;
            }


            .vertical-menu #sidebar-menu>ul>li:hover>a {
                background-color: #ff00a2 !important;
            }

            #sidebar-menu .has-arrow:after {
                color: white !important;
            }

            #sidebar-menu ul li ul.sub-menu:hover:before {
                content: "";
                position: absolute;
                left: 40px;
                top: 10px;
                bottom: 10px;
                width: 2px;
                background: var(--bs-sidebar-menu-sub-item-line-color);
            }

            #sidebar-menu ul .mm-active {
                background: none;
                color: #ff00a2;
            } 

            li > ul {
                background: var(--app-color) !important;
            }

            header {
                /*background: linear-gradient(90deg, rgba(252, 0, 160, 0.8352591036414566) 0%, rgba(74, 52, 132, 0.9136904761904762) 50%);*/
                box-shadow: 2px 2px 13px #afafaf;
            }

            body {
                  /*background: linear-gradient(90deg, rgba(252, 0, 160, 0.8352591036414566) 0%, rgba(74, 52, 132, 0.9136904761904762) 50%);*/
                  /*background: #dcdcdca3;*/
                  background: #eaf1f3;
            }

            #sidebar-menu > ul > li > ul > li > .active > span {
                color: white !important;
                background: #ff00a2;
                padding: 10px;
                border-radius: 10px;
            }

            footer {
                background: #eaf1f3 !important;
                box-shadow: 0px 0px 5px #8d7c7c !important;
            }

            .footer-text {
                color: var(--app-color) !important;
                font-weight: bold;   
            }

            .widget-card {
                height: 128px !important;
            }

            .main-content {
                margin-top: 15px;
                transition: margin-left .5s;
            }

            .vertical-menu,.isvertical-topbar,.navbar-brand-box {
                transition: all .5s;
            }

            .avatar-title {
                background: none !important;
            }

            .indent-avatar-bg {
                background-color: var(--app-color);
                border-radius: 50% !important;
            }

            .list_module_card {
                /* background-image: url(https://cdn.vectorstock.com/i/500p/06/38/gradient-background-simple-light-blue-design-vector-48520638.jpg); */
                background-size: cover;
                background-image: url(https://wallpapercave.com/wp/29FCznp.jpg);
                background-position: right;
                border-radius: 30px 30px 30px 30px;
                height: 1000px;
            }

            .dataTables_info {
                color: white !important;
            }

            .dataTables_paginate {
                margin-right: 425px;
                margin-top: 40px;
            }

            .paginate_button,.ellipsis  {
                background: white !important;
                border-radius: 50% !important;
            }

            .paginate_button.current  {
                background: #ff00a2 !important;
                border-radius: 50%;
                border: none !important;
                color: white !important;
            }

            .paginate_button.next {
                width: 83px !important;
            }

            .paginate_button:hover {
                background: #ff00a2 !important;
                color: white !important;
                border: none !important;
            }

            .tbl_head tr th{
                background: var(--app-color) !important;
                color: white !important;
            }

            thead tr th {
                background: #337aaf33 !important;
                color: white !important;
                border-bottom: none !important;
            }

            @media (min-width: 1200px) {
                /*.toastui-chart-wrapper canvas {
                    width: 800px !important;
                }*/
            }

            /* template style changes end   */

        </style>

    </head>

    
    <body data-sidebar-size='sm'>

     <div  id="preloader">
         <div class="loader"></div>
     </div>   