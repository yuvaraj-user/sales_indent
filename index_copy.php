            <?php  
            include('partials/head.php');             
            ?>
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

              $dashboard_det="EXEC Sales_Indent_Widget_Count_Details @Emp_Id='".$_SESSION['EmpID']."',@Dcode='".$_SESSION['Dcode']."'";
              $salesadv       = sqlsrv_query($conn,$dashboard_det);
              $fetch_wait   = sqlsrv_fetch_array($salesadv);
              $Completed_Count        = @$fetch_wait['Completed_Count'];
              $Validation_Count       = @$fetch_wait['Validation_Count'];
              $Approve_Count          = @$fetch_wait['Approve_Count'];
              $Total_Count          = @$fetch_wait['Total_Count'];
              $Total_Qty          = @$fetch_wait['Total_Qty'];

              $Rejection_Count          = @$fetch_wait['Rejection_Count'];

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
              $Rejection_Qty=@$fetch_wait['Rejection_Qty'];

              $Validation_With_In_Limit_Qty=@$fetch_wait['Validation_With_In_Limit_Qty'];
              ?>
              <!-- ============================================================== -->
              <!-- Start right Content here -->
              <!-- ============================================================== -->
              <div class="main-content mt-4">
                <div class="page-content">
                       <!--  <div class="container-fluid">
                            <div class="row">


                                <div class="col-xl-3 col-md-4">
                                    <a <?php if($_SESSION['EmpID'] == 'RS6175') { ?> href="Sales_Indent_Zone_Report.php?Status=<?=safe_encode('1')?>" <?php } else { ?> href="sales_indent_report.php?Status=<?=safe_encode('0')?>" <?php }?>>
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body"  >
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="bx bx-store f-40 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-6 pt-3">
                                                        <p class="mb-0 font-size-16 text-center fw-medium">No Of Indent</p>
                                                        <div>
                                                            <input type="hidden" id="no_of_indent_value" value="<?= !is_null($Total_Count) ? $Total_Count : 0; ?>">
                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="no_of_indent">0 
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>


                            <?php if($Recommend_Menu >0 ){ ?>


                                <div class="col-xl-3 col-md-4">
                                    <a href="Approve_Sales_Indent_Report.php">
                                    <div class="card widget-shade">
                                        <div class="card-body widget-body">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="bx bx-check-shield f-40 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-7 pt-3">
                                                        <p class="mb-0 font-size-16 text-center fw-medium">Pending Approval<br><span class="mt-2">(With In Limit)</span></p>
                                                        <div>
                                                            <input type="hidden" id="approval_winlt_value" value="<?= !is_null($indent_Approve_Count_wilimit) ? $indent_Approve_Count_wilimit : 0; ?>">
                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="approval_winlt">0 
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>




                                <div class="col-xl-3 col-md-4">
                                    <a href="Sales_Indent_Recommendation.php">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="bx bx-analyse f-40 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-9 pt-3">
                                                        <p class="mb-0 font-size-16 text-center fw-medium">Pending Recommendation<br><span class="mt-2">(Limit Exceed)</span></p>
                                                        <div>
                                                            <input type="hidden" id="recommend_le_value" value="<?= !is_null($indent_Recommedation_Count_lexceed) ? $indent_Recommedation_Count_lexceed : 0; ?>">

                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="recommend_le">0
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

                            <?php if($Validate_Menu >0) { ?>
                                <div class="col-xl-3 col-md-4">
                                    <a href="Sales_Indent_Validation.php">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="mdi mdi-shield-half-full f-40 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-7 pt-3">
                                                        <p class="mb-0 font-size-16 text-center text-truncate fw-medium">Pending Validation <br><span class="mt-2">(Limit Exceed)</span></p>
                                                        <div>
                                                            <input type="hidden" id="validate_le_value" value="<?= !is_null($indent_Validation_Limit_Exceed_Count) ? $indent_Validation_Limit_Exceed_Count : 0; ?>">

                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="validate_le">0
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>


                                <div class="col-xl-3 col-md-4">
                                    <a href="Approve_Sales_Indent_Report_Direct.php">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="fas fa-chart-pie f-40 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-7 pt-3">
                                                        <p class="mb-0 font-size-16 text-center text-truncate fw-medium">Pending Approval<br><span class="mt-2">(Direct) (With In Limit)</span></p>
                                                        <div>
                                                            <input type="hidden" id="validate_winlt_value" value="<?= !is_null($indent_approve_direct_withinlimit) ? $indent_approve_direct_withinlimit : 0; ?>">

                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="validate_winlt">0
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

                            <?php if($Approve_limit_Exceed_Menu  >0){ ?>

                                <div class="col-xl-3 col-md-4">
                                    <a href="Sales_Indent_Approve_for_Limit_Exceed.php">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="fas fa-check-circle f-40 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-7 pt-3 text-center">
                                                        <p class="mb-0 font-size-16 text-truncate fw-medium">Pending Apporval <br><span class="mt-2">(Limit Exceed)</span></p>
                                                        <div>
                                                            <input type="hidden" id="approve_le_value" value="<?= !is_null($indent_Approve_Count_Limit_Exceed_Count) ? $indent_Approve_Count_Limit_Exceed_Count : 0; ?>">

                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="approve_le">0
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


                                <div class="col-xl-3 col-md-4">
                                    <a href="sales_indent_report.php?Status=<?=safe_encode('5')?>">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="fas fa-check-double f-40 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-7 pt-3">
                                                        <p class="mb-0 font-size-15 text-center fw-medium">Completed</p>
                                                        <div>
                                                            <input type="hidden" id="completed_value" value="<?= !is_null($Completed_Count) ? $Completed_Count : 0; ?>">

                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="completed">0 
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>


                                <div class="col-xl-3 col-md-4">
                                    <a href="sales_indent_report.php?Status=<?=safe_encode('7')?>">
                                    <div class="card widget-shade" >
                                        <div class="card-body widget-body" >
                                            <div>
                                                <div class="d-flex align-items-center justify-content-around">
                                                    <div class="col-2">
                                                        <i class="fas fa-times f-40 mb-0 text-success"></i>
                                                    </div>
                                                    <div class="col-7 pt-3">
                                                        <p class="mb-0 font-size-16 text-center fw-medium">Rejection</p>
                                                        <div>
                                                            <input type="hidden" id="rejected_value" value="<?= !is_null($Rejection_Count) ? $Rejection_Count : 0; ?>">

                                                            <p class="pt-3 mb-0 font-size-22 text-center fw-bold" id="rejected">0
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>


                            </div>
                        </div> -->

                        <div class="row">
                            <div class="col-md-6 col-xl-3">
                                <a <?php if($_SESSION['EmpID'] == 'RS6175') { ?> href="Sales_Indent_Zone_Report.php?Status=<?=safe_encode('1')?>" <?php } else { ?> href="sales_indent_report.php?Status=<?=safe_encode('0')?>" <?php }?>>
                                    <div class="card widget-rounded-circle widget-shade widget-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="avatar-lg indent-avatar-bg">
                                                        <i class="bx bx-store f-30 text-white avatar-title" style="border-radius: 50%;"></i>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-end">
                                                        <input type="hidden" id="no_of_indent_value" value="<?= !is_null($Total_Count) ? $Total_Count : 0; ?>">

                                                        <h3 class="text-dark mt-1"><span data-plugin="counterup" id="no_of_indent">0</span></h3>
                                                        <p class="mb-1 text-truncate">Total Indents</p>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                    </div> 
                                </a>
                            </div> 

                            <?php if($Recommend_Menu >0 ){ ?>

                                <div class="col-md-6 col-xl-3">
                                    <a href="Approve_Sales_Indent_Report.php">
                                        <div class="card widget-rounded-circle widget-shade widget-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="avatar-lg indent-avatar-bg">
                                                            <i class="bx bx-check-shield f-30 text-white avatar-title" style="border-radius: 50%;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-end">
                                                            <input type="hidden" id="approval_winlt_value" value="<?= !is_null($indent_Approve_Count_wilimit) ? $indent_Approve_Count_wilimit : 0; ?>">
                                                            <h3 class="text-dark mt-1"><span data-plugin="counterup" id="approval_winlt">0</span></h3>
                                                            <p class="mb-1 text-truncate">pending approval <br>(with in limit)</p>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> 
                                    </a>
                                </div> 

                                <div class="col-md-6 col-xl-3">
                                    <a href="Sales_Indent_Recommendation.php">
                                        <div class="card widget-rounded-circle widget-shade widget-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <div class="avatar-lg indent-avatar-bg">
                                                            <i class="bx bx-analyse f-30 text-white avatar-title" style="border-radius: 50%;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-7">
                                                        <div class="text-end">
                                                            <input type="hidden" id="recommend_le_value" value="<?= !is_null($indent_Recommedation_Count_lexceed) ? $indent_Recommedation_Count_lexceed : 0; ?>">
                                                            <h3 class="text-dark mt-1"><span data-plugin="counterup" id="recommend_le">0</span></h3>
                                                            <p class="mb-1 text-truncate">pending recommendation <br>(Limit Exceed)</p>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> 
                                    </a>
                                </div> 

                            <?php } ?>

                            <?php if($Validate_Menu >0) { ?>

                                <div class="col-md-6 col-xl-3">
                                    <a href="Sales_Indent_Validation.php">
                                        <div class="card widget-rounded-circle widget-shade widget-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="avatar-lg indent-avatar-bg">
                                                            <i class="mdi mdi-shield-half-full f-30 text-white avatar-title" style="border-radius: 50%;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-end">
                                                            <input type="hidden" id="validate_le_value" value="<?= !is_null($indent_Validation_Limit_Exceed_Count) ? $indent_Validation_Limit_Exceed_Count : 0; ?>">

                                                            <h3 class="text-dark mt-1"><span data-plugin="counterup" id="validate_le">0</span></h3>
                                                            <p class="mb-1 text-truncate">pending validation <br>(Limit Exceed)</p>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> 
                                    </a>
                                </div>

                                <div class="col-md-6 col-xl-3">
                                    <a href="Approve_Sales_Indent_Report_Direct.php">
                                        <div class="card widget-rounded-circle widget-shade widget-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="avatar-lg indent-avatar-bg d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-chart-pie f-30 text-white" style="border-radius: 50%;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-end">
                                                          <input type="hidden" id="validate_winlt_value" value="<?= !is_null($indent_approve_direct_withinlimit) ? $indent_approve_direct_withinlimit : 0; ?>">

                                                          <h3 class="text-dark mt-1"><span data-plugin="counterup" id="validate_winlt">0</span></h3>
                                                          <p class="mb-1 text-truncate">Pending Approval <br>(Direct) (with in limit)</p>
                                                      </div>
                                                  </div>
                                              </div> 
                                          </div>
                                      </div> 
                                  </a>
                              </div>

                            <?php } ?>

                              <?php if($Approve_limit_Exceed_Menu >0) { ?>
                                <div class="col-md-6 col-xl-3">
                                    <a href="Sales_Indent_Approve_for_Limit_Exceed.php">
                                        <div class="card widget-rounded-circle widget-shade widget-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="avatar-lg indent-avatar-bg">
                                                            <i class="fas fa-check-circle f-30 text-white avatar-title" style="border-radius: 50%;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-end">
                                                            <input type="hidden" id="approve_le_value" value="<?= !is_null($indent_Approve_Count_Limit_Exceed_Count) ? $indent_Approve_Count_Limit_Exceed_Count : 0; ?>">

                                                            <h3 class="text-dark mt-1"><span data-plugin="counterup" id="approve_le">0</span></h3>
                                                            <p class="mb-1 text-truncate">Pending Approval <br> (limit exceed)</p>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> 
                                    </a>
                                </div>
                              <?php }?>


                            <!-- requestors widgets start (only show for requestors) -->

                            <?php if($requeter_menu > 0 && $Validate_Menu == 0 && $Recommend_Menu == 0 && $Approve_limit_Exceed_Menu == 0) { ?>
                                <div class="col-md-6 col-xl-3">
                                    <a href="sales_indent_report.php?Status=<?=safe_encode('1')?>">
                                        <div class="card widget-rounded-circle widget-shade widget-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="avatar-lg indent-avatar-bg">
                                                            <i class="bx bx-check-shield f-30 text-white avatar-title" style="border-radius: 50%;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-end">
                                                            <input type="hidden" id="approval_winlt_value" value="<?= !is_null($requester_withinlimit_approval_pending_count) ? $requester_withinlimit_approval_pending_count : 0; ?>">
                                                            <h3 class="text-dark mt-1"><span data-plugin="counterup" id="approval_winlt">0</span></h3>
                                                            <p class="mb-1 text-truncate">Waitting For Approval <br>(with in limit)</p>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> 
                                    </a>
                                </div> 

                                <div class="col-md-6 col-xl-3">
                                    <a href="sales_indent_report.php?Status=<?=safe_encode('6')?>">
                                        <div class="card widget-rounded-circle widget-shade widget-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="avatar-lg indent-avatar-bg d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-chart-pie f-30 text-white" style="border-radius: 50%;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-end">
                                                          <input type="hidden" id="validate_winlt_value" value="<?= !is_null($requester_withinlimit_approval_direct_pending_count) ? $requester_withinlimit_approval_direct_pending_count : 0; ?>">

                                                          <h3 class="text-dark mt-1"><span data-plugin="counterup" id="validate_winlt">0</span></h3>
                                                          <p class="mb-1 text-truncate">Waitting For Approval <br>(Direct) (with in limit)</p>
                                                      </div>
                                                  </div>
                                              </div> 
                                          </div>
                                      </div> 
                                  </a>
                              </div>

                                <div class="col-md-6 col-xl-3">
                                    <a href="sales_indent_report.php?Status=<?=safe_encode('2')?>">
                                        <div class="card widget-rounded-circle widget-shade widget-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <div class="avatar-lg indent-avatar-bg">
                                                            <i class="bx bx-analyse f-30 text-white avatar-title" style="border-radius: 50%;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-7">
                                                        <div class="text-end">
                                                            <input type="hidden" id="recommend_le_value" value="<?= !is_null($requester_limitexceed_recommend_pending_count) ? $requester_limitexceed_recommend_pending_count : 0; ?>">
                                                            <h3 class="text-dark mt-1"><span data-plugin="counterup" id="recommend_le">0</span></h3>
                                                            <p class="mb-1 text-truncate">Waitting For Recommendation <br>(limit exceed)</p>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> 
                                    </a>
                                </div> 


                                <div class="col-md-6 col-xl-3">
                                    <a href="sales_indent_report.php?Status=<?=safe_encode('3')?>">
                                        <div class="card widget-rounded-circle widget-shade widget-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="avatar-lg indent-avatar-bg">
                                                            <i class="mdi mdi-shield-half-full f-30 text-white avatar-title" style="border-radius: 50%;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-end">
                                                            <input type="hidden" id="validate_le_value" value="<?= !is_null($requester_limitexceed_validate_pending_count) ? $requester_limitexceed_validate_pending_count : 0; ?>">

                                                            <h3 class="text-dark mt-1"><span data-plugin="counterup" id="validate_le">0</span></h3>
                                                            <p class="mb-1 text-truncate">Waitting For Validation <br>(Limit Exceed)</p>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> 
                                    </a>
                                </div>

                                <div class="col-md-6 col-xl-3">
                                    <a href="sales_indent_report.php?Status=<?=safe_encode('4')?>">
                                        <div class="card widget-rounded-circle widget-shade widget-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="avatar-lg indent-avatar-bg">
                                                            <i class="fas fa-check-circle f-30 text-white avatar-title" style="border-radius: 50%;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-end">
                                                            <input type="hidden" id="approve_le_value" value="<?= !is_null($requester_limitexceed_approval_pending_count) ? $requester_limitexceed_approval_pending_count : 0; ?>">

                                                            <h3 class="text-dark mt-1"><span data-plugin="counterup" id="approve_le">0</span></h3>
                                                            <p class="mb-1 text-truncate">Waitting For Approval <br> (limit exceed)</p>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> 
                                    </a>
                                </div>


                            <?php } ?>
                            <!-- requestors widgets end (only show for requestors) -->

                            <div class="col-md-6 col-xl-3">
                                <a href="sales_indent_report.php?Status=<?=safe_encode('5')?>">
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
                                                        <input type="hidden" id="completed_value" value="<?= !is_null($Completed_Count) ? $Completed_Count : 0; ?>">

                                                        <h3 class="text-dark mt-1"><span data-plugin="counterup" id="completed">0</span></h3>
                                                        <p class="mb-1 text-truncate">Completed</p>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                    </div> 
                                </a>
                            </div>

                            <div class="col-md-6 col-xl-3">
                                <a href="sales_indent_report.php?Status=<?=safe_encode('7')?>">
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
                                                        <input type="hidden" id="rejected_value" value="<?= !is_null($Rejection_Count) ? $Rejection_Count : 0; ?>">

                                                        <h3 class="text-dark mt-1"><span data-plugin="counterup" id="rejected">0</span></h3>
                                                        <p class="mb-1 text-truncate">Rejected</p>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                    </div> 
                                </a>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-xl-6 col-md-12 col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="col-xl-3 col-md-3">
                                                <h5 class="card-title mb-0 text-primary">With in Limit Sales</h5>
                                            </div>
                                            <div class="col-xl-3 col-md-3 d-flex align-items-center">
                                                <label class="me-2">Division </label>
                                                <select class="js-example-basic-single indent_product_division" error-msg="Division Field is Required" id="" style="width: 100% !important;" data-type="with_in_limit">
                                                    <option>Select Division</option>
                                                </select>
                                            </div>
                                            <div class="flex-shrink-0 col-xl-3 col-md-3">
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="fw-semibold">Sort By:</span>
                                                        <span class="text-muted">Current Season<i class="mdi mdi-chevron-down ms-1"></i></span>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <!-- <a class="dropdown-item" href="#">Yearly</a> -->
                                                        <a class="dropdown-item" href="#">Current Season</a>
                                                        <!-- <a class="dropdown-item" href="#">This Week</a> -->
                                                        <!-- <a class="dropdown-item" href="#">Today</a> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                  <!--   <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h4 class="font-size-22">$23,590.00</h4>
                                            </div>
                                            <div class="col-md-8">
                                                <ul class="list-inline main-chart text-md-end mb-0">
                                                    <li class="list-inline-item chart-border-left me-0 border-0">
                                                        <h4 class="text-primary font-size-22">$584k <span class="text-muted d-inline-block font-size-14 align-middle ms-2">Incomes</span></h4>
                                                    </li>
                                                    <li class="list-inline-item chart-border-left me-0">
                                                        <h4 class="font-size-22">$497k<span class="text-muted d-inline-block font-size-14 align-middle ms-2">Expenses</span>
                                                        </h4>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div>
                                        </div>  
                                    </div> -->
                                     <div class="card-body bg-light">
                                        <div id="column-charts"></div>
        
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-12 col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="col-xl-3 col-md-3">
                                                <h5 class="card-title mb-0 text-primary">Limit Exceed Sales</h5>
                                            </div>
                                            <div class="col-xl-3 col-md-3 d-flex align-items-center">
                                                <label class="me-2">Division </label>
                                                <select class="js-example-basic-single indent_product_division" error-msg="Division Field is Required" id="" style="width: 100% !important;" data-type="limit_exceed">
                                                    <option>Select Division</option>
                                                </select>
                                            </div>
                                            <div class="flex-shrink-0 col-xl-3 col-md-3">
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="fw-semibold">Sort By:</span>
                                                        <span class="text-muted">Current Season<i class="mdi mdi-chevron-down ms-1"></i></span>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <!-- <a class="dropdown-item" href="#">Yearly</a> -->
                                                        <a class="dropdown-item" href="#">Current Season</a>
                                                        <!-- <a class="dropdown-item" href="#">This Week</a> -->
                                                        <!-- <a class="dropdown-item" href="#">Today</a> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body bg-light">
                                        <div id="bar-charts"></div>    
                                    </div>
                                </div>
                            </div>

                             <div class="col-xl-4 col-md-12 col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-0">Top Sale Division</h5>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Today<i class="mdi mdi-chevron-down ms-1"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Yearly</a>
                                                        <a class="dropdown-item" href="#">Monthly</a>
                                                        <a class="dropdown-item" href="#">Weekly</a>
                                                        <a class="dropdown-item" href="#">Today</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div id="top_sale_division"></div>
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
        <!-- <script src="assets/js/pages/tui-charts.init.js"></script> -->

        <script type="text/javascript">

            $(document).ready(function(){
                $('.page-title').text('Indent Dashboard');
                $('.page-title').before("<i class='bx bx-home-alt icon font-size-20 me-2 text-primary'></i>");
                $('.flatpickr-input').flatpickr();
                countto('no_of_indent',$('#no_of_indent_value').val());
                countto('approval_winlt',$('#approval_winlt_value').val());
                countto('validate_winlt',$('#validate_winlt_value').val());
                countto('recommend_le',$('#recommend_le_value').val());
                countto('validate_le',$('#validate_le_value').val());
                countto('approve_le',$('#approve_le_value').val());
                countto('completed',$('#completed_value').val());
                countto('rejected',$('#rejected_value').val());

                $.ajax({
                  type: "POST",
                  url: "Ajax.php",
                  data:{"Action":"sales_indent_turnover_amount","sortby":'current_season'},
                  success: function(data){
                    result = JSON.parse(data);
                    var options = '<option>Select Division</option>';
                    for(i in result.mapped_all_division) {
                        var selected = (i == 0) ? 'selected' : '';
                        options += `<option value="${ result.mapped_all_division[i].Division }" ${selected}>${ result.mapped_all_division[i].Sales_Org }</option>`;
                    }

                    $('.indent_product_division').html(options);
                    $('.indent_product_division').select2();
                    sales_report_column_chart(result);
                    sales_report_bar_chart(result);
                    top_sale_division_piechart();
                   }
                 });


            });

            $(document).on('change','.indent_product_division',function(){
                var type = $(this).data('type');
                var product_division = $(this).val();

                $.ajax({
                  type: "POST",
                  url: "Ajax.php",
                  data:{"Action":"sales_indent_turnover_amount","sortby":'current_season',"product_division": product_division},
                  success: function(data){
                    result = JSON.parse(data);
                    console.log(type);
                        if(type == 'with_in_limit') {
                            sales_report_column_chart(result,'update');
                        } else if(type == 'limit_exceed') {
                            sales_report_bar_chart(result);
                        }
                   }
                 });

            });


            function sales_report_column_chart(arr_data,status = '')
            {   
                const data = {
                    categories: arr_data.category,
                    series: [
                        {
                            name: 'Pending',
                            data: arr_data.With_in_limit_pending,
                        },
                        {
                            name: 'Approved',
                            data: arr_data.With_in_limit_Approved,
                        },
                        {
                            name: 'Rejected',
                            data: arr_data.With_in_limit_rejected,
                        }
                    ],
                };
                const options = {
                    chart: { 
                        title: 'Monthly Sales', 
                        width: 650,
                        height: 500 
                    },
                    series: {
                        eventDetectType: 'grouped',
                        dataLabels: {
                            // visible: true 
                        },
        
                    },
                    xAxis: {
                        title: 'Month',
                    },
                    yAxis: {
                        title: 'Amount',
                        scale: {
                          min: 0,
                          max: 5,
                          stepSize: 1,
                        },
                        label: {
                            formatter: (value) => {
                                return `${value} L`;
                            },
                        },
                    },
                    theme: {
                        series: {
                          dataLabels: {
                            textBubble: {
                                // visible: true,
                            }
                          }
                      }
                  }
                };

                $('#column-charts').empty();
                const el = document.getElementById('column-charts');
                const chart = toastui.Chart.columnChart({el, data, options});

            }

            function sales_report_bar_chart(arr_data)
            {
                const data = {
                    categories: arr_data.category,
                    series: [
                        {
                            name: 'Pending',
                            data: arr_data.limit_exceed_pending,
                        },
                        {
                            name: 'Approved',
                            data: arr_data.limit_exceed_Approved,
                        },
                        {
                            name: 'Rejected',
                            data: arr_data.limit_exceed_rejected,
                        }
                    ],
                };
                const options = {
                    chart: { 
                        title: 'Monthly Sales', 
                        width: 650,
                        height: 500 
                    },
                    series: {
                        eventDetectType: 'grouped',
                        dataLabels: {
                            // visible: true 
                        },
        
                    },
                    xAxis: {
                        title: 'Amount',
                        scale: {
                          min: 0,
                          max: 5,
                          stepSize: 1,
                      },
                      label: {
                        formatter: (value) => {
                            return `${value} L`;
                        },
                    },
                    },
                    yAxis: {
                        title: 'Month',
                    },
                    theme: {
                        // series: {
                        //   dataLabels: {
                        //     textBubble: {
                        //         // visible: true,
                        //     }
                        //   }
                       series: {
                          colors: [
                            '#77e4bc',
                            '#de3aa9',
                            '#b33ade'
                            ],
                      }
                  }
                };

                $('#bar-charts').empty();
                const el = document.getElementById('bar-charts');
                const chart = toastui.Chart.barChart({el, data, options});

            }


            function top_sale_division_piechart()
            {
                const el = document.getElementById('top_sale_division');

                const data = {
                  categories: ['Browser'],
                  series: [
                  {
                      name: 'Chrome',
                      data: 46.02,
                  },
                  {
                      name: 'IE',
                      data: 20.47,
                  },
                  {
                      name: 'Firefox',
                      data: 17.71,
                  },
                  {
                      name: 'Safari',
                      data: 5.45,
                  },
                  {
                      name: 'Opera',
                      data: 3.1,
                  },
                  {
                      name: 'Etc',
                      data: 7.25,
                  }
                  ]
                }

                const options = {
                    chart: { 
                        title: 'Monthly Sales', 
                        width: 420,
                        height: 420 
                    },
                    series: {
                        selectable: true,
                        dataLabels: {
                          visible: true,
                          // pieSeriesName: { visible: true, anchor: 'outer' }
                      }
                    }
                }
                const chart = toastui.Chart.pieChart({el, data, options});

            }
        </script>

    </body>

    </html>