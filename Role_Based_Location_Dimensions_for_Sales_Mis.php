<?php
include '../auto_load.php';
$current_user_id=$_SESSION['EmpID'];
$session_role_id=$_SESSION['Dcode'];
$location_array=array();
$zone_dets_array=array();
$region_dets_array=array();
$territory_dets_array=array();
$action_type = isset($_REQUEST['action_type'])&&!empty($_REQUEST['action_type']) ? $_REQUEST['action_type'] : "";

function check_input($input,$default_value="")
{
  $default_value=$default_value !="" ? $default_value : 0;
  if(isset($input) && is_array($input))
  {
    return implode(",", $input);

  }else if(!empty($input))
  {
    return $input;

  }else
  {
    return $default_value;
  }
}
if($action_type !="")
{
  //print_r($_POST);exit;
$zone_dets_array=array();
$region_dets_array=array();
$territory_dets_array=array();
$product_division=isset($_REQUEST['product_division'])&&!empty($_REQUEST['product_division']) ? $_REQUEST['product_division'] : " ";

//$product_division=isset($_REQUEST['product_division'])&&!empty($_REQUEST['product_division']) ? $_REQUEST['product_division'] : "ras";



$ProductDivision='';
if($product_division =='ras')
{
  $ProductDivision="CT01";
}else if($product_division =='fcm')
{
  $ProductDivision="FC01";
}else if($product_division =='frg')
{
  $ProductDivision="FR01";
}
$zone_code=check_input(@$_REQUEST['zone_code']);
$region_code=check_input(@$_REQUEST['region_code']);
$territory_code=check_input(@$_REQUEST['territory_code']);
$crop_code=check_input(@$_REQUEST['crop_code']);
$variety_code=check_input(@$_REQUEST['variety_code']);
$get_zone_status=$get_region_status=$get_territory_status=$get_crop_status=$get_quotation_status=$get_saleorder_status=$get_crop_status_sub=0;
if($action_type == "Get_Zone_Details"){
  $get_zone_status="1";
  $get_region_status="1";
  $get_territory_status="1";
  $get_crop_status="1";
  $get_quotation_status="1";
  $get_saleorder_status="1";
}else if($action_type == "Get_Region_Details"){
  $get_zone_status="0";
  $get_region_status="1";
  $get_territory_status="1";
  $get_crop_status="0";
}else if($action_type == "Get_Territory_Details"){
  $get_zone_status="0";
  $get_region_status="0";
  $get_territory_status="1";
  $get_crop_status="0";
}else if($action_type == "Get_Crop_Details"){
  $get_crop_status="1";
 
}else if($action_type == "Get_Crop_Details_sub"){
 // $get_crop_status="1";
  $get_crop_status_sub="1";
}




$zone_all=$region_all=$terrtery_all="";
if($_SESSION['Dcode'] == "ADMIN" || $_SESSION['Dcode'] == "SUPERADMIN" ){
  $zone_all="All";
  $region_all="All";
  $terrtery_all="All";
}else if($_SESSION['Dcode'] == "ZM" || $_SESSION['Dcode'] == "DBM" || $_SESSION['Dcode'] == "GM"){
  $zone_all="";
  $region_all="All";
  $terrtery_all="All";
}else if($_SESSION['Dcode'] == "RBM"){
  $zone_all="";
  $region_all="";
  $terrtery_all="All";
}else if($_SESSION['Dcode'] == "TM"){
  $zone_all="";
  $region_all="";
  $terrtery_all="";
}
$response=array();
$response['zone_details']="";
$response['region_details']="";
$response['territory_details']="";
$response['crop_details']="";
$response['quotation_details']="";
$response['saleorder_details']="";



if($get_crop_status == "1")
{
 $sql="EXEC Master_Crop_Details @Product_Division='".@$product_division."'";
 $response['crop_sql']=$sql;
$sql_for_crop_details=sqlsrv_prepare($conn,$sql);
sqlsrv_execute($sql_for_crop_details);
$crop_dets_array=[];
while($result = sqlsrv_fetch_array($sql_for_crop_details,SQLSRV_FETCH_ASSOC))
 {
  if($result['CropCode'] !="" && $result['CropName'] !="")
  {
    $crop_dets_array[]=array('code'=>$result['CropCode'],'name'=>$result['CropName']);
  }
 }
  $crop_dets_array = array_unique($crop_dets_array, SORT_REGULAR);
  $option_for_crop_dets="";
  if(count($crop_dets_array) >0)
  {
    $option_for_crop_dets.="<option value=''>Select Crop </option>";
  }

  foreach ($crop_dets_array as $zone_key => $zone_value)
  {
    if($product_division =='ras'){


$selected=trim($zone_value['code']) ? "selected" : "";


    }else{

 $selected=$crop_code==trim($zone_value['code']) ? "selected" : "";
      
    }



 //   $selected=$crop_code==trim($zone_value['code']) ? "selected" : "";
     $option_for_crop_dets.="<option value='".trim($zone_value['code'])."' ".$selected.">".trim($zone_value['name'])."</option>"; 
  }
   $response['crop_details']=$option_for_crop_dets;
   $response['crop_sql']=$sql;
}



if($get_crop_status_sub == "1")
{
 $sql="EXEC Master_Crop_Details_Sub @Product_Division='".@$product_division."',@Crop_Code='".@$crop_code."'";
 $response['crop_sql']=$sql;
$sql_for_crop_details=sqlsrv_prepare($conn,$sql);
sqlsrv_execute($sql_for_crop_details);
$crop_dets_array=[];
while($result = sqlsrv_fetch_array($sql_for_crop_details,SQLSRV_FETCH_ASSOC))
 {
  if($result['CropCode'] !="" && $result['CropName'] !="")
  {
    $crop_dets_array[]=array('code'=>$result['CropCode'],'name'=>$result['CropName']);
  }
 }
  $crop_dets_array = array_unique($crop_dets_array, SORT_REGULAR);
  $option_for_crop_dets="";
  if(count($crop_dets_array) >0)
  {
    $option_for_crop_dets.="<option value=''>Select Crop </option>";
  }

  foreach ($crop_dets_array as $zone_key => $zone_value)
  {
    $selected=$crop_code==trim($zone_value['code']) ? "selected" : "";
     $option_for_crop_dets.="<option value='".trim($zone_value['code'])."' ".$selected.">".trim($zone_value['name'])."</option>"; 
  }
   $response['crop_details']=$option_for_crop_dets;
   $response['crop_sql']=$sql;
}




if($get_quotation_status == "1")
{
 $sql="EXEC Sales_Indent_Quotation_Details @Product_Division='".@$product_division."'";
 $response['quotation_sql']=$sql;
$sql_for_quotation_details=sqlsrv_prepare($conn,$sql);
sqlsrv_execute($sql_for_quotation_details);
$quotation_dets_array=[];
while($result = sqlsrv_fetch_array($sql_for_quotation_details,SQLSRV_FETCH_ASSOC))
 {
  if($result['Type'] !="" && $result['Descrpition'] !="")
  {
    $quotation_dets_array[]=array('code'=>$result['Type'],'name'=>$result['Descrpition']);
  }
 }
  $quotation_dets_array = array_unique($quotation_dets_array, SORT_REGULAR);
  $option_for_quotation_dets="";
  if(count($quotation_dets_array) >1)
  {
    $option_for_quotation_dets.="<option value=''>Select quotation </option>";
  }

  foreach ($quotation_dets_array as $key => $value)
  {
    $selected=@$quotation_code==trim($value['code']) ? "selected" : "";
     $option_for_quotation_dets.="<option value='".trim($value['code'])."' ".$selected.">".trim($value['name'])."</option>"; 
  }
   $response['quotation_details']=$option_for_quotation_dets;
   $response['quotation_sql']=$sql;
}

if($get_saleorder_status == "1")
{
 $sql="EXEC Sales_Indent_SaleOrder_Details @Product_Division='".@$product_division."'";
 $response['saleorder_sql']=$sql;
$sql_for_saleorder_details=sqlsrv_prepare($conn,$sql);
sqlsrv_execute($sql_for_saleorder_details);
$saleorder_dets_array=[];
while($result = sqlsrv_fetch_array($sql_for_saleorder_details,SQLSRV_FETCH_ASSOC))
 {
  if($result['Type'] !="" && $result['Descrpition'] !="")
  {
    $saleorder_dets_array[]=array('code'=>$result['Type'],'name'=>$result['Descrpition']);
  }
 }
  $saleorder_dets_array = array_unique($saleorder_dets_array, SORT_REGULAR);
  $option_for_saleorder_dets="";
  if(count($saleorder_dets_array) >1)
  {
    $option_for_saleorder_dets.="<option value=''>Select saleorder </option>";
  }

  foreach ($saleorder_dets_array as $key => $value)
  {
    $selected=@$saleorder_code==trim($value['code']) ? "selected" : "";
     $option_for_saleorder_dets.="<option value='".trim($value['code'])."' ".$selected.">".trim($value['name'])."</option>"; 
  }
   $response['saleorder_details']=$option_for_saleorder_dets;
  $response['saleorder_sql']=$sql;
}

if($get_zone_status == "1")
{
 $sql="EXEC Role_Based_Location_Dimension @Plant_Divison_Code='$product_division',@Role_ID='$session_role_id',@User_ID='$current_user_id',@ZoneCode='0',@RegionCode='0',@TerritoryCode='0'";
 $response['zone_sql']=$sql;
$sql_for_location_details=sqlsrv_prepare($conn,$sql);
sqlsrv_execute($sql_for_location_details);
$zone_dets_array=[];
while($result = sqlsrv_fetch_array($sql_for_location_details,SQLSRV_FETCH_ASSOC))
 {
  if($result['SAPZONEID'] !="" && $result['ZONENAME'] !="")
  {
    $zone_dets_array[]=array('code'=>$result['SAPZONEID'],'name'=>$result['ZONENAME'],'data_area_id'=>strtolower($result['DATAAREAID']));
  }
 }
  $zone_dets_array = array_unique($zone_dets_array, SORT_REGULAR);
  $option_for_zone_dets="";
  if(count($zone_dets_array) >1)
  {
    $option_for_zone_dets.="<option value=''>Select Zone </option>";
  }

  foreach ($zone_dets_array as $zone_key => $zone_value)
  {
    $selected="";
     $option_for_zone_dets.="<option value=".trim($zone_value['code'])." ".$selected.">".trim($zone_value['name'])."</option>"; 
  }
   $response['zone_details']=$option_for_zone_dets;
}

if($get_region_status == "1")
{
  $sql="EXEC Role_Based_Location_Dimension @Plant_Divison_Code='$product_division',@Role_ID='$session_role_id',@User_ID='$current_user_id',@ZoneCode='$zone_code',@RegionCode='0',@TerritoryCode='0'";
  $response['region_sql']=$sql;
$sql_for_location_details=sqlsrv_prepare($conn,$sql);
sqlsrv_execute($sql_for_location_details);
$zone_dets_array=[];
while($result = sqlsrv_fetch_array($sql_for_location_details,SQLSRV_FETCH_ASSOC))
 {
  
  if($result['SAPREGIONID'] !="" && $result['REGIONNAME'] !="")
  {
     $region_dets_array[]=array('code'=>$result['SAPREGIONID'],'name'=>$result['REGIONNAME'],'data_area_id'=>strtolower($result['DATAAREAID']));
  }
 }
  $region_dets_array = array_unique($region_dets_array, SORT_REGULAR);
  $option_for_region_dets="";
  if(count($region_dets_array) >1)
  {
    $option_for_region_dets.="<option value=''>Select Region </option>";
  }

  foreach ($region_dets_array as $region_key => $region_value)
  {
    $selected="";
     $option_for_region_dets.="<option value=".trim($region_value['code'])." ".$selected.">".trim($region_value['name'])."</option>"; 
  }
  $response['region_details']=$option_for_region_dets;
}

if($get_territory_status == "1")
{
$sql="EXEC Role_Based_Location_Dimension @Plant_Divison_Code='$product_division',@Role_ID='$session_role_id',@User_ID='$current_user_id',@ZoneCode='$zone_code',@RegionCode='$region_code',@TerritoryCode='0'";
$response['territory_sql']=$sql;
$sql_for_location_details=sqlsrv_prepare($conn,$sql);
sqlsrv_execute($sql_for_location_details);
$territory_dets_array=[];
while($result = sqlsrv_fetch_array($sql_for_location_details,SQLSRV_FETCH_ASSOC))
 {
  if($result['SAPTMID'] !="" && $result['TMNAME'] !="")
  {
      $territory_dets_array[]=array('code'=>$result['SAPTMID'],'name'=>$result['TMNAME'],'data_area_id'=>strtolower($result['DATAAREAID']));
  } 
 }
  $territory_dets_array = array_unique($territory_dets_array, SORT_REGULAR);
  $option_for_territory_dets="";
  if(count($territory_dets_array) >1)
  {
   $option_for_territory_dets.="<option value=''>Select Territory </option>";
  }

  foreach ($territory_dets_array as $territory_key => $territory_value)
  {
   
    $selected= "";
   
    $option_for_territory_dets.="<option value=".trim($territory_value['code'])."  ".$selected.">".trim($territory_value['name'])."</option>"; 
  }
  $response['territory_details']=$option_for_territory_dets;
}
$response['sql']=$sql;
//print_r($sql);exit;
echo json_encode($response);exit;
}
?>
