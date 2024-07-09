            <?php include('partials/head.php'); ?>
            <!-- <body data-layout="horizontal"> -->
            <?php 
            if(@$requeter_menu == 0) {
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
                    if(isset($_REQUEST['submit']))
                     {
                         // echo "<pre>";print_r($_POST);exit;
                      $user_Input=$_POST;
                      $ModifiedAt=date('Y-m-d H:i:s');
                      $ModifiedBy=$_SESSION['EmpID'];
                      $Remarks=@$user_Input['Remarks'];
                      $CustomerCode=@$user_Input['CustomerCode'];
                      $SupplyType=@$user_Input['SupplyType'];
                      $PlantId=@$user_Input['PlantId'];
                      $Address=@$user_Input['Address'];

                      $sql="update Sales_Indent set Address='$Address'";
                        if(sqlsrv_query($conn,$sql)){
                           $count=0;
                        } 

                      foreach(@$user_Input['QtyInBag'] as $key=>$value)
                      {
                        $MaterialCode=@$user_Input['MaterialCode'][$key];
                        $QtyInBag=@$user_Input['QtyInBag'][$key];
                        $QtyInPkt=@$user_Input['QtyInPkt'][$key];
                        $QtyInKg=@$user_Input['QtyInKg'][$key];
                        $price=@$user_Input['Price'][$key];
                        $Total_Price=@$user_Input['Total_Price'][$key];
                        $SalesIndentId=@$user_Input['SalesIndentId'][$key];

                        $sql="UPDATE Sales_Indent_Material_Details SET QtyInBag='$QtyInBag',QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',MaterialCode='$MaterialCode',ModifiedAt='".$ModifiedAt."',ModifiedBy='".$ModifiedBy."',Price='".$price."',Total_Price='".$Total_Price."' where id='".$SalesIndentId."'";
                        if(sqlsrv_query($conn,$sql)){
                          $count++;
                        } 
                      }
                      echo "<script>
                      Swal.fire(
                      {
                      title:'Success!',
                      text:'Indent Modifications updated succssfully.',
                      icon:'success',
                      // showCancelButton:!0,
                      confirmButtonColor:'#038edc',
                      // cancelButtonColor:'#f34e4e'
                         }
                      ).then(function(e){
                        if(e) {
                            window.location.href ='Indent_update.php'
                        }
                      });
                      </script>";
                      
                      }
                      
                      //$Insert=new Stock_Indent();
                      //$Insert->Insert(array("data"=>1));
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

                      $id=isset($_REQUEST['Id']) && !empty($_REQUEST['Id']) ? safe_decode($_REQUEST['Id']) : 0;
                      echo $sql="EXEC Sales_Indent_Header_Details @SalesIndentId='$id'";
                      $stmt = sqlsrv_prepare($conn, $sql);
                      sqlsrv_execute($stmt);
                      $Header_data = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
                      
                      echo $Grid_sql="EXEC Sales_Indent_Get_Material_DetS @Sales_Indent_Id='".@$Header_data['SalesIndentId']."'";
                      $Grid_stmt = sqlsrv_prepare($conn, $Grid_sql);
                      sqlsrv_execute($Grid_stmt);
                ?>

                <!-- ============================================================== -->
                <!-- Start right Content here -->
                <!-- ============================================================== -->
                <div class="main-content">
                    <div class="page-content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="form-horizontal form-label-left Sales_Indent_Submit" name="form_name"  role="form" method="POST" enctype="multipart/form-data" >  
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
                                                              <div class="mb-2">
                                                                <label for="name" >Request ID</label>
                                                              </div>
                                                              <input type="hidden" class="Sales_Indent_Id" name="Sales_Indent_Id" value="<?=@$Header_data['SalesIndentId']?>">
                                                              <input type="text" class="form-control col-md-12 col-xs-12 required_for_valid textbox-grey" error-msg="Request ID field is required" name="ReqId" value="<?=@$Header_data['ReqId']?>" readonly>
                                                              <label class="error_msg text-danger"></label>
                                                        </div>
                                                        
                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                            <div class="form-group">
                                                              <label for="control-label col-md-2 col-sm-2 col-xs-12" for="name">Request Date</label>
                                                               
                                                                  <input type="text" class="form-control col-md-12 col-xs-12 required_for_valid textbox-grey mt-2" name="ReqDate" value="<?=@$Header_data['ReqDate']?>" readonly>
                                                                   <label class="error_msg text-danger"></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                            <div class="form-group change_select_width">
                                                                <div class="mb-2">
                                                                    <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name" >Division<span class="required"> *</span> </label>
                                                                </div>
                                                               <input type="hidden" class="product_division" name="ProductDivision"  value="<?=@$Header_data['ProductDivision']?>">
                                                               <select class="js-example-basic-single col-xs-12" error-msg="Division Field is Required" disabled >
                                                                  <?php foreach($product_division_arr  as $value){?>
                                                                  <option value="<?=$value['code']?>"<?=@$Header_data['ProductDivision'] == $value['code'] ? 'Selected' : ''?>><?=$value['name']?></option>
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
                                                            <select class="js-example-basic-single col-xs-12 quot_type required_for_valid" error-msg="Quotation Type Field is Required" name="QuotationType" id="QuotationType" disabled>
                                                                <option value=""> Select</option>
                                                                <?php
                                                                   $quotation   = "SELECT DISTINCT Type,CONCAT(Type,' - ',Descrpition) AS quotation_type  FROM Master_SalesIndent_QuotationType";
                                                                   $Type = sqlsrv_query($conn,$quotation);
                                                                   while($row_season = sqlsrv_fetch_array($Type)){
                                                                   ?>
                                                                <option value="<?=$row_season['Type']?>" <?=@$Header_data['QuotationType'] == $row_season['Type'] ? 'Selected' : ''?>> <?php echo $row_season['quotation_type']; ?> </option>
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
                                                             <select class="js-example-basic-single col-xs-12 sale_type required_for_valid" error-msg="Sale Order Type Field is Required" name="SaleOrderType" id="SaleOrderType" disabled>
                                                                <option value=""> Select</option>
                                                                <?php
                                                                   $quotation   = "SELECT DISTINCT Type,CONCAT(Type,' - ',Descrpition) AS Saleorder_type  FROM Master_SalesIndent_SaleOrderType";
                                                                   $sale_type = sqlsrv_query($conn,$quotation);
                                                                   while($row_season = sqlsrv_fetch_array($sale_type)){
                                                                   ?>
                                                                <option value="<?=$row_season['Type']?>"<?=@$Header_data['SaleOrderType'] == $row_season['Type'] ? 'Selected' : ''?>>  <?php echo $row_season['Saleorder_type']; ?> </option>
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
                                                                <input type="text" class="form-control textbox-grey" value="<?=@$Header_data['ZONENAME']?>" readonly>
                                                                <input type="hidden" class="zone_id" name="ZoneId"  value="<?=@$Header_data['ZoneId']?>">
                                                                <input type="hidden" class="region_id" name="RegionId"  value="<?=@$Header_data['RegionId']?>">
                                                                <input type="hidden" class="territory_id" name="TerritoryId"  value="<?=@$Header_data['TerritoryId']?>">
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
                                                                    <input type="text" class="form-control textbox-grey" value="<?=@$Header_data['REGIONNAME']?>" readonly>
                                                                    <label class="error_msg text-danger"></label>
                                                                    
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 col-sm-4 col-xs-6">
                                                                <div class="form-group change_select_width">
                                                                    <div class="mb-2">
                                                                        <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Territory<span class="required"> *</span> </label>
                                                                    </div>
                                                                   <input type="text" class="form-control textbox-grey" value="<?=@$Header_data['TMNAME']?>" readonly>
                                                                    <label class="error_msg text-danger"></label>
                                                                    
                                                                </div>
                                                            </div>


                                                            <div class="col-md-4 col-sm-4 col-xs-6">
                                                                <div class="row cus_change_select">
                                                                    <div class="mb-2">
                                                                        <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Customer<span class="required"> *</span> </label>
                                                                    </div>
                                                                   <input type="hidden" class="customer_id" name="CustomerCode" value="<?=@$Header_data['CustomerCode']?>">
                                                                   <select class="js-example-basic-single col-xs-12 customer_id required_for_valid"  error-msg="Customer Field is Required" name="CustomerCode" disabled>
                                                                   </select>
                                                                    <label class="error_msg text-danger"></label>
                                                                </div>
                                                            </div>

                                                        </div>
                                                 
                                                           
                                                        <div class="form-group row col-md-7 Sales_Indent_Div_Crop"> 

                                                            <div class="col-md-3 col-sm-3 col-xs-6">
                                                                <div class="form-group change_select_width">
                                                                    <div class="mb-2"> 
                                                                        <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Supply Type<span class="required"> *</span> </label>
                                                                    </div>
                                                                   <select class="js-example-basic-single col-xs-12 supply_type required_for_valid" 
                                                                      error-msg="Supply Type Field is Required" name="SupplyType"  disabled>
                                                                      <option value="1" <?=@$Header_data['SupplyType']==1 ? 'Selected' : ''?>>Direct Supply </option>
                                                                      <option value="2" <?=@$Header_data['SupplyType']==2 ? 'Selected' : ''?>>C&F Supply </option>
                                                                   </select>
                                                                    <label class="error_msg text-danger"></label>
                                                                   
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3 col-sm-3 col-xs-6">
                                                                <div class="form-group change_select_width">
                                                                    <div class="mb-2"> 
                                                                         <label for="control-label col-md-3 col-sm-4 col-xs-12" for="name">Plant<span class="required"> *</span> </label>
                                                                    </div>
                                                                    <input type="text" class="form-control textbox-grey" value="<?=@$Header_data['Plant_Code']." / ".@$Header_data['Plant_Name']?>" readonly>
                                                                    <label class="error_msg text-danger"></label>
                                                                   <input type="hidden" name="Limit_Exceed" class="Limit_Exceed" value="0">
                                                                </div>
                                                            </div>

                                                        </div>

                                                   </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card sales_indent_material_card">
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
                                                            <?php 
                                                                $i=1;
                                                                while($Grid_data = sqlsrv_fetch_array($Grid_stmt,SQLSRV_FETCH_ASSOC)){;
                                                            ?>
                                                               <tr>
                                                                <td class='srn_no' ><?php echo $i++; ?></td>
                                                                <td>
                                                                     <input type="hidden" class="crops" value="<?php echo $Header_data['CropId'];?>">
                                                                     <select class="js-example-basic-single form-control Crop_Design col-md-4" error-msg="Crop Field is Required" name="CropId[]" style="width: 100%;" disabled>
                                                                    <option value="">Select Crop</option>
                                                                    <?php 
                                                                    $pdivision = ($Header_data['ProductDivision'] == 'ras' || $Header_data['ProductDivision'] == 'CT01') ? 'COT' : (($Header_data['ProductDivision'] == 'fcm' || $Header_data['ProductDivision'] == 'FC01') ? 'FC' : 'FO');
                                                                    $crop_sql = "SELECT Crop_Master.CropCode,Crop_Master.CropName FROM Master_Stock_Prod_2_Crop AS Crop_Master WHERE ProductDivionCode= '".$pdivision."' and Record_Status=1";
                                                                    $crop_query = sqlsrv_query($conn,$crop_sql); 
                                                                    while($crop_data = sqlsrv_fetch_array($crop_query,SQLSRV_FETCH_ASSOC)){;
                                                                    ?>
                                                                    <option <?php if($Header_data['CropId'] == $crop_data['CropCode']) { echo 'selected'; } ?>  value="<?php echo $crop_data['CropCode']; ?>"><?php echo $crop_data['CropName']; ?></option>
                                                                    <?php } ?>
                                                                    </select>
                                                                    <small class="error-msg text-danger mt-1 lh-base"></small>

                                                                </td>
                                                               
                                                                 <input type="hidden"  class="form-control right only_numbers max_charater location " error-msg="Pkts Field is Required" value="SE01" name="StorageLocation[]" readonly >

                                                                <td>
                                                                    <div>
                                                                        <input type="hidden" class='Material_Code' value='<?=@$Grid_data['MaterialCode']?>'>
                                                                        <select class="js-example-basic-single material_id required_for_valid" error-msg="Material Field is Required" name="MaterialCode[]"  style="width: 100%;">
                                                                        <option value="" selected>Select Material</option>  
                                                                        </select> 

                                                                         <input type="hidden"  class="form-control right Product_QtyInPkt" error-msg="Pkts Field is Required" name="MaterialQtyInPkt[]" value="0" readonly >
                                                                           <input type="hidden"  class="form-control right Product_QtyInKg " error-msg="Pkts Field is Required" name="MaterialQtyInKg[]" value="0" readonly >
                                                                        <small class="error-msg text-danger mt-1 lh-base"></small>
                                                                     </div>                  
                                                                </td>

                                                                <td>
                                                                    <div style="width:10%">
                                                                        <input type="hidden" class='Season' value='<?=@$Grid_data['season']?>'>
                                                                        <select class="js-example-basic-single season " error-msg="Season Field is Required" name="season[]" value="<?=round(@$Grid_data['season'],0)?>">
                                                                        <option value="" selected>Select Season </option>
                                                                        </select> 
                                                                        <small class="error-msg text-danger mt-1 lh-base"></small>
                                                                    </div>                  
                                                                </td>

                                                                <td> 
                                                                    <div>
                                                                      <input type="text"  class="form-control right only_numbers max_charater QtyInBag required_for_valid" error-msg="Quantity_Bag Field is Required" name="QtyInBag[]"  value="<?=round(@$Grid_data['QtyInBag'],0)?>">
                                                                        <input type="hidden"  class="form-control right only_numbers max_charater Product_QtyInPkt " error-msg="Kg Field is Required" name="MaterialQtyInPkt[]" value="<?=round(@$Grid_data['MaterialQtyInPkt'],2)?>" readonly >
                                                                        <input type="hidden"  class="form-control right only_numbers max_charater Product_QtyInKg " error-msg="Kg Field is Required" name="MaterialQtyInKg[]" value="<?=round(@$Grid_data['MaterialQtyInKg'],2)?>" readonly >
                                                                        <input type="hidden"  class="form-control right only_numbers max_charater Product_Price" error-msg="Kg Field is Required" name="MaterialPrice[]" value="<?=round(@$Grid_data['Price'],2)?>" readonly >
                                                                        <small class="error-msg text-danger mt-1 lh-base"></small>
                                                                    </div>
                                                                </td>
                                                            
                                                                <td> 
                                                                    <div>
                                                                      <input type="text"  class="form-control right only_numbers max_charater QtyInPkt textbox-grey" error-msg="Pkts Field is Required" name="QtyInPkt[]" value="<?=round(@$Grid_data['QtyInPkt'],0)?>" readonly >
                                                                        <small class="error-msg text-danger mt-1 lh-base"></small>
                                                                    </div>
                                                                </td>

                                                                <td> 
                                                                    <div>
                                                                        <input type="text"  class="form-control right only_numbers max_charater QtyInKg textbox-grey" error-msg="Kg Field is Required" name="QtyInKg[]" value="<?=round(@$Grid_data['QtyInKg'],0)?>" readonly >
                                                                        <small class="error-msg text-danger mt-1 lh-base"></small>
                                                                    </div>
                                                                </td>

                                                                <td> 
                                                                  <div>
                                                                  <input type="text"  class="form-control right only_numbers max_charater textbox-grey Price" error-msg="Price Per Pkts Field is Required" name="Price[]" value="<?=round(@$Grid_data['Price'],0)?>" readonly >
                                                                    <small class="error-msg text-danger mt-1 lh-base"></small>
                                                                    </div>
                                                                </td>

                                                                <td> 
                                                                  <div>
                                                                  <input type="text"  class="form-control right only_numbers max_charater textbox-grey Total_Price" error-msg="Total price  Field is Required" name='Total_Price[]' value="<?=round(@$Grid_data['Total_Price'],0)?>" style='width:110%' readonly >
                                                                 <small class="error-msg text-danger mt-1 lh-base"></small> 
                                                                    </div>
                                                                </td>

                                                                <td> 
                                                                  <div>
                                                                  <input type="text"  class="form-control right only_numbers max_charater Discount textbox-grey" error-msg="Discount  Field is Required" name='Discount[]' style='width:110%' readonly value="<?=round(@$Grid_data['Discount'],0)?>">
                                                                  <small class="error-msg text-danger mt-1 lh-base"></small>
                                                                    </div>
                                                                </td>

                                                                <td> 
                                                                  <div>
                                                                  <input type="text"  class="form-control right only_numbers max_charater Grand_Total_Price textbox-grey" error-msg="Grand Total price  Field is Required" name='Grand_Total_Price[]' style='width:110%' readonly value="<?=round(@$Grid_data['Grand_Total_Price'],0)?>">


                                                                  <input type="hidden"  class="form-control  crop_name_test" name="CropId_Name[]"  readonly >

                                                                  <small class="error-msg text-danger mt-1 lh-base"></small>

                                                                    </div>
                                                                </td>
                                                     
                                                                <td>
                                                                 <div class="d-flex mt-2">
                                                                  <!-- <button type="button" onclick ='add_row()' class="btn btn-sm btn-success"><i class="fas fa-plus-circle"></i></button> -->
                                                                 <input type="hidden" name="SalesIndentId[]"  class="SalesIndentId" value="<?=@$Grid_data['Id']?>">
                                                                  <button type="button" class='btn delete btn-sm btn-danger ms-1 remove_btn'><i class="fas fa-trash" aria-hidden="true"></i></button>
                                                                 </div>
                                                                </td> 
                                                               </tr>
                                                                <?php } ?> 
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                </div>


                                                <div class="col-md-12 Sales_Indent_Div_Material">
                                                    <div class="row mb-3" style="width:100%;">
                                                        <label class="control-label col-md-2" for="Address">Delivery Address </label>
                                                        <div class="col-md-12">
                                                            <textarea class="form-control " maxlength="60" error-msg="Delivery Address  Field is Required" name="Address" rows="2"><?=@$Header_data['Address']?></textarea>
                                                            <small class="text-danger"> ( Note: Only Allow 60 Characters Only ) </small>
                                                            <label class="error_msg text-danger"></label>

                                                            <input type="hidden" class="form-control rasiCount_material mt-4" value="1" min="1" max='25'>
                                                        </div> 

                                                        <div class="col-md-12 Sales_Indent_Div d-flex justify-content-center mt-5">
                                                            <a href="Dashboard_validation.php" class="btn btn-danger"><i class="bx bx-x me-1"></i> Cancel</a>
                                                            <button type="submit" name="submit" class="btn btn-primary cls_btn_sub ms-1"><i class="bx bx-check me-1"></i> Update</button>

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

            <script src="js/Edit_Sales_indent_Details.js"></script>
            <script src="../common/checkSession.js"></script>

            <script type="text/javascript">

                $(document).ready(function(){
                    $('.page-title').text('Indent Modification');
                    $('.flatpickr-input').flatpickr();

                   $('.js-example-basic-single').select2();
                   var product_division = $(".product_division").val();
                   var supply_type = $(".supply_type").val();  
                   var crop_id=$(".cropid").val();
                   role_based_filter(product_division,"Get_Zone_Details",0,0,0,crop_id,0);
                   var PlantId="<?php echo $Header_data['PlantId'];?>";
                   get_plant_details(supply_type,PlantId,0);
                   var zone_id = $(".zone_id").val();
                   var region_id = $(".region_id").val();
                   var territory_id = $(".territory_id").val();
                   var crop_code = $(".crop_id").val();
                   var customer_id='<?php echo @$Header_data["CustomerCode"]?>';
                   get_customer_details(product_division,zone_id,region_id,territory_id,customer_id);

                   $(".material_id").each(function(key,value){
                      //material selectbox functionality
                      var Material_Code=$(this).closest("tr").find(".Material_Code").val();
                      var region_id=$(".region_id").val(); 
                      var result="";
                      result= get_material_details(product_division,crop_code,zone_id,1,Material_Code,region_id);
                      $(this).html(result);
                      // $(this).select2();


                      //season select box functionality 
                      var user_input={};
                      user_input.material_id=Material_Code;
                      user_input.region_id=region_id;
                      var season=$(this).closest("tr").find(".Season").val();
                      var result=Get_Season_Code_Details(user_input,season);
                      $(this).closest('tr').find(".season").html(result);

                      //crop select box functionality
                      var cropcode = $(this).closest('td').find('.crops').val();
                      $(this).closest('tr').find('.Crop_Design').val(cropcode);


                      var QtyInPkt = $(this).closest('tr').find(".QtyInPkt").val(); 
                      var price=$(this).closest('tr').find(".Product_Price").val();
                      var totalPrice=parseFloat(price)*parseFloat(QtyInPkt);

                      var Discount=$(this).closest("tr").find(".Discount").val(); /*Discount Amount (In Fcm Default 5% and Cotton 0% ) */
                      Discount=parseInt(Discount);
                      discount_amount = 0;
                      if(Discount !='' && Discount >0)
                      {
                          if(isNaN(totalPrice))
                          {
                            totalPrice=0;
                          }
                        discount_amount=totalPrice*(Discount/100);
                      }
                    var Grand_Total_Price=parseFloat(totalPrice)-parseFloat(discount_amount);
                    $(this).closest('tr').find('.Grand_Total_Price').val(Grand_Total_Price);
                  });


               });


            </script>

            </body>

            </html>