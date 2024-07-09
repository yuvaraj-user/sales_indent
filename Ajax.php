<?php
include '../auto_load.php';
include 'Sales_Indent_Details.php';
include 'Sales_indent_zone_details.php';
include 'Dashboard_details.php';


$Sales_Indent_Details=new Sales_Indent_Details($conn);
$Sales_Indent_Zone_Details=new Sales_indent_zone_details($conn);
$Dashboard_details =new Dashboard_details($conn);

$Action=@$_REQUEST['Action'];
if($Action=='View_Sales_Indent_Details')
{
	$user_input=$_POST;
	$result= $Sales_Indent_Details->View_Sales_Indent_Details($_POST);
	echo json_encode($result);exit;
}else if($Action=='Approve_Details_For_With_In_Limit')
{
	$user_input=$_POST;
	$result= $Sales_Indent_Details->Approve_Details_For_With_In_Limit($_POST);
	echo json_encode($result);exit;
}else if($Action=='Approve_Details_For_With_In_Limit_Direct')
{
	$user_input=$_POST;
	$result= $Sales_Indent_Details->Approve_Details_For_With_In_Limit_Direct($_POST);
	echo json_encode($result);exit;
}else if($Action=='Recommedation_Details_For_Limit_Exceed')
{
	$user_input=$_POST;
	$result= $Sales_Indent_Details->Recommedation_Details_For_Limit_Exceed($_POST);
	echo json_encode($result);exit;
}else if($Action=='Sales_Indent_Validation_With_In_Limit')
{
	$user_input=$_POST;
	$result= $Sales_Indent_Details->Sales_Indent_Validation_With_In_Limit($_POST);
	echo json_encode($result);exit;
}else if($Action=='Validation_Details_For_Limit_Exceed')
{
	$user_input=$_POST;
	$result= $Sales_Indent_Details->Validation_Details_For_Limit_Exceed($_POST);
	echo json_encode($result);exit;
}else if($Action=='Approve_Details_For_With_Limit_Exceed')
{
	$user_input=$_POST;
	$result= $Sales_Indent_Details->Approve_Details_For_With_Limit_Exceed($_POST);
	echo json_encode($result);exit;
}else if($Action=='Get_Customer_Collection_Details')
{
	$user_input=$_POST;
	$result= $Sales_Indent_Details->Get_Customer_Collection_Details($_POST);
	echo json_encode($result);exit;
}else if($Action=='View_Cutsomer_Claim_Details')
{
	$user_input=$_POST;
	$result= $Sales_Indent_Details->View_Cutsomer_Claim_Details($_POST);
	echo json_encode($result);exit;
}else if($Action=='Limit_Exceed_Limit_Checking')
{
	$user_input=$_POST;
	$result= $Sales_Indent_Details->Limit_Exceed_Limit_Checking($_POST);
	echo json_encode($result);exit;
} else if($Action=='get_zone_wise_indent')
{
	$user_input=$_POST;
	$result= $Sales_Indent_Zone_Details->zone_wise_sales_indent($_POST);
	echo json_encode($result);exit;
} else if($Action=='get_region_wise_indent')
{
	$user_input=$_POST;
	$result= $Sales_Indent_Zone_Details->region_wise_sales_indent($_POST);
	echo json_encode($result);exit;
} else if($Action=='territory_wise_sales_indent')
{
	$user_input = $_POST;
	$result = $Sales_Indent_Zone_Details->territory_wise_sales_indent($_POST);
	echo json_encode($result);exit;
} else if($Action=='crop_wise_sales_indent')
{
	$user_input = $_POST;
	$result = $Sales_Indent_Zone_Details->crop_wise_sales_indent($_POST);
	echo json_encode($result);exit;
} else if($Action=='variety_wise_sales_indent')
{
	$user_input = $_POST;
	$result = $Sales_Indent_Zone_Details->variety_wise_sales_indent($_POST);
	echo json_encode($result);exit;
}
 else if($Action=='sales_indent_turnover_amount')
{
	$user_input = $_POST;
	$result = $Dashboard_details->sales_indent_turnover_amount($_POST);
	echo json_encode($result);exit;
}

 else if($Action=='sales_indent_plant_percentage')
{
	$user_input = $_POST;
	$result = $Dashboard_details->sales_indent_plant_percentage($_POST);
	echo json_encode($result);exit;
}

 else if($Action=='get_indent_status_details')
{
	$user_input = $_POST;
	$result = $Dashboard_details->get_indent_status_details($_POST);
	echo json_encode($result);exit;
}

 else if($Action=='crop_wise_indent_summary')
{
	$user_input = $_POST;
	$result = $Dashboard_details->crop_wise_indent_summary($_POST);
	echo json_encode($result);exit;
}

 else if($Action=='product_wise_indent_summary')
{
	$user_input = $_POST;
	$result = $Dashboard_details->product_wise_indent_summary($_POST);
	echo json_encode($result);exit;
}
 else if($Action=='zone_wise_indent_summary')
{
	$user_input = $_POST;
	$result = $Dashboard_details->zone_wise_indent_summary($_POST);
	echo json_encode($result);exit;
}
 else if($Action=='region_wise_indent_summary')
{
	$user_input = $_POST;
	$result = $Dashboard_details->region_wise_indent_summary($_POST);
	echo json_encode($result);exit;
}

 else if($Action=='territory_wise_indent_summary')
{
	$user_input = $_POST;
	$result = $Dashboard_details->territory_wise_indent_summary($_POST);
	echo json_encode($result);exit;
}


 ?>