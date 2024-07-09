            <?php  include('partials/head.php'); ?>
            <!-- <body data-layout="horizontal"> -->

            <!-- Begin page -->
            <div id="layout-wrapper">
                <?php include('partials/topbar.php'); ?>

                <!-- ========== Left Sidebar Start ========== -->
                <?php include('partials/sidebar.php'); ?>
                <!-- Left Sidebar End -->
                <?php include('partials/horizontal_header.php'); ?>

                <?php 
                    include('Current_User_Session_Details.php');
                    if(isset($_REQUEST['submit']))
                    {
                        include('index_store.php');
                    }
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

                    $dashboard_det="EXEC Sales_Indent_STO_Widget_Count_Details @Emp_Id='".$_SESSION['EmpID']."',@Dcode='".$_SESSION['Dcode']."'";
                    // Approve_Count_With_In_Limit,Recommendation_Count_Limit_Exceed,Approve_Count_Limit_Exceed,Completed_Count,Approve_Qty_With_In_Limit,Recommendation_Qty_Limit_Exceed,Approve_Qty_Limit_Exceed

                    $salesadv       = sqlsrv_query($conn,$dashboard_det);
                    $fetch_wait     = sqlsrv_fetch_array($salesadv);
                    $Completed_Count        = $fetch_wait['Completed_Count'];
                    // $Validation_Count       = $fetch_wait['Validation_Count'];
                    $Approve_Count          = $fetch_wait['Approve_Count'];
                    $Total_Count            = $fetch_wait['Total_Count'];
                    $Rejection_Count        = $fetch_wait['Rejection_Count'];
                    // $Validation_qty         = $fetch_wait['Validation_qty'];
                    $Approve_qty            = $fetch_wait['Approve_qty'];

                    $Approve_Count_With_In_Limit=@$fetch_wait['Approve_Count_With_In_Limit'];
                    $Recommendation_Count_Limit_Exceed=@$fetch_wait['Recommendation_Count_Limit_Exceed'];
                    $Approve_Count_Limit_Exceed=@$fetch_wait['Approve_Count_Limit_Exceed'];
                    $Completed_Count=@$fetch_wait['Completed_Count'];
                    $Approve_Qty_With_In_Limit=@$fetch_wait['Approve_Qty_With_In_Limit'];
                    $Recommendation_Qty_Limit_Exceed=@$fetch_wait['Recommendation_Qty_Limit_Exceed'];
                    $Approve_Qty_Limit_Exceed=@$fetch_wait['Approve_Qty_Limit_Exceed'];
 
                ?>
                <!-- ============================================================== -->
                <!-- Start right Content here -->
                <!-- ============================================================== -->
                <div class="main-content mt-4">
                    <div class="page-content">
                        <div class="container-fluid">
                            <div class="row">

                               <!--  <div class="col-xl-3 col-md-4">
                                    <a  href="sales_indent_Sto_Report.php">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body"  >
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="fas fa-bahai f-40 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-6 pt-3">
                                                        <p class="mb-0 font-size-16 text-center fw-medium">No Of STO Indent</p>
                                                        <div>
                                                            <input type="hidden" id="no_of_sto_indent_value" value="<?= !is_null($Total_Count) ? $Total_Count : 0; ?>">
                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="no_of_sto_indent">0 
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>

                                <?php if($Approver_For_Sto_Indent >0 ){ ?>

                                <div class="col-xl-3 col-md-4">
                                    <a  href="Sto_Validate_Sales_Indent_Report_With_Limit.php">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body"  >
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="fas fa-money-check f-30 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-8 pt-3">
                                                        <p class="mb-0 font-size-16 text-center fw-medium text-nowrap">Pending Validation <br>(With In Limit)</p>
                                                        <div>
                                                            <input type="hidden" id="pending_sto_validate_winlt_value" value="<?= !is_null($Sto_validate_Count_With_In_Limit) ? $Sto_validate_Count_With_In_Limit : 0; ?>">
                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="pending_sto_validate_winlt">0 
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>

                                <?php } ?> -->

                               <!--  <?php if($Validate_For_Sto_Indent >0 ){ ?>
                                <div class="col-xl-3 col-md-4">
                                    <a  href="Sto_Approve_Sales_Indent_Report.php">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body"  >
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="fas fa-ribbon f-30 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-6 pt-3">
                                                        <p class="mb-0 font-size-16 text-center fw-medium">Pending Approval <br>(With In Limit)</p>
                                                        <div>
                                                            <input type="hidden" id="pending_sto_approval_winlt_value" value="<?= !is_null($Sto_Approve_Count_With_In_Limit) ? $Sto_Approve_Count_With_In_Limit : 0; ?>">
                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="pending_sto_approval_winlt">0 
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <?php } ?> -->


                              <!--   <?php if($Approver_For_Sto_Indent >0 ){ ?>

                                <div class="col-xl-3 col-md-4">
                                    <a  href="Sto_Recommend_Sales_Indent_Report.php">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body"  >
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="fas fa-receipt f-30 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-8 pt-3">
                                                        <p class="mb-0 font-size-16 text-center fw-medium text-nowrap">Pending Recommendation <br>(Limit Exceed)</p>
                                                        <div>
                                                            <input type="hidden" id="pending_sto_recommend_le_value" value="<?= !is_null($Sto_Recommendation_Count_Limit_Exceed) ? $Sto_Recommendation_Count_Limit_Exceed : 0; ?>">
                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="pending_sto_recommend_le">0 
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>

                                <?php } ?>
 -->

                                <!-- <?php if($Validate_For_Sto_Indent >0 ){ ?>

                                <div class="col-xl-3 col-md-4">
                                    <a  href="Sto_Validate_Sales_Indent_Report_limit_Exceed.php">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body"  >
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="fas fa-search-minus f-30 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-8 pt-3">
                                                        <p class="mb-0 font-size-16 text-center fw-medium text-nowrap">Pending Validation <br>(Limit Exceed)</p>
                                                        <div>
                                                            <input type="hidden" id="pending_sto_validate_le_value" value="<?= !is_null($sto_validate_Count_Limit_Exceed) ? $sto_validate_Count_Limit_Exceed : 0; ?>">
                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="pending_sto_validate_le">0 
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <?php } ?> -->


                            <!--     <?php  if($Approver_Menu_Limit_Exceed_For_Sto_Indent >0 ){ ?>
                                <div class="col-xl-3 col-md-4">
                                    <a  href="Sto_Approve_Limit_Exceed_Sales_Indent_Report.php">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body"  >
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="fas fa-tasks f-30 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-8 pt-3">
                                                        <p class="mb-0 font-size-16 text-center fw-medium text-nowrap">Pending Approve <br>(Limit Exceed)</p>
                                                        <div>
                                                            <input type="hidden" id="pending_sto_approve_le_value" value="<?= !is_null($sto_Approve_Count_Limit_Exceed) ? $sto_Approve_Count_Limit_Exceed : 0; ?>">
                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="pending_sto_approve_le">0 
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <?php } ?> -->

                              <!--   <div class="col-xl-3 col-md-4">
                                    <a  href="sales_indent_Sto_Report.php?Status=<?=safe_encode('4')?>">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body"  >
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="fas fa-check-double f-30 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-8 pt-3">
                                                        <p class="mb-0 font-size-16 text-center fw-medium text-nowrap">Completed</p>
                                                        <div>
                                                            <input type="hidden" id="completed_sto_value" value="<?= !is_null($Completed_Count) ? $Completed_Count : 0; ?>">
                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="completed_sto">0 
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div> -->

                             <!--    <div class="col-xl-3 col-md-4">
                                    <a  href="sales_indent_Sto_Report.php?Status=<?=safe_encode('5')?>">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body"  >
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="fas fa-times f-30 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-8 pt-3">
                                                        <p class="mb-0 font-size-16 text-center fw-medium text-nowrap">Rejection</p>
                                                        <div>
                                                            <input type="hidden" id="rejected_sto_value" value="<?= !is_null($Rejection_Count) ? $Rejection_Count : 0; ?>">
                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="rejected_sto">0 
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>
 -->


                            </div>

                            <div class="row">
                                <div class="col-md-6 col-xl-3">
                                    <a  href="sales_indent_Sto_Report.php" >
                                        <div class="card widget-rounded-circle widget-shade widget-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="avatar-lg indent-avatar-bg d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-bahai f-30 text-white" style="border-radius: 50%;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-end">
                                                            <input type="hidden" id="no_of_sto_indent_value" value="<?= !is_null($Total_Count) ? $Total_Count : 0; ?>">

                                                            <h3 class="text-dark mt-1"><span data-plugin="counterup" id="no_of_sto_indent">0</span></h3>
                                                            <p class="mb-1 text-truncate">Total STO Indents</p>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> 
                                    </a>
                                </div>

                                <?php if($Approver_For_Sto_Indent >0 ){ ?>
                                    <div class="col-md-6 col-xl-3">
                                        <a  href="Sto_Validate_Sales_Indent_Report_With_Limit.php" >
                                            <div class="card widget-rounded-circle widget-shade widget-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="avatar-lg indent-avatar-bg d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-money-check f-30 text-white" style="border-radius: 50%;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="text-end">
                                                                <input type="hidden" id="pending_sto_validate_winlt_value" value="<?= !is_null($Sto_validate_Count_With_In_Limit) ? $Sto_validate_Count_With_In_Limit : 0; ?>">

                                                                <h3 class="text-dark mt-1"><span data-plugin="counterup" id="pending_sto_validate_winlt">0</span></h3>
                                                                <p class="mb-1 text-truncate">Pending Validation <br>(With In Limit)</p>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div> 
                                        </a>
                                    </div>
                                <?php } ?>

                                <?php if($Validate_For_Sto_Indent >0 ){ ?>
                                    <div class="col-md-6 col-xl-3">
                                        <a  href="Sto_Approve_Sales_Indent_Report.php" >
                                            <div class="card widget-rounded-circle widget-shade widget-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="avatar-lg indent-avatar-bg d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-ribbon f-30 text-white" style="border-radius: 50%;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="text-end">
                                                             <input type="hidden" id="pending_sto_approval_winlt_value" value="<?= !is_null($Sto_Approve_Count_With_In_Limit) ? $Sto_Approve_Count_With_In_Limit : 0; ?>">

                                                                <h3 class="text-dark mt-1"><span data-plugin="counterup" id="pending_sto_approval_winlt">0</span></h3>
                                                                <p class="mb-1 text-truncate">Pending Approval <br>(With In Limit)</p>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div> 
                                        </a>
                                    </div>
                                <?php } ?>

                                <?php if($Approver_For_Sto_Indent >0 ){ ?>
                                    <div class="col-md-6 col-xl-3">
                                        <a  href="Sto_Recommend_Sales_Indent_Report.php" >
                                            <div class="card widget-rounded-circle widget-shade widget-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="avatar-lg indent-avatar-bg d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-receipt f-30 text-white" style="border-radius: 50%;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="text-end">
                                                            <input type="hidden" id="pending_sto_recommend_le_value" value="<?= !is_null($Sto_Recommendation_Count_Limit_Exceed) ? $Sto_Recommendation_Count_Limit_Exceed : 0; ?>">

                                                                <h3 class="text-dark mt-1"><span data-plugin="counterup" id="pending_sto_recommend_le">0</span></h3>
                                                                <p class="mb-1 text-truncate">Pending Recommendation <br>(Limit Exceed)</p>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div> 
                                        </a>
                                    </div>
                                <?php } ?>

                                <?php if($Validate_For_Sto_Indent >0 ){ ?>
                                    <div class="col-md-6 col-xl-3">
                                        <a  href="Sto_Validate_Sales_Indent_Report_limit_Exceed.php" >
                                            <div class="card widget-rounded-circle widget-shade widget-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="avatar-lg indent-avatar-bg d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-search-minus f-30 text-white" style="border-radius: 50%;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="text-end">
                                                            <input type="hidden" id="pending_sto_validate_le_value" value="<?= !is_null($sto_validate_Count_Limit_Exceed) ? $sto_validate_Count_Limit_Exceed : 0; ?>">

                                                                <h3 class="text-dark mt-1"><span data-plugin="counterup" id="pending_sto_validate_le">0</span></h3>
                                                                <p class="mb-1 text-truncate">Pending Validation <br>(Limit Exceed)</p>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div> 
                                        </a>
                                    </div>
                                <?php } ?>

                                <?php  if($Approver_Menu_Limit_Exceed_For_Sto_Indent >0 ){ ?>
                                    <div class="col-md-6 col-xl-3">
                                        <a  href="Sto_Approve_Limit_Exceed_Sales_Indent_Report.php" >
                                            <div class="card widget-rounded-circle widget-shade widget-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="avatar-lg indent-avatar-bg d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-tasks f-30 text-white" style="border-radius: 50%;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="text-end">
                                                            <input type="hidden" id="pending_sto_approve_le_value" value="<?= !is_null($sto_Approve_Count_Limit_Exceed) ? $sto_Approve_Count_Limit_Exceed : 0; ?>">

                                                                <h3 class="text-dark mt-1"><span data-plugin="counterup" id="pending_sto_approve_le">0</span></h3>
                                                                <p class="mb-1 text-truncate">Pending Approval <br>(Limit Exceed)</p>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div> 
                                        </a>
                                    </div>
                                <?php } ?>

                                    <div class="col-md-6 col-xl-3">
                                        <a  href="sales_indent_Sto_Report.php?Status=<?=safe_encode('4')?>" >
                                            <div class="card widget-rounded-circle widget-shade widget-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="avatar-lg indent-avatar-bg d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-check-double f-30 text-white" style="border-radius: 50%;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="text-end">
                                                            <input type="hidden" id="completed_sto_value" value="<?= !is_null($Completed_Count) ? $Completed_Count : 0; ?>">

                                                                <h3 class="text-dark mt-1"><span data-plugin="counterup" id="completed_sto">0</span></h3>
                                                                <p class="mb-1 text-truncate">Completed</p>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div> 
                                        </a>
                                    </div>

                                    <div class="col-md-6 col-xl-3">
                                        <a  href="sales_indent_Sto_Report.php?Status=<?=safe_encode('5')?>" >
                                            <div class="card widget-rounded-circle widget-shade widget-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="avatar-lg indent-avatar-bg d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-times f-30 text-white" style="border-radius: 50%;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="text-end">
                                                            <input type="hidden" id="rejected_sto_value" value="<?= !is_null($Rejection_Count) ? $Rejection_Count : 0; ?>">

                                                                <h3 class="text-dark mt-1"><span data-plugin="counterup" id="rejected_sto">0</span></h3>
                                                                <p class="mb-1 text-truncate">Rejected</p>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div> 
                                        </a>
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
                    $('.page-title').text('STO Dashboard');
                    $('.page-title').before("<i class='fas fa-tachometer-alt icon font-size-20 me-2 text-primary'></i>");
                    $('.flatpickr-input').flatpickr();
                    countto('no_of_sto_indent',$('#no_of_sto_indent_value').val());
                    countto('pending_sto_approval_winlt',$('#pending_sto_approval_winlt_value').val());
                    countto('pending_sto_validate_winlt',$('#pending_sto_validate_winlt_value').val());
                    countto('pending_sto_recommend_le',$('#pending_sto_recommend_le_value').val());
                    countto('pending_sto_validate_le',$('#pending_sto_validate_le_value').val());
                    countto('pending_sto_approve_le',$('#pending_sto_approve_le_value').val());
                    countto('completed_sto',$('#completed_sto_value').val());
                    countto('rejected_sto',$('#rejected_sto_value').val());



                });
            </script>

            </body>

            </html>