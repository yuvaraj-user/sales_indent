            <?php include('partials/head.php'); ?>
            <!-- <body data-layout="horizontal"> -->
            <?php 
            if(@$requeter_menu == 0) {
                header('location: index.php');  
            }
            ?>
                <style type="text/css">
                    thead tr th {
                        background: var(--app-color) !important;
                       color: white !important;
                   }
            </style>
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
                ?>

                <!-- ============================================================== -->
                <!-- Start right Content here -->
                <!-- ============================================================== -->
                <div class="main-content mt-4">
                    <div class="page-content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="form-horizontal form-label-left Sales_Indent_Submit" action="index_store.php" name="form_name"  role="form" method="POST" enctype="multipart/form-data" >  

                                         <input type="hidden" class="form_submit_value" name="form_submit_value" value="0">
                                         <input type="hidden" class="session_user_zone_id" name="session_user_zone_id" value="<?=@$user_zone_id?>">
                                         <input type="hidden" class="session_user_region_id" name="session_user_region_id" value="<?=@$user_region_id?>">
                                         <input type="hidden" class="session_user_territory_id" name="session_user_territory_id" value="<?=@$user_territory_id?>">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-4" style="color: rgb(31, 88, 199); display: flex;">Sale Order Indent Details</h5>
                                                <!-- <p class="col-xs-12 Sales_Indent_Div fw-bold" style="color: #1f58c7">Sale Order Indent Details</p> -->
                                                <div class="row">
                                                    <div class="form-group" style="line-height: 5px;">
                                                      <div class="row">

                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                          <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name" >Request ID</label>
                                                          <input type="text" class="form-control col-md-12 col-xs-12 required_for_valid textbox-grey mt-2" error-msg="Request ID field is required" name="ReqId" value="<?php echo $Doc_No ?>" readonly>
                                                          <label class="error_msg text-danger"></label>
                                                        </div>
                                                        
                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                            <div class="form-group">
                                                              <label for="control-label col-md-2 col-sm-2 col-xs-12" for="name">Request Date</label>
                                                               
                                                                  <input type="text" class="form-control col-md-12 col-xs-12 required_for_valid textbox-grey mt-2" name="ReqDate" value="<?php echo date('d-m-Y');?>" readonly>
                                                                   <label class="error_msg text-danger"></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                            <div class="form-group change_select_width">
                                                                <div class="mb-2">
                                                                    <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name" >Division<span class="required"> *</span> </label>
                                                                </div>
                                                                <select class="js-example-basic-single col-xs-12 product_division required_for_valid mt-2" error-msg="Division Field is Required" name="ProductDivision" >
                                                               
                                                                    <?php foreach($product_division_arr  as $value){?>
                                                                  <option value="<?=$value['code']?>"><?=$value['name']?></option>
                                                                  <?php } ?>
                                                                </select>
                                                                <label class="error_msg text-danger"></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                        <div class="form-group change_select_width">
                                                            <div class="mb-2">
                                                                <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name" >Quotation Type<span class="required"> *</span> </label>
                                                            </div>
                                                             <select class="js-example-basic-single col-xs-12 quot_type required_for_valid" error-msg="Quotation Type Field is Required" name="QuotationType" id="QuotationType" >
                                                                        <option value=""> Select</option>
                                                                        <?php
                                                              $quotation   = "SELECT DISTINCT Type,CONCAT(Type,' - ',Descrpition) AS quotation_type  FROM Master_SalesIndent_QuotationType";
                                                              $Type = sqlsrv_query($conn,$quotation);
                                                              while($row_season = sqlsrv_fetch_array($Type)){
                                                            ?>
                                                                        <option value="<?=$row_season['Type']?>"> <?php echo $row_season['quotation_type']; ?> </option>
                                                                        <?php } ?>
                                                                      </select>
                                                                       <label class="error_msg text-danger"></label>
                                                          
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                            <div class="form-group change_select_width">
                                                            <div class="mb-2">
                                                                <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name" >Sale Order Type<span class="required"> *</span> </label>
                                                            </div>
                                                           <select class="js-example-basic-single col-xs-12 sale_type required_for_valid" error-msg="Sale Order Type Field is Required" name="SaleOrderType" id="SaleOrderType" >
                                                                        <option value=""> Select</option>
                                                                        <?php
                                                              $quotation   = "SELECT DISTINCT Type,CONCAT(Type,' - ',Descrpition) AS Saleorder_type  FROM Master_SalesIndent_SaleOrderType";
                                                              $sale_type = sqlsrv_query($conn,$quotation);
                                                              while($row_season = sqlsrv_fetch_array($sale_type)){
                                                            ?>
                                                                        <option value="<?=$row_season['Type']?>"> <?php echo $row_season['Saleorder_type']; ?> </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                       <label class="error_msg text-danger"></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                            <div class="form-group change_select_width">
                                                                <div class="mb-2">
                                                                   <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Zone<span class="required"> *</span> </label>
                                                               </div>
                                                               <select class="js-example-basic-single col-xs-12 zone_id required_for_valid" error-msg="Zone Field is Required"  name="ZoneId" >
                                                                <option value="">Select </option>
                                                            </select>
                                                            <label class="error_msg text-danger"></label>
                                                        </div>

                                                    </div>
                                                      </div>
                                                    </div>
                                                </div>


                                                <div class="row mt-3">

                                                   <div class="row Sales_Indent_Div">
                                                   
                                                        <div class="form-group row col-md-5">

                                                            <div class="col-md-4 col-sm-3 col-xs-6">
                                                                <div class="form-group change_select_width">
                                                                    <div class="mb-2">
                                                                        <label for="control-label col-md-3 col-sm-4 col-xs-12 " for="name">Region<span class="required"> *</span> </label>
                                                                    </div>
                                                                    <select class="js-example-basic-single col-xs-12 region_id required_for_valid" error-msg="Region Field is Required" name="RegionId" >
                                                                    <option value="">Select </option>
                                                                    </select>
                                                                    <label class="error_msg text-danger"></label>
                                                                    
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 col-sm-4 col-xs-6">
                                                                <div class="form-group change_select_width">
                                                                    <div class="mb-2">
                                                                        <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Territory<span class="required"> *</span> </label>
                                                                    </div>
                                                                    <select class="js-example-basic-single col-xs-12 territory_id required_for_valid" error-msg="Territory Field is Required" name="TerritoryId" >
                                                                    <option value="">Select </option>
                                                                    </select>
                                                                    <label class="error_msg text-danger"></label>
                                                                    
                                                                </div>
                                                            </div>


                                                            <div class="col-md-4 col-sm-4 col-xs-6">
                                                                <div class="row cus_change_select">
                                                                    <div class="mb-2">
                                                                        <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Customer<span class="required"> *</span> </label>
                                                                    </div>
                                                                    <select class="js-example-basic-single col-xs-12 customer_id required_for_valid"  error-msg="Customer Field is Required" name="CustomerCode" >
                                                                    <option value="">Select </option>
                                                                    </select>
                                                                    <label class="error_msg text-danger"></label>
                                                                </div>
                                                            </div>

                                                        </div>
                                                 
                                                           
                                                        <div class="form-group row col-md-7 Sales_Indent_Div_Crop"> 

                                                            <div class="col-md-3 col-sm-3 col-xs-6">
                                                                <div class="form-group">
                                                                  <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Customer Balance<span class="required"> *</span> </label>
                                                                   <input type="text" class="form-control Customer_Balance textbox-grey mt-2" name="Customer_Balance" error-msg="Customer Balance Field is Required" value=0 readonly="">
                                                                    <label class="error_msg text-danger"></label> 
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3 col-sm-3 col-xs-6">
                                                                <div class="form-group">
                                                                  <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Customer Credit Limit<span class="required"> *</span> </label>
                                                                   <input type="text" class="form-control Customer_Credit_Limit textbox-grey mt-2" name="Customer_Credit_Limit" error-msg=" Customer Credit Limit Field is Required" value=0 readonly="">
                                                                    <label class="error_msg text-danger"></label> 
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3 col-sm-3 col-xs-6">
                                                                <div class="form-group change_select_width">
                                                                    <div class="mb-2"> 
                                                                        <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Supply Type<span class="required"> *</span> </label>
                                                                    </div>
                                                                    <select class="js-example-basic-single col-xs-12 supply_type required_for_valid" 
                                                                    error-msg="Supply Type Field is Required" name="SupplyType" >
                                                                    <option value="2">C&F Supply </option>
                                                                   <?php if(strtoupper(@$_SESSION['Dcode']) !='TM' ){?>
                                                                    <option value="1">Direct Supply </option>
                                                                  <?php } ?>
                                                                    
                                                                    </select>
                                                                    <label class="error_msg text-danger"></label>
                                                                   
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3 col-sm-3 col-xs-6">
                                                                <div class="form-group change_select_width">
                                                                    <div class="mb-2"> 
                                                                         <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Plant<span class="required"> *</span> </label>
                                                                    </div>
                                                                    <select class="js-example-basic-single col-xs-12 plant_id required_for_valid" error-msg="Plant Field is Required" name="PlantId" >
                                                                    <option value="">Select </option>
                                                                    </select>
                                                                    <label class="error_msg text-danger"></label>
                                                                   <input type="hidden" name="Limit_Exceed" class="Limit_Exceed" value="0">
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="form-group row col-md-7 Sales_Indent_Div_Crop mt-3"> 
                                                            <!-- <div class="col-md-3 col-sm-3 col-xs-6">
                                                                <div class="form-group change_select_width">
                                                                    <div class="mb-2"> 
                                                                        <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Supply Type<span class="required"> *</span> </label>
                                                                    </div>
                                                                    <select class="js-example-basic-single col-xs-12 supply_type required_for_valid" 
                                                                    error-msg="Supply Type Field is Required" name="SupplyType" >
                                                                    <option value="2">C&F Supply </option>
                                                                   <?php if(strtoupper(@$_SESSION['Dcode']) !='TM' ){?>
                                                                    <option value="1">Direct Supply </option>
                                                                  <?php } ?>
                                                                    
                                                                    </select>
                                                                    <label class="error_msg text-danger"></label>
                                                                   
                                                                </div>
                                                            </div> -->

                                                            <!-- <div class="col-md-3 col-sm-3 col-xs-6">
                                                                <div class="form-group change_select_width">
                                                                    <div class="mb-2"> 
                                                                         <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Plant<span class="required"> *</span> </label>
                                                                    </div>
                                                                    <select class="js-example-basic-single col-xs-12 plant_id required_for_valid" error-msg="Plant Field is Required" name="PlantId" >
                                                                    <option value="">Select </option>
                                                                    </select>
                                                                    <label class="error_msg text-danger"></label>
                                                                   <input type="hidden" name="Limit_Exceed" class="Limit_Exceed" value="0">
                                                                </div>
                                                            </div> -->

                                                            <div class="col-md-3 col-sm-3 col-xs-6">
                                                                <div class="form-group">
                                                                    <div class="mb-2"> 
                                                                        <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Expected Date<span class="required"></span> </label>
                                                                    </div>
                                                                    <input type="text" name="Expected_Date" class="form-control Expected_Date flatpickr-input"  value="<?php echo date("Y-m-d") ?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3 col-sm-3 col-xs-6">
                                                                <div class="form-group">
                                                                  <button type="button" class="Get_Data btn btn-primary" style="margin-top: 37px;">Go</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                   </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card sales_indent_material_card" style="display:none;">
                                            <div class="card-body">
                                                <h5 class="card-title mb-4" style="color: rgb(31, 88, 199); display: flex;">Sale Indent Material Details</h5>
                                                <div class="row">
                                                <div class="form-group col-md-12">
                                                    <small class="duplicate_error text-danger" style="font-weight:bold"></small>
                                                    <small class="limit_exceeds_error text-danger" style="font-weight:bold"></small>
                                                    <div class="Sales_Indent_Div_Material">
                                                        <table class="table table-bordered table-hover table-striped table-responsive tbl-select p-0"     style="line-height: 10px;display: block;">
                                                            <thead>
                                                              <th class="f-14" style="max-width:10%">S.No.</th>
                                                              <th class="f-14" style="max-width:10%">Crop</th>
                                                              <!-- <th>Storage Location</th>-->
                                                              <th class="f-14" style="max-width:10%">Material</th>
                                                              <th class="text-center f-14" style="max-width:10%">Season</th>
                                                              <th class="text-center text-nowrap f-14" style="max-width:10%">Quantity Bag</th>
                                                              <th class="text-center text-nowrap f-14" style="max-width:10%">Quantity Pkts</th>
                                                              <th class="text-center text-nowrap f-14" style="max-width:10%">Quantity Kg</th>
                                                              <th class="text-center text-nowrap f-14" style="max-width:10%">Price Per Pkts</th>
                                                              <th class="text-center text-nowrap f-14" style="max-width:25%">Total Price </th>
                                                              <th class="text-center text-nowrap f-14" style="max-width:25%">Discount (%) </th>
                                                              <th class="text-center text-nowrap f-14" style="max-width:25%">Grand Total Price</th>
                                                              <th>Action</th>
                                                            </thead> 

                                                            <tbody>
                                                               <tr>
                                                                <td class='srn_no' >1</td>
                                                                <td>
                                                                     <select class="js-example-basic-single form-control Crop_Design col-md-4 crop_id required_for_valid" error-msg="Crop Field is Required" name="CropId[]" style="width: 100%;">
                                                                    <option value="">Select </option>
                                                                    </select>
                                                                    <small class="error-msg text-danger mt-1 lh-base"></small>

                                                                </td>
                                                               
                                                                 <input type="hidden"  class="form-control right only_numbers max_charater location " error-msg="Pkts Field is Required" value="SE01" name="StorageLocation[]" readonly >

                                                                <td>
                                                                    <div>
                                                                        <select class="js-example-basic-single material_id required_for_valid" error-msg="Material Field is Required" name="MaterialCode[]" id="Material0" style="width: 100%;">
                                                                        <option value="" selected>Material </option>  
                                                                        </select> 

                                                                         <input type="hidden"  class="form-control right Product_QtyInPkt" error-msg="Pkts Field is Required" name="MaterialQtyInPkt[]" value="0" readonly >
                                                                           <input type="hidden"  class="form-control right Product_QtyInKg " error-msg="Pkts Field is Required" name="MaterialQtyInKg[]" value="0" readonly >
                                                                        <small class="error-msg text-danger mt-1 lh-base"></small>
                                                                     </div>                  
                                                                </td>

                                                                <td>
                                                                    <div style="width:10%">
                                                                        <select class="js-example-basic-single season " error-msg="Season Field is Required" name="season[]" >
                                                                        <option value="" selected>Select Season </option>
                                                                        </select> 
                                                                        <small class="error-msg text-danger mt-1 lh-base"></small>
                                                                    </div>                  
                                                                </td>

                                                                <td> 
                                                                    <div>
                                                                      <input type="text"  class="form-control right only_numbers max_charater QtyInBag required_for_valid" error-msg="Quantity_Bag Field is Required" name="QtyInBag[]"  readonly>
                                                                        <small class="error-msg text-danger mt-1 lh-base"></small>
                                                                    </div>
                                                                </td>
                                                            
                                                                <td> 
                                                                    <div>
                                                                      <input type="text"  class="form-control right only_numbers max_charater QtyInPkt textbox-grey" error-msg="Pkts Field is Required" name="QtyInPkt[]" readonly >
                                                                        <small class="error-msg text-danger mt-1 lh-base"></small>
                                                                    </div>
                                                                </td>

                                                                <td> 
                                                                    <div>
                                                                        <input type="text"  class="form-control right only_numbers max_charater QtyInKg textbox-grey" error-msg="Kg Field is Required" name="QtyInKg[]" readonly >
                                                                        <small class="error-msg text-danger mt-1 lh-base"></small>
                                                                    </div>
                                                                </td>

                                                                <td> 
                                                                  <div>
                                                                  <input type="text"  class="form-control right only_numbers max_charater textbox-grey Price" error-msg="Price Per Pkts Field is Required" name="Price[]" id="Price0" value="" readonly >
                                                                    <small class="error-msg text-danger mt-1 lh-base"></small>
                                                                    </div>
                                                                </td>

                                                                <td> 
                                                                  <div>
                                                                  <input type="text"  class="form-control right only_numbers max_charater Total_Price textbox-grey" error-msg="Total price  Field is Required" name='Total_Price[]' value="" style='width:110%' readonly >
                                                                 <small class="error-msg text-danger mt-1 lh-base"></small> 
                                                                    </div>
                                                                </td>

                                                                <td> 
                                                                  <div>
                                                                  <input type="text"  class="form-control right only_numbers max_charater Discount textbox-grey" error-msg="Discount  Field is Required" name='Discount[]' style='width:110%' readonly >
                                                                  <small class="error-msg text-danger mt-1 lh-base"></small>
                                                                    </div>
                                                                </td>

                                                                <td> 
                                                                  <div>
                                                                  <input type="text"  class="form-control right only_numbers max_charater Grand_Total_Price textbox-grey" error-msg="Grand Total price  Field is Required" name='Grand_Total_Price[]' style='width:110%' readonly >


                                                                  <input type="hidden"  class="form-control  crop_name_test" name="CropId_Name[]"  readonly >

                                                                  <small class="error-msg text-danger mt-1 lh-base"></small>

                                                                    </div>
                                                                </td>
                                                     
                                                                <td>
                                                                 <div class="d-flex mt-2">
                                                                  <button type="button" onclick ='add_row()' class="btn btn-sm btn-success"><i class="fas fa-plus-circle"></i></button>
                                                                  <button type="button" class='btn delete btn-sm btn-danger ms-1' onclick ='delete_user($(this))'><i class="fas fa-trash" aria-hidden="true"></i></button>
                                                                 </div>
                                                                </td> 
                                                               </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                </div>


                                                <div class="col-md-12 Sales_Indent_Div_Material">
                                                    <div class="row mb-3" style="width:100%;">
                                                        <label class="control-label col-md-2" for="Address">Delivery Address </label>
                                                        <div class="col-md-12">
                                                            <textarea class="form-control " maxlength="60" error-msg="Delivery Address  Field is Required" name="Address" rows="2"></textarea>
                                                            <small class="text-danger"> ( Note: Only Allow 60 Characters Only ) </small>
                                                            <label class="error_msg text-danger"></label>

                                                            <input type="hidden" class="form-control rasiCount_material mt-4" value="1" min="1" max='25'>
                                                        </div> 

                                                        <div class="col-md-12 Sales_Indent_Div d-flex justify-content-center mt-5">
                                                            <a href="Dashboard_validation.php" class="btn btn-danger"><i class="bx bx-x me-1"></i> Cancel</a>
                                                            <button type="submit" name="submit" class="btn btn-success cls_btn_sub ms-2"><i class="bx bx-check me-1"></i> Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
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
                    $('.page-title').text('Indent Creation');
                    $('.page-title').before("<i class='bx bx-store icon font-size-20 me-2 text-primary'></i>");
                    $('.flatpickr-input').flatpickr();
                });
            </script>

            </body>

            </html>