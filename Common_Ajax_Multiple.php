<?php 
include '../auto_load.php';
/*---------------------------Sathish --------------------------------------*/

$Action=@$_REQUEST['Action'];

$product_division=isset($_REQUEST['product_division'])&&!empty($_REQUEST['product_division']) ? $_REQUEST['product_division'] : "";
$crop_code = isset($_REQUEST['crop_code'])&&!empty($_REQUEST['crop_code']) ? $_REQUEST['crop_code'] : "0";
$zone_id = isset($_REQUEST['zone_id'])&&!empty($_REQUEST['zone_id']) ? $_REQUEST['zone_id'] : "0";
$status = isset($_REQUEST['status'])&&!empty($_REQUEST['status']) ? $_REQUEST['status'] : "0";



function SalesIndent_wise_chart_details($product_division,$crop_code,$zone_id,$status)
{
  global $conn;
   $product_division=@$_POST['product_division'];
	if($product_division=="ras"){
		$product_division="CT01";
	}else if($product_division=="fcm"){
		$product_division="FC01";
	} else if($product_division=="frg"){
		$product_division="FR01";
	}

    $crop_code=@$_POST['crop_code'];
	$zone_code=@$_POST['zone_id'];
	$Material_Code=@$_POST['Material_Code'];


	
   $result=array();
   $Hybrid_wise_chart_array = array();

   $sql_for_Hybrid_wise_dets = "EXEC Salesindent_Material_Details_Append @Crop_Code='".@$crop_code."',@Product_Division='".@$product_division."',@Zone_Id='".$zone_code."' ";
   $Hybrid_wise_chart_stmt = sqlsrv_prepare($conn, $sql_for_Hybrid_wise_dets);
   sqlsrv_execute($Hybrid_wise_chart_stmt);      
   while($Hybrid_dets = sqlsrv_fetch_array($Hybrid_wise_chart_stmt,SQLSRV_FETCH_ASSOC))
   { 
      $total_qty=$Hybrid_dets['ProductCode'];

    //  print_r($total_qty);
      
        $Hybrid_wise_chart_array[] = array("Hybrid"=>$Hybrid_dets['ProductCode']);
      
   }
   return $Hybrid_wise_chart_array;

}




 if($Action == "get_Material_Append_details")
 {
    $result=array();
    $result['Hybrid_wise_dets'] = SalesIndent_wise_chart_details($product_division,$crop_code,$zone_id,$status);

 
  echo json_encode($result);exit;

 }




 if($Action =="get_Material_Count_details"){
	$product_division=@$_POST['product_division'];
	if($product_division=="ras"){
		$product_division="CT01";
	}else if($product_division=="fcm"){
		$product_division="FC01";
	} else if($product_division=="frg"){
		$product_division="FR01";
	}

    $crop_code=@$_POST['crop_code'];
	$zone_code=@$_POST['zone_id'];
	$Material_Code=@$_POST['Material_Code'];

	
	$sql="EXEC Salesindent_Material_Details_Count @Crop_Code='".@$crop_code."',@Product_Division='".@$product_division."',@Zone_Id='".$zone_code."'";

  $Details  = sqlsrv_query($conn,$sql);
	$result = sqlsrv_fetch_array($Details);
	$Response=array(
	'Material_Count'=>$result['Material_Count']
	);
	echo json_encode(array('data'=>$Response));
}





?>