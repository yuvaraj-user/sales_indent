            <?php include('partials/head.php'); ?>
            <!-- <body data-layout="horizontal"> -->
            <?php 
            if(@$Validate_Menu == 0) {
                header('location: index.php');  
            }
            ?>

            <!-- Begin page -->
            <div id="layout-wrapper">
                <?php include('partials/topbar.php'); ?>

                <!-- ========== Left Sidebar Start ========== -->
                <?php include('partials/sidebar.php'); ?>
                <!-- Left Sidebar End -->
                <?php include('partials/horizontal_header.php'); ?>

                <?php 
                    include('Current_User_Session_Details.php');
                    $user_zone_id=$session_zone_id;
                    $user_region_id=$session_region_id;
                    $user_territory_id=$session_territory_id;
                    $user_product_division_array=$product_division_array;
                    $user_zone_array=explode(",",$user_zone_id);
                    $user_region_array=explode(",",$user_region_id);
                    $user_territory_array=explode(",",$user_territory_id);
                    if($_SESSION['Dcode'] == "ADMIN" || $_SESSION['Dcode'] == "SUPERADMIN")
                    {
                      $product_division_arr[0]['code']="ras";
                      $product_division_arr[0]['name']="Cotton";
                      $product_division_arr[1]['code']="fcm";
                      $product_division_arr[1]['name']="Field Crop";
                      $product_division_arr[2]['code']="frg";
                      $product_division_arr[2]['name']="Forage Seeds";
                    }else {
                      $sub_array=[];
                        foreach($product_division_array as $value){
                            if($value == "ras"){
                              $sub_array['code']="ras";
                              $sub_array['name']="Cotton";
                            }else if($value == "fcm"){
                              $sub_array['code']="fcm";
                              $sub_array['name']="Field Crop";
                            }else if($value == "frg"){
                              $sub_array['code']="frg";
                              $sub_array['name']="Forage Seeds";
                            }
                            $product_division_arr[]=$sub_array;
                        }
                    }

                    function Generate_Document_No($Emp_Id,$id='0'){
                      global $conn;
                      $Emp_Id=$Emp_Id !=''? strtoupper(trim($Emp_Id)) : "";
                      $Doc_No_Auto_Generation_Sql="Sales_Indent_Generate_Document_No @Emp_Id='".$Emp_Id."',@Id=".$id."";
                      $Doc_No_Auto_Generation_Dets=sqlsrv_query($conn,$Doc_No_Auto_Generation_Sql);
                      $Doc_No_Auto_Generation_Result = sqlsrv_fetch_array($Doc_No_Auto_Generation_Dets);
                      return $Anp_Doc_No_Generation_Id=$Doc_No_Auto_Generation_Result['PrimaryId'];
                    }
                    $Doc_No=Generate_Document_No($_SESSION['EmpID'],0);

                    echo $dashboard_det="EXEC Sales_Indent_Widget_Count_Details @Emp_Id='".$_SESSION['EmpID']."',@Dcode='".$_SESSION['Dcode']."'";
                    $salesadv       = sqlsrv_query($conn,$dashboard_det);
                    $fetch_wait   = sqlsrv_fetch_array($salesadv);
                    $Completed_Count        = $fetch_wait['Completed_Count'];
                    $Validation_Count       = $fetch_wait['Validation_Count'];
                    $Approve_Count          = $fetch_wait['Approve_Count'];
                    $Total_Count          = $fetch_wait['Total_Count'];
                    $Rejection_Count          = $fetch_wait['Rejection_Count'];
                    $Approve_Count=@$fetch_wait['Approve_Count'];
                    $Recommedation_Count=@$fetch_wait['Recommedation_Count'];
                    $Validation_Limit_Exceed_Count=@$fetch_wait['Validation_Limit_Exceed_Count'];
                    $Validation_With_In_Limit_Count=@$fetch_wait['Validation_With_In_Limit_Count'];
                    $Approve_Count_Limit_Exceed_Count=@$fetch_wait['Approve_Count_Limit_Exceed_Count'];
                    $Completed_Count=@$fetch_wait['Completed_Count'];
                    $Approve_qty=@$fetch_wait['Approve_qty'];
                    $Recommedation_Qty=@$fetch_wait['Recommedation_Qty'];
                    $Validation_Limit_Exceed_Qty=@$fetch_wait['Validation_Limit_Exceed_Qty'];
                    $Approve_Count_Limit_Exceed_Qty=@$fetch_wait['Approve_Count_Limit_Exceed_Qty'];
                    $Completed_Qty=@$fetch_wait['Completed_Qty'];
                    $Validation_With_In_Limit_Qty=@$fetch_wait['Validation_With_In_Limit_Qty'];
                ?>

                <!-- ============================================================== -->
                <!-- Start right Content here -->
                <!-- ============================================================== -->
                <div class="main-content">
                    <div class="page-content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xl-3 col-md-6">
                                    <div class="card border-success widget_card_ht">
                                        <div class="card-header bg-transparent border-success text-center d-flex bg-success align-items-center h-100">
                                            <!-- <img src="icon/icon2.png" class="img-fluid"> -->
                                            <i class="fas fa-chart-pie font-size-24 mb-0 text-white"></i>
                                            <h5 class="text-white flex-grow-1">Waiting For Validation (With In Limit)</h5>
                                        </div>


                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <!-- <img src="icon/icon2.png" class="img-fluid"> -->
                                                    <h5>Count : <span class="badge bg-primary"><?= !is_null($Validation_With_In_Limit_Count) ? $Validation_With_In_Limit_Count : 0; ?></span></h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5>Qty : <span class="badge bg-success"><?= !is_null($Validation_With_In_Limit_Qty) ? $Validation_With_In_Limit_Qty : 0; ?></span></h5>
                                                </div>
                                            </div>
                                        </div>
                                         <a href="Sales_Indent_Validation_With_In_Limit.php">
                                            <div class="card-footer bg-transparent border-success text-center fw-bold">View More <i class="fas fa-angle-double-right"></i></div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- container-fluid -->
                    </div>
                    <!-- End Page-content -->
                    <?php include('partials/footer.php') ?>
                </div>
                <!-- end main content-->

            </div>
            <!-- END layout-wrapper -->


            <?php //include('partials/right-sidebar.php') ?>
            <?php include('partials/bottom_scripts.php') ?>

            <script src="js/Indent_creation.js"></script>
            <script src="../common/checkSession.js"></script>

            <script type="text/javascript">
                $(document).ready(function(){
                    $('.page-title').text('Indent Approve (With In Limit)');
                    $('.flatpickr-input').flatpickr();
                });
            </script>

            </body>

            </html>