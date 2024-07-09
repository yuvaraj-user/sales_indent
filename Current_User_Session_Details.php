<?php
$current_user_id=$_SESSION['EmpID'];
$session_role_id=$_SESSION['Dcode'];
$location_array=array();
$zone_dets_array=array();
$region_dets_array=array();
$territory_dets_array=array();

/* Get Product Division Details Start Here */
 $product_division_array=$zone_details_array=$region_details_array=$territory_details_array=[];
 $user_product_division_id=$user_zone_id=$user_region_id=$user_territory_id="";
 $product_division_sql =sqlsrv_query($conn,"spSel_UserProductDivision @LoginId='".$current_user_id."',@UserLevel='".$session_role_id."'");
  while($product_division_result = sqlsrv_fetch_array($product_division_sql))
  {
    $product_division_array[] = $product_division_result['product_division'];
  }

  if(count($product_division_array)>1){
    $user_product_division_id=@$product_division_array[0];
  }else{
     $user_product_division_id =implode(',',$product_division_array);
  }
/* Get Product Division Details End Here */
$session_details=get_current_user_details($user_product_division_id,$current_user_id,$session_role_id);

 $session_zone_id=$session_details['session_zone_id'];
 $session_region_id=$session_details['session_region_id'];
 $session_territory_id=$session_details['session_territory_id'];


function get_current_user_details($user_product_division_id,$current_user_id,$session_role_id)
{

  global $conn;
  $location_array=$zone_dets_array=$region_dets_array=$territory_dets_array=array();
  $zone_id_array=$region_id_array=$territory_id_array=array();
  $sql="EXEC Role_Based_Location_Dimension @Plant_Divison_Code='$user_product_division_id',@Role_ID='$session_role_id',@User_ID='$current_user_id',@ZoneCode='0',@RegionCode='0',@TerritoryCode='0'";
  $sql_for_location_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_location_details);
 while($result = sqlsrv_fetch_array($sql_for_location_details,SQLSRV_FETCH_ASSOC))
 {
  if($result['SAPZONEID'] !="" && $result['ZONENAME'] !="")
  {
    $zone_dets_array[]=array('code'=>$result['SAPZONEID'],'name'=>$result['ZONENAME'],'data_area_id'=>strtolower($result['DATAAREAID']));
    $zone_id_array[]=$result['SAPZONEID'];
  }

  if($result['SAPREGIONID'] !="" && $result['REGIONNAME'] !="")
  {
     $region_dets_array[]=array('code'=>$result['SAPREGIONID'],'name'=>$result['REGIONNAME'],'data_area_id'=>strtolower($result['DATAAREAID']));
     $region_id_array[]=$result['SAPREGIONID'];
  }

  if($result['SAPTMID'] !="" && $result['TMNAME'] !="")
  {
      $territory_dets_array[]=array('code'=>$result['SAPTMID'],'name'=>$result['TMNAME'],'data_area_id'=>strtolower($result['DATAAREAID']));
      $territory_id_array[]=$result['SAPTMID'];
  }
 }
 
$zone_dets_array = array_unique($zone_dets_array, SORT_REGULAR);
$region_dets_array = array_unique($region_dets_array, SORT_REGULAR);
$territory_dets_array = array_unique($territory_dets_array, SORT_REGULAR);

$zone_id_array = array_unique($zone_id_array, SORT_REGULAR);
$region_id_array = array_unique($region_id_array, SORT_REGULAR);
$territory_id_array = array_unique($territory_id_array, SORT_REGULAR);

 $session_zone_id =implode(',',$zone_id_array);
 $session_region_id =implode(',',$region_id_array);
 $session_territory_id =implode(',',$territory_id_array);

 return array('session_zone_id'=>$session_zone_id,'session_region_id'=>$session_region_id,'session_territory_id'=>$session_territory_id); 
}










