<?php 
include_once('Dashboard_details.php');
function Get_Menu_Access_Details($sql)
{
  global $conn;
  $connection=sqlsrv_query($conn,$sql);
  $Result = sqlsrv_fetch_array($connection,SQLSRV_FETCH_ASSOC);
  $count=@$Result['Count'];
  return $count !='' ? $count : 0;
}

function Get_user_indent_count($sql)
  {
    global $conn;
    $Sql_Details  = sqlsrv_prepare($conn, $sql);
    sqlsrv_execute($Sql_Details);
    $count = sqlsrv_fetch_array($Sql_Details,SQLSRV_FETCH_ASSOC)['TOTALROW'];
    return $count;
  }

function sto_notification_qry($status)
{
  global $conn;

  $sql = "SELECT TOTALROW = COUNT(*) OVER(),Material_Tbl.season as Season_Code,Trn_Tbl.SalesIndentId,ReqId,(CASE WHEN ProductDivision='ras' THEN 'CT01' WHEN ProductDivision='fcm' THEN 'FC01' WHEN ProductDivision='frg' THEN 'FR01' ELSE  ProductDivision END) AS Sales_Org,(CASE WHEN ProductDivision='ras' THEN 'Cotton' WHEN ProductDivision='fcm' THEN 'Field Crop' WHEN ProductDivision='frg' THEN 'Forage Seeds' ELSE ProductDivision END) AS ProductDivision,(CASE WHEN SupplyType=1 THEN 'Direct Supply' WHEN SupplyType=2 THEN 'C&F Supply' ELSE SupplyType END) AS SupplyType,(CASE WHEN Material_Tbl.CurrentStatus=1 AND Material_Tbl.RejectionStatus=1 THEN 'Waitting For Approval (With In Limit)'  WHEN Material_Tbl.CurrentStatus=2 AND Material_Tbl.RejectionStatus=1 THEN 'Waitting For Recommendation (Limit Exceed)' WHEN Material_Tbl.CurrentStatus=3 AND Material_Tbl.RejectionStatus=1 THEN 'Waitting For Approval (Limit Exceed)'WHEN Material_Tbl.CurrentStatus=4 AND Material_Tbl.RejectionStatus=1 THEN 'Completed' WHEN Material_Tbl.CurrentStatus=1 AND Material_Tbl.RejectionStatus=2 THEN 'Rejected(Approval - With In Limit)'WHEN Material_Tbl.CurrentStatus=2 AND Material_Tbl.RejectionStatus=2 THEN 'Rejected(Recommendation - Limit Exceed)'WHEN Material_Tbl.CurrentStatus=3 AND Material_Tbl.RejectionStatus=2 THEN 'Rejected(Approval - Limit Exceed)'ELSE ' ' End) AS Status_Text,(CASE WHEN Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."'  AND Material_Tbl.CurrentStatus=5 AND Material_Tbl.RejectionStatus=1 THEN 1 ELSE 0 END) AS Approve_Permission_With_In_Limit,(CASE WHEN Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."'  AND Material_Tbl.CurrentStatus=1 AND Material_Tbl.RejectionStatus=1 THEN 1 ELSE 0 END) AS Validate_Permission_With_In_Limit,(CASE WHEN Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."'  AND Material_Tbl.CurrentStatus=3 AND Material_Tbl.RejectionStatus=1 THEN 1 ELSE 0 END) AS Validate_Permission_Limit_Exceed,(CASE WHEN Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."'  AND Material_Tbl.CurrentStatus=2 AND Material_Tbl.RejectionStatus=1 THEN 1 ELSE 0 END) AS Recommend_Permission_Limit_Exceed,(CASE WHEN Mapping.Approver_Emp_Id='".$_SESSION['EmpID']."'  AND Material_Tbl.CurrentStatus=5 AND Material_Tbl.RejectionStatus=1 THEN 1 ELSE 0 END) AS Approve_Permission_Limit_Exceed,CONVERT (varchar(10),ReqDate, 105) AS ReqDate,Trn_Tbl.RequestBy,Trn_Tbl.ReqDate AS Req_Date,CONVERT (varchar(10),Material_Tbl.Approved_At, 105) AS Approved_Date,Trn_Tbl.ZoneId,Zone_Tbl.ZONENAME,Trn_Tbl.RegionId,Region_Tbl.REGIONNAME,Trn_Tbl.TerritoryId,Trn_Tbl.PlantId,Material_Tbl.CurrentStatus,Material_Tbl.MaterialCode,Material_Tbl.MaterialQtyInKg,Material_Tbl.MaterialQtyInPkt,Material_Tbl.QtyInBag,Material_Tbl.QtyInKg,Material_Tbl.QtyInPkt,Material_Tbl.Id,Material_Tbl.Validate_by,Material_Tbl.Approved_by,Material_Tbl.Validate_At,Material_Tbl.Approved_At,Trn_Tbl.CropId,Trn_Tbl.PlantId,Trn_Tbl.Receiving_Plant,Plant_Tbl.Plant_Name as Receiving_Plant_Name,Crop_Tbl.CropName,Crop_Tbl.Division,Material_Tbl.PlanQty,Material_Tbl.RejectionStatus,Expected_Date FROM Sales_Indent_STO AS Trn_Tbl
  INNER JOIN (SELECT DISTINCT DATAAREAID,REGIONNAME,SAPREGIONID FROM RASI_REGIONTABLE) AS Region_Tbl ON Region_Tbl.DATAAREAID=Trn_Tbl.ProductDivision AND Region_Tbl.SAPREGIONID=Trn_Tbl.RegionId
  INNER JOIN (SELECT DISTINCT DATAAREAID,ZONENAME,SAPZONEID FROM RASI_ZONETABLE) AS Zone_Tbl ON Zone_Tbl.SAPZONEID=Trn_Tbl.ZoneId AND Zone_Tbl.DATAAREAID=Trn_Tbl.ProductDivision
  LEFT JOIN RASI_Role_Mapping_With_Crop_STO AS Mapping ON Mapping.Requester_Emp_Id=Trn_Tbl.RequestBy AND Mapping.Product_Division=Trn_Tbl.ProductDivision AND Mapping.Crop=Trn_Tbl.CropId
  INNER JOIN Sales_Indent_STO_Material_Details AS Material_Tbl ON Material_Tbl.SalesIndentId=Trn_Tbl.SalesIndentId
  INNER JOIN (SELECT DISTINCT CropCode,CropName,Division FROM Master_Stock_Prod_2_Crop) AS Crop_Tbl ON Crop_Tbl.CropCode=Trn_Tbl.CropId
  LEFT JOIN EMPLTABLE as EMPLTABLE on EMPLTABLE.EMPLID=Trn_Tbl.RequestBy

  LEFT join (select distinct Plant_Code,Plant_Name,Sales_org from Master_Plant) as Plant_Tbl ON Plant_Tbl.Plant_Code=Trn_Tbl.Receiving_Plant 
  AND Plant_Tbl.Sales_org=Trn_Tbl.ProductDivision  
  WHERE 1=1 and  Mapping.Type='STO_Indent'
  AND (Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."' OR Mapping.Approver_Emp_Id='".$_SESSION['EmpID']."' OR Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."')
  AND Material_Tbl.CurrentStatus = '".$status."'
  AND Material_Tbl.RejectionStatus='1'";

  $connection=sqlsrv_query($conn,$sql);
  $res_arr = array();
  while($Result = sqlsrv_fetch_array($connection,SQLSRV_FETCH_ASSOC)) {
    $res_arr[] = $Result;
  }

  return $res_arr;

}


function indent_limit_exceed_qry($status,$from)
{
  global $conn;
  $sql = "SELECT";

  if($from == 'recommendation') {
    $sql .= " COUNT(CASE WHEN Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' THEN '1' ELSE NULL END) AS recommendation_total_count,
    SUM(CASE WHEN Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' THEN CAST(Material_Tbl.QtyInPkt AS Decimal(18,0)) ELSE 0 END) AS recommendation_total_qty, 
    COUNT(CASE WHEN (Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '1') THEN '1' ELSE NULL END) AS recommendation_direct_count,
    SUM(CASE WHEN (Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '1') THEN CAST(Material_Tbl.QtyInPkt AS Decimal(18,0)) ELSE 0 END) AS recommendation_direct_qty,
    COUNT(CASE WHEN (Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '2') THEN '1' ELSE NULL END) AS recommendation_cf_count,
    SUM(CASE WHEN (Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '2') THEN CAST(Material_Tbl.QtyInPkt AS Decimal(18,0)) ELSE 0 END) AS recommendation_cf_qty";
  } elseif($from == 'validate') {
        $sql .= " COUNT(CASE WHEN Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."' THEN '1' ELSE NULL END) AS validate_total_count,
    SUM(CASE WHEN Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."' THEN CAST(Material_Tbl.QtyInPkt AS Decimal(18,0)) ELSE 0 END) AS validate_total_qty, 
    COUNT(CASE WHEN (Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '1') THEN '1' ELSE NULL END) AS validate_direct_count,
    SUM(CASE WHEN (Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '1') THEN CAST(Material_Tbl.QtyInPkt AS Decimal(18,0)) ELSE 0 END) AS validate_direct_qty,
    COUNT(CASE WHEN (Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '2') THEN '1' ELSE NULL END) AS validate_cf_count,
    SUM(CASE WHEN (Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '2') THEN CAST(Material_Tbl.QtyInPkt AS Decimal(18,0)) ELSE 0 END) AS validate_cf_qty";
  } elseif($from == 'approve') {
        $sql .= " COUNT(CASE WHEN Mapping.Approver_Emp_Id='".$_SESSION['EmpID']."' THEN '1' ELSE NULL END) AS approve_total_count,
    SUM(CASE WHEN Mapping.Approver_Emp_Id='".$_SESSION['EmpID']."' THEN CAST(Material_Tbl.QtyInPkt AS Decimal(18,0)) ELSE 0 END) AS approve_total_qty, 
    COUNT(CASE WHEN (Mapping.Approver_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '1') THEN '1' ELSE NULL END) AS approve_direct_count,
    SUM(CASE WHEN (Mapping.Approver_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '1') THEN CAST(Material_Tbl.QtyInPkt AS Decimal(18,0)) ELSE 0 END) AS approve_direct_qty,
    COUNT(CASE WHEN (Mapping.Approver_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '2') THEN '1' ELSE NULL END) AS approve_cf_count,
    SUM(CASE WHEN (Mapping.Approver_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '2') THEN CAST(Material_Tbl.QtyInPkt AS Decimal(18,0)) ELSE 0 END) AS approve_cf_qty";
  }

  $sql .= " FROM Sales_Indent AS Trn_Tbl
  INNER JOIN (SELECT DISTINCT EMPLID,EMPLNAME,SAPTMID,TMNAME,DATAAREAID FROM RASI_TMTABLE) AS Tm_Tbl ON Tm_Tbl.DATAAREAID=Trn_Tbl.ProductDivision AND Tm_Tbl.SAPTMID=Trn_Tbl.TerritoryId
  INNER JOIN (SELECT DISTINCT DATAAREAID,ZONENAME,SAPZONEID FROM RASI_ZONETABLE) AS Zone_Tbl ON Zone_Tbl.SAPZONEID=Trn_Tbl.ZoneId AND Zone_Tbl.DATAAREAID=Trn_Tbl.ProductDivision
  INNER JOIN (SELECT DISTINCT DATAAREAID,REGIONNAME,SAPREGIONID FROM RASI_REGIONTABLE) AS Region_Tbl ON Region_Tbl.SAPREGIONID=Trn_Tbl.RegionId AND Region_Tbl.DATAAREAID=Trn_Tbl.ProductDivision
  INNER JOIN (SELECT DISTINCT Type,Descrpition FROM Master_SalesIndent_QuotationType) AS Quotation_Tbl ON Quotation_Tbl.Type=Trn_Tbl.QuotationType
  INNER JOIN (SELECT DISTINCT Type,Descrpition FROM Master_SalesIndent_SaleOrderType) AS Sales_Order_Tbl ON Sales_Order_Tbl.Type=Trn_Tbl.SaleOrderType
  INNER JOIN Sales_Indent_Material_Details AS Material_Tbl ON Material_Tbl.SalesIndentId=Trn_Tbl.SalesIndentId
  LEFT JOIN SD_CUS_BAL ON SD_CUS_BAL.CUSTOMER=Trn_Tbl.CustomerCode
  LEFT JOIN SD_CUS_MASTER ON SD_CUS_MASTER.Customer=Trn_Tbl.CustomerCode
  INNER JOIN (SELECT DISTINCT CropCode,CropName,Division FROM Master_Stock_Prod_2_Crop) AS Crop_Tbl ON Crop_Tbl.CropCode=Trn_Tbl.CropId
  LEFT JOIN EMPLTABLE as EMPLTABLE on EMPLTABLE.EMPLID=Trn_Tbl.RequestBy
  LEFT JOIN DIRPARTYTABLE as DIRPARTYTABLE on DIRPARTYTABLE.RECID=EMPLTABLE.RECID

  LEFT JOIN (select distinct Product_Division,Supply_Type,Plant_Code,Plant_Name,Region from Master_Plant) as Direct_Supply_Plant_Tbl on Direct_Supply_Plant_Tbl.Plant_Code=Trn_Tbl.PlantId AND Direct_Supply_Plant_Tbl.Region=Trn_Tbl.RegionId and Direct_Supply_Plant_Tbl.Supply_Type=(CASE WHEN Trn_Tbl.SupplyType='1' THEN 'Direct Supply'  ELSE 'C&F supply' END) and Direct_Supply_Plant_Tbl.Product_Division=(CASE WHEN Trn_Tbl.ProductDivision='ras' THEN 'CT01' WHEN Trn_Tbl.ProductDivision='fcm' THEN 'FC01'  WHEN Trn_Tbl.ProductDivision='frg' THEN 'FR01' ELSE ' ' END) AND Direct_Supply_Plant_Tbl.Region=(CASE WHEN Trn_Tbl.SupplyType='1' THEN Direct_Supply_Plant_Tbl.Region  ELSE Trn_Tbl.RegionId END) 
  left join Sales_Indnet_User_Plant_Master as CNF_Plant_Table on CNF_Plant_Table.Plant_Code=Trn_Tbl.PlantId and CNF_Plant_Table.Emp_Code=Trn_Tbl.RequestBy
  LEFT JOIN (SELECT DISTINCT KUNNR,VKORG,VTWEG FROM SALES_AREA) AS Distribution_Tbl On Distribution_Tbl.KUNNR=Trn_Tbl.CustomerCode and Distribution_Tbl.VKORG=(CASE WHEN Trn_Tbl.ProductDivision='ras' THEN 'CT01' WHEN Trn_Tbl.ProductDivision='fcm' THEN 'FC01'  WHEN Trn_Tbl.ProductDivision='frg' THEN 'FR01' ELSE ' ' END) LEFT JOIN RASI_Role_Mapping_With_Crop Mapping ON Mapping.Requester_Emp_Id=Trn_Tbl.RequestBy AND Mapping.Sub_Type =(CASE WHEN Trn_Tbl.SupplyType='1' THEN 'Direct_Supply'  ELSE 'C&F_Supply' END) AND Mapping.Product_Division=Trn_Tbl.ProductDivision AND Mapping.crop=Trn_Tbl.CropId  WHERE 1=1 and Material_Tbl.CurrentStatus !='0'  AND Material_Tbl.CurrentStatus = '".$status."' AND Material_Tbl.RejectionStatus=1";
// echo $sql;exit;
  $connection=sqlsrv_query($conn,$sql);
  $res_arr = array();
  while($Result = sqlsrv_fetch_array($connection,SQLSRV_FETCH_ASSOC)) {
    $res_arr[] = $Result;
  }

  return $res_arr;
}


function indent_with_in_limit_qry($status)
{
  global $conn;
  $sql = "SELECT COUNT(CASE WHEN Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' THEN '1' ELSE NULL END) AS approve_total_count,
    SUM(CASE WHEN Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' THEN CAST(Material_Tbl.QtyInPkt AS Decimal(18,0)) ELSE 0 END) AS approve_total_qty, 
    COUNT(CASE WHEN (Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '1') THEN '1' ELSE NULL END) AS approve_direct_count,
    SUM(CASE WHEN (Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '1') THEN CAST(Material_Tbl.QtyInPkt AS Decimal(18,0)) ELSE 0 END) AS approve_direct_qty,
    COUNT(CASE WHEN (Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '2') THEN '1' ELSE NULL END) AS approve_cf_count,
    SUM(CASE WHEN (Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' AND Trn_Tbl.SupplyType = '2') THEN CAST(Material_Tbl.QtyInPkt AS Decimal(18,0)) ELSE 0 END) AS approve_cf_qty

    FROM Sales_Indent AS Trn_Tbl
    INNER JOIN (SELECT DISTINCT EMPLID,EMPLNAME,SAPTMID,TMNAME,DATAAREAID FROM RASI_TMTABLE) AS Tm_Tbl ON Tm_Tbl.DATAAREAID=Trn_Tbl.ProductDivision AND Tm_Tbl.SAPTMID=Trn_Tbl.TerritoryId
    INNER JOIN (SELECT DISTINCT DATAAREAID,ZONENAME,SAPZONEID FROM RASI_ZONETABLE) AS Zone_Tbl ON Zone_Tbl.SAPZONEID=Trn_Tbl.ZoneId AND Zone_Tbl.DATAAREAID=Trn_Tbl.ProductDivision
    INNER JOIN (SELECT DISTINCT DATAAREAID,REGIONNAME,SAPREGIONID FROM RASI_REGIONTABLE) AS Region_Tbl ON Region_Tbl.SAPREGIONID=Trn_Tbl.RegionId AND Region_Tbl.DATAAREAID=Trn_Tbl.ProductDivision
    INNER JOIN (SELECT DISTINCT Type,Descrpition FROM Master_SalesIndent_QuotationType) AS Quotation_Tbl ON Quotation_Tbl.Type=Trn_Tbl.QuotationType
    INNER JOIN (SELECT DISTINCT Type,Descrpition FROM Master_SalesIndent_SaleOrderType) AS Sales_Order_Tbl ON Sales_Order_Tbl.Type=Trn_Tbl.SaleOrderType
    INNER JOIN Sales_Indent_Material_Details AS Material_Tbl ON Material_Tbl.SalesIndentId=Trn_Tbl.SalesIndentId
    LEFT JOIN SD_CUS_BAL ON SD_CUS_BAL.CUSTOMER=Trn_Tbl.CustomerCode
    LEFT JOIN SD_CUS_MASTER ON SD_CUS_MASTER.Customer=Trn_Tbl.CustomerCode
    INNER JOIN (SELECT DISTINCT CropCode,CropName,Division FROM Master_Stock_Prod_2_Crop) AS Crop_Tbl ON Crop_Tbl.CropCode=Trn_Tbl.CropId
    LEFT JOIN EMPLTABLE as EMPLTABLE on EMPLTABLE.EMPLID=Trn_Tbl.RequestBy
    LEFT JOIN DIRPARTYTABLE as DIRPARTYTABLE on DIRPARTYTABLE.RECID=EMPLTABLE.RECID

    LEFT JOIN (select distinct Product_Division,Supply_Type,Plant_Code,Plant_Name,Region from Master_Plant) as Direct_Supply_Plant_Tbl on Direct_Supply_Plant_Tbl.Plant_Code=Trn_Tbl.PlantId AND Direct_Supply_Plant_Tbl.Region=Trn_Tbl.RegionId and Direct_Supply_Plant_Tbl.Supply_Type=(CASE WHEN Trn_Tbl.SupplyType='1' THEN 'Direct Supply'  ELSE 'C&F supply' END) and Direct_Supply_Plant_Tbl.Product_Division=(CASE WHEN Trn_Tbl.ProductDivision='ras' THEN 'CT01' WHEN Trn_Tbl.ProductDivision='fcm' THEN 'FC01'  WHEN Trn_Tbl.ProductDivision='frg' THEN 'FR01' ELSE ' ' END) AND Direct_Supply_Plant_Tbl.Region=(CASE WHEN Trn_Tbl.SupplyType='1' THEN Direct_Supply_Plant_Tbl.Region  ELSE Trn_Tbl.RegionId END) 
    left join Sales_Indnet_User_Plant_Master as CNF_Plant_Table on CNF_Plant_Table.Plant_Code=Trn_Tbl.PlantId and CNF_Plant_Table.Emp_Code=Trn_Tbl.RequestBy
    LEFT JOIN (SELECT DISTINCT KUNNR,VKORG,VTWEG FROM SALES_AREA) AS Distribution_Tbl On Distribution_Tbl.KUNNR=Trn_Tbl.CustomerCode and Distribution_Tbl.VKORG=(CASE WHEN Trn_Tbl.ProductDivision='ras' THEN 'CT01' WHEN Trn_Tbl.ProductDivision='fcm' THEN 'FC01'  WHEN Trn_Tbl.ProductDivision='frg' THEN 'FR01' ELSE ' ' END) 

    LEFT JOIN RASI_Role_Mapping_With_Crop Mapping ON Mapping.Requester_Emp_Id=Trn_Tbl.RequestBy AND Mapping.Sub_Type =(CASE WHEN Trn_Tbl.SupplyType='1' THEN 'Direct_Supply'  ELSE 'C&F_Supply' END) AND Mapping.Product_Division=Trn_Tbl.ProductDivision  AND Mapping.Crop=Trn_Tbl.CropId
    WHERE 1=1 and Material_Tbl.CurrentStatus !='0' AND Limit_Exceed !='1' AND (Mapping.Requester_Emp_Id='".$_SESSION['EmpID']."' OR Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' OR Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."' OR Mapping.Approver_Emp_Id='".$_SESSION['EmpID']."') AND Material_Tbl.CurrentStatus = '".$status."' AND Material_Tbl.RejectionStatus=1";
    $connection=sqlsrv_query($conn,$sql);
    $res_arr = array();
    while($Result = sqlsrv_fetch_array($connection,SQLSRV_FETCH_ASSOC)) {
      $res_arr[] = $Result;
  }

  return $res_arr;
}



function sto_with_in_limit_qry($status,$from)
{
  global $conn;
  $sql = "SELECT Plant_Tbl.Plant_Name,Plant_Tbl.Plant_Code,COUNT(Trn_Tbl.PlantId) as recommendation_count
  FROM Sales_Indent_STO AS Trn_Tbl INNER JOIN (SELECT DISTINCT DATAAREAID,REGIONNAME,SAPREGIONID FROM RASI_REGIONTABLE) AS Region_Tbl ON Region_Tbl.DATAAREAID=Trn_Tbl.ProductDivision AND Region_Tbl.SAPREGIONID=Trn_Tbl.RegionId INNER JOIN (SELECT DISTINCT DATAAREAID,ZONENAME,SAPZONEID FROM RASI_ZONETABLE) AS Zone_Tbl ON Zone_Tbl.SAPZONEID=Trn_Tbl.ZoneId AND Zone_Tbl.DATAAREAID=Trn_Tbl.ProductDivision LEFT JOIN RASI_Role_Mapping_With_Crop_STO AS Mapping ON Mapping.Requester_Emp_Id=Trn_Tbl.RequestBy AND Mapping.Product_Division=Trn_Tbl.ProductDivision AND Mapping.Crop=Trn_Tbl.CropId INNER JOIN Sales_Indent_STO_Material_Details AS Material_Tbl ON Material_Tbl.SalesIndentId=Trn_Tbl.SalesIndentId INNER JOIN (SELECT DISTINCT CropCode,CropName,Division FROM Master_Stock_Prod_2_Crop) AS Crop_Tbl ON Crop_Tbl.CropCode=Trn_Tbl.CropId LEFT JOIN EMPLTABLE as EMPLTABLE on EMPLTABLE.EMPLID=Trn_Tbl.RequestBy LEFT join (select distinct Plant_Code,Plant_Name,Sales_org from Master_Plant) as Plant_Tbl ON Plant_Tbl.Plant_Code=Trn_Tbl.PlantId AND Plant_Tbl.Sales_org=Trn_Tbl.ProductDivision WHERE 1=1 and Mapping.Type='STO_Indent'";

    if($from == 'validate_with_in_limit' || $from == 'recommendation_limit_exceed') {
        $sql .= " AND Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' ";
    } elseif ($from == 'approve_with_in_limit' || $from == 'validate_limit_exceed') {
        $sql .= " AND Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."'";
    } elseif ($from == 'approve_limit_exceed') {
        $sql .= " AND Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."'";
    }

    $sql .= " AND Material_Tbl.CurrentStatus = '".$status."' AND Material_Tbl.RejectionStatus='1' group by Plant_Tbl.Plant_Name,Trn_Tbl.PlantId,Plant_Tbl.Plant_Code";
    // echo $sql;exit;

    $connection=sqlsrv_query($conn,$sql);
    $res_arr = array();
    while($Result = sqlsrv_fetch_array($connection,SQLSRV_FETCH_ASSOC)) {
      $res_arr[] = $Result;
  }

  return $res_arr;
}


$requeter_menu=Get_Menu_Access_Details("EXEC Sales_Indent_Role_Mapping_Details_CROP @EmpId='".$_SESSION['EmpID']."',@Type='Sales_Indent',@Statement_Type='Requester_Emp_Id'");
$Approve_Menu=Get_Menu_Access_Details("EXEC Sales_Indent_Role_Mapping_Details_CROP @EmpId='".$_SESSION['EmpID']."',@Type='Sales_Indent',@Statement_Type='Requester_Emp_Id'");
$Recommend_Menu=Get_Menu_Access_Details("EXEC Sales_Indent_Role_Mapping_Details_CROP @EmpId='".$_SESSION['EmpID']."',@Type='Sales_Indent',@Statement_Type='Recommender_Emp_Id'");
$Validate_Menu=Get_Menu_Access_Details("EXEC Sales_Indent_Role_Mapping_Details_CROP @EmpId='".$_SESSION['EmpID']."',@Type='Sales_Indent',@Statement_Type='Recommender_Level_2_Emp_Id'");
$Approve_limit_Exceed_Menu=Get_Menu_Access_Details("EXEC Sales_Indent_Role_Mapping_Details_CROP @EmpId='".$_SESSION['EmpID']."',@Type='Sales_Indent',@Statement_Type='Approver_Emp_Id'");

$Requestor_Menu_For_Sales_Planning=Get_Menu_Access_Details("EXEC Sales_Indent_Role_Mapping_Details @EmpId='".$_SESSION['EmpID']."',@Type='Sales_Planning',@Statement_Type='Requester_Emp_Id'");
$Validation_Level_1_Menu_For_Sales_Planning=Get_Menu_Access_Details("EXEC Sales_Indent_Role_Mapping_Details @EmpId='".$_SESSION['EmpID']."',@Type='Sales_Planning',@Statement_Type='Recommender_Emp_Id'");
$Validation_Level_2_Menu_For_Sales_Planning=Get_Menu_Access_Details("EXEC Sales_Indent_Role_Mapping_Details @EmpId='".$_SESSION['EmpID']."',@Type='Sales_Planning',@Statement_Type='Recommender_Level_2_Emp_Id'");
$Approver_Menu_For_Sales_Planning=Get_Menu_Access_Details("EXEC Sales_Indent_Role_Mapping_Details @EmpId='".$_SESSION['EmpID']."',@Type='Sales_Planning',@Statement_Type='Approver_Emp_Id'");

$Requestor_Menu_For_Sto_Indent=Get_Menu_Access_Details("EXEC Sales_Indent_STO_Role_Mapping_Details @EmpId='".$_SESSION['EmpID']."',@Type='STO_Indent',@Statement_Type='Requester_Emp_Id'");
$Approver_For_Sto_Indent=Get_Menu_Access_Details("EXEC Sales_Indent_STO_Role_Mapping_Details @EmpId='".$_SESSION['EmpID']."',@Type='STO_Indent',@Statement_Type='Recommender_Emp_Id'");
$Approver_Menu_Limit_Exceed_For_Sto_Indent=Get_Menu_Access_Details("EXEC Sales_Indent_STO_Role_Mapping_Details @EmpId='".$_SESSION['EmpID']."',@Type='STO_Indent',@Statement_Type='Approver_Emp_Id'");

$Validate_For_Sto_Indent=Get_Menu_Access_Details("EXEC Sales_Indent_STO_Role_Mapping_Details @EmpId='".$_SESSION['EmpID']."',@Type='STO_Indent',@Statement_Type='Recommender_Level_2_Emp_Id'");



//notification count functionality for indent 
$dashboard_det     ="EXEC Sales_Indent_Widget_Count_Details @Emp_Id='".$_SESSION['EmpID']."',@Dcode='".$_SESSION['Dcode']."'";
$salesadv          = sqlsrv_query($conn,$dashboard_det);
$fetch_wait        = sqlsrv_fetch_array($salesadv);

$indent_Approve_wil_qry = "EXEC Sales_Indent_Report_Details_WITh_CROP_Limit @Length='2000',@Offset='0', @Emp_Id='".$_SESSION['EmpID']."',@Product_Division='',@Dcode='".$_SESSION['Dcode']."',@Supply_Type='',@Zone_id='',@Region_Id='',@Terrirory_Id='',@Status='1',@QuotationType='',@Customer='',@SaleOrderType=''";

$indent_Approve_wil_qry_run   = sqlsrv_query($conn,$indent_Approve_wil_qry);
$indent_approval_wil_count = 0;
while($row   = sqlsrv_fetch_array($indent_Approve_wil_qry_run)) {
  if($row['Recommender_Emp_Id'] == 1) {
    $indent_approval_wil_count++;
  }
}
$indent_Approve_Count_wilimit       = $indent_approval_wil_count;



$indent_Recommedation_lie_qry = "EXEC Sales_Indent_Report_Details_With_Crop @Length='2000',@Offset='0', @Emp_Id='".$_SESSION['EmpID']."',@Product_Division='',@Dcode='".$_SESSION['Dcode']."',@Supply_Type='',@Zone_id='',@Region_Id='',@Terrirory_Id='',@Status='2',@QuotationType='',@Customer='',@SaleOrderType=''";

$indent_Recommedation_lie_qry_run   = sqlsrv_query($conn,$indent_Recommedation_lie_qry);
$indent_Recommedation_lie_count = 0;
while($row   = sqlsrv_fetch_array($indent_Recommedation_lie_qry_run)) {
  if($row['Recommender_Emp_Id'] == 1) {
    $indent_Recommedation_lie_count++;
  }
}
$indent_Recommedation_Count_lexceed       = $indent_Recommedation_lie_count;


$indent_Validation_lie_qry = "EXEC Sales_Indent_Report_Details_With_Crop @Length='2000',@Offset='0', @Emp_Id='".$_SESSION['EmpID']."',@Product_Division='',@Dcode='".$_SESSION['Dcode']."',@Supply_Type='',@Zone_id='',@Region_Id='',@Terrirory_Id='',@Status='3',@QuotationType='',@Customer='',@SaleOrderType=''";

$indent_Validation_lie_qry_run   = sqlsrv_query($conn,$indent_Validation_lie_qry);
$indent_Validation_lie_count = 0;
while($row   = sqlsrv_fetch_array($indent_Validation_lie_qry_run)) {
  if($row['Recommender_Level_2_Emp_Id'] == 1) {
    $indent_Validation_lie_count++;
  }
}
$indent_Validation_Limit_Exceed_Count       = $indent_Validation_lie_count;

$indent_Approve_lie_qry = "EXEC Sales_Indent_Report_Details_With_Crop @Length='2000',@Offset='0', @Emp_Id='".$_SESSION['EmpID']."',@Product_Division='',@Dcode='".$_SESSION['Dcode']."',@Supply_Type='',@Zone_id='',@Region_Id='',@Terrirory_Id='',@Status='4',@QuotationType='',@Customer='',@SaleOrderType=''";

$indent_Approve_lie_qry_run   = sqlsrv_query($conn,$indent_Approve_lie_qry);
$indent_Approve_lie_count = 0;
while($row   = sqlsrv_fetch_array($indent_Approve_lie_qry_run)) {
  if($row['Approver_Emp_Id'] == 1) {
    $indent_Approve_lie_count++;
  }
}
$indent_Approve_Count_Limit_Exceed_Count       = $indent_Approve_lie_count;

$Validation_With_In_Limit_Count=@$fetch_wait['Validation_With_In_Limit_Count'];




$direct_approval_count_qry = "SELECT (CASE WHEN Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' THEN 1  ELSE  0 END) AS Recommender_Emp_Id,
  (CASE WHEN Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."' THEN 1  ELSE  0 END) AS Recommender_Level_2_Emp_Id
  ,(CASE WHEN Mapping.Approver_Emp_Id='".$_SESSION['EmpID']."' THEN 1  ELSE  0 END) AS Approver_Emp_Id

  FROM Sales_Indent AS Trn_Tbl
  INNER JOIN (SELECT DISTINCT EMPLID,EMPLNAME,SAPTMID,TMNAME,DATAAREAID FROM RASI_TMTABLE) AS Tm_Tbl ON Tm_Tbl.DATAAREAID=Trn_Tbl.ProductDivision AND Tm_Tbl.SAPTMID=Trn_Tbl.TerritoryId
  INNER JOIN (SELECT DISTINCT DATAAREAID,ZONENAME,SAPZONEID FROM RASI_ZONETABLE) AS Zone_Tbl ON Zone_Tbl.SAPZONEID=Trn_Tbl.ZoneId AND Zone_Tbl.DATAAREAID=Trn_Tbl.ProductDivision
  INNER JOIN (SELECT DISTINCT DATAAREAID,REGIONNAME,SAPREGIONID FROM RASI_REGIONTABLE) AS Region_Tbl ON Region_Tbl.SAPREGIONID=Trn_Tbl.RegionId AND Region_Tbl.DATAAREAID=Trn_Tbl.ProductDivision
  --INNER JOIN (SELECT DISTINCT CustomerName,SAPCode FROM Master_Customer) AS Customer_Tbl ON Customer_Tbl.SAPCode=Trn_Tbl.CustomerCode
  INNER JOIN (SELECT DISTINCT Type,Descrpition FROM Master_SalesIndent_QuotationType) AS Quotation_Tbl ON Quotation_Tbl.Type=Trn_Tbl.QuotationType
  INNER JOIN (SELECT DISTINCT Type,Descrpition FROM Master_SalesIndent_SaleOrderType) AS Sales_Order_Tbl ON Sales_Order_Tbl.Type=Trn_Tbl.SaleOrderType
  --LEFT JOIN RASI_Role_Mapping AS Mapping ON Mapping.Requester_Emp_Id=Trn_Tbl.RequestBy AND Mapping.Product_Division=Trn_Tbl.ProductDivision
  INNER JOIN Sales_Indent_Material_Details AS Material_Tbl ON Material_Tbl.SalesIndentId=Trn_Tbl.SalesIndentId
  LEFT JOIN SD_CUS_BAL ON SD_CUS_BAL.CUSTOMER=Trn_Tbl.CustomerCode
  LEFT JOIN SD_CUS_MASTER ON SD_CUS_MASTER.Customer=Trn_Tbl.CustomerCode
  INNER JOIN (SELECT DISTINCT CropCode,CropName,Division FROM Master_Stock_Prod_2_Crop) AS Crop_Tbl ON Crop_Tbl.CropCode=Trn_Tbl.CropId

  LEFT JOIN EMPLTABLE as EMPLTABLE on EMPLTABLE.EMPLID=Trn_Tbl.RequestBy
  LEFT JOIN DIRPARTYTABLE as DIRPARTYTABLE on DIRPARTYTABLE.RECID=EMPLTABLE.RECID


  LEFT JOIN (select distinct Product_Division,Supply_Type,Plant_Code,Plant_Name,Region from Master_Plant) as Direct_Supply_Plant_Tbl on Direct_Supply_Plant_Tbl.Plant_Code=Trn_Tbl.PlantId AND Direct_Supply_Plant_Tbl.Region=Trn_Tbl.RegionId and Direct_Supply_Plant_Tbl.Supply_Type=(CASE WHEN Trn_Tbl.SupplyType='1' THEN 'Direct Supply'  ELSE 'C&F supply' END) and Direct_Supply_Plant_Tbl.Product_Division=(CASE WHEN Trn_Tbl.ProductDivision='ras' THEN 'CT01' WHEN Trn_Tbl.ProductDivision='fcm' THEN 'FC01'  WHEN Trn_Tbl.ProductDivision='frg' THEN 'FR01' ELSE ' ' END) AND Direct_Supply_Plant_Tbl.Region=(CASE WHEN Trn_Tbl.SupplyType='1' THEN Direct_Supply_Plant_Tbl.Region  ELSE Trn_Tbl.RegionId END)
  left join Sales_Indnet_User_Plant_Master as CNF_Plant_Table on CNF_Plant_Table.Plant_Code=Trn_Tbl.PlantId and CNF_Plant_Table.Emp_Code=Trn_Tbl.RequestBy
  LEFT JOIN (SELECT DISTINCT KUNNR,VKORG,VTWEG FROM SALES_AREA) AS Distribution_Tbl On Distribution_Tbl.KUNNR=Trn_Tbl.CustomerCode and Distribution_Tbl.VKORG=(CASE WHEN Trn_Tbl.ProductDivision='ras' THEN 'CT01' WHEN Trn_Tbl.ProductDivision='fcm' THEN 'FC01'  WHEN Trn_Tbl.ProductDivision='frg' THEN 'FR01' ELSE ' ' END)


  LEFT JOIN RASI_Role_Mapping_With_Crop Mapping ON Mapping.Requester_Emp_Id=Trn_Tbl.RequestBy AND Mapping.Sub_Type =(CASE WHEN Trn_Tbl.SupplyType='1' THEN 'Direct_Supply'  ELSE 'C&F_Supply' END) AND Mapping.Product_Division=Trn_Tbl.ProductDivision  AND Mapping.Crop=Trn_Tbl.CropId WHERE 1=1 and Material_Tbl.CurrentStatus !='0' AND Mapping.Type='Sales_Indent'  

  AND (Mapping.Requester_Emp_Id='".$_SESSION['EmpID']."' OR Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' OR Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."' OR Mapping.Approver_Emp_Id='".$_SESSION['EmpID']."')


  AND Trn_Tbl.SupplyType = 1 AND Material_Tbl.CurrentStatus = 6 AND Material_Tbl.RejectionStatus=1";
  $dr_approval          = sqlsrv_query($conn,$direct_approval_count_qry);
  $indent_approve_direct_withinlimit_count = 0;
  while($fetch_direct = sqlsrv_fetch_array($dr_approval)) {
    if($fetch_direct['Recommender_Level_2_Emp_Id'] == 1) {
      $indent_approve_direct_withinlimit_count++; 
    }
  }
  $indent_approve_direct_withinlimit = $indent_approve_direct_withinlimit_count;

//notification count functionality for indent end  





//notification count functionality for STO indent   

$sto_validate_wil_qry = "EXEC Sales_Indent_STO_Details_WIth_Limit_STO @Emp_Id='".$_SESSION['EmpID']."',@Product_Division='',@Dcode='".$_SESSION['Dcode']."',@plant_id='',@Zone_id='',@Region_Id='',@Terrirory_Id='',@Status='1',@Length='2000',@Offset='0'";

$sto_validate_wil_qry_run   = sqlsrv_query($conn,$sto_validate_wil_qry);
$sto_validate_wil_count = 0;
while($row   = sqlsrv_fetch_array($sto_validate_wil_qry_run)) {
  if($row['Validate_Permission_With_In_Limit'] == 1) {
    $sto_validate_wil_count++;
  }
}
$Sto_validate_Count_With_In_Limit       = $sto_validate_wil_count;


$sto_approve_wil_qry = "EXEC Sales_Indent_STO_Details_WIth_Limit_STO @Emp_Id='".$_SESSION['EmpID']."',@Product_Division='',@Dcode='".$_SESSION['Dcode']."',@plant_id='',@Zone_id='',@Region_Id='',@Terrirory_Id='',@Status='5',@Length='2000',@Offset='0'";

$sto_approve_wil_qry_run   = sqlsrv_query($conn,$sto_approve_wil_qry);
$sto_approve_wil_count = 0;
while($row   = sqlsrv_fetch_array($sto_approve_wil_qry_run)) {
  if($row['Approve_Permission_With_In_Limit'] == 1) {
    $sto_approve_wil_count++;
  }
}
$Sto_Approve_Count_With_In_Limit       = $sto_approve_wil_count;


$sto_recommendation_lie_qry = "EXEC Sales_Indent_STO_Details_WIth_Limit_STO @Emp_Id='".$_SESSION['EmpID']."',@Product_Division='',@Dcode='".$_SESSION['Dcode']."',@plant_id='',@Zone_id='',@Region_Id='',@Terrirory_Id='',@Status='2',@Length='2000',@Offset='0'";

$sto_recommendation_lie_qry_run   = sqlsrv_query($conn,$sto_recommendation_lie_qry);
$sto_recommendation_lie_count = 0;
while($row   = sqlsrv_fetch_array($sto_recommendation_lie_qry_run)) {
  if($row['Recommend_Permission_Limit_Exceed'] == 1) {
    $sto_recommendation_lie_count++;
  }
}
$Sto_Recommendation_Count_Limit_Exceed       = $sto_recommendation_lie_count;


$sto_validate_lie_count = 0;
$sto_validate_lie = sto_notification_qry(3);
foreach ($sto_validate_lie as $key => $value) {
  if($value['Validate_Permission_Limit_Exceed'] == 1) {
    $sto_validate_lie_count++;
  }
}

$sto_validate_Count_Limit_Exceed       = $sto_validate_lie_count;


$sto_approve_lie_qry = "EXEC Sales_Indent_STO_Details_WIth_Limit_STO_Final_Level @Emp_Id='".$_SESSION['EmpID']."',@Product_Division='',@Dcode='".$_SESSION['Dcode']."',@plant_id='',@Zone_id='',@Region_Id='',@Terrirory_Id='',@Status='7',@length='2000',@offset='0'";

$sto_approve_lie_qry_qry_run   = sqlsrv_query($conn,$sto_approve_lie_qry);
$sto_approve_lie_qry_count = 0;
while($row   = sqlsrv_fetch_array($sto_approve_lie_qry_qry_run)) {
  if($row['Approve_Permission_Limit_Exceed'] == 1) {
    $sto_approve_lie_qry_count++;
  }
}
$sto_Approve_Count_Limit_Exceed       = $sto_approve_lie_qry_count;


//notification count functionality for STO indent end  




//requester indent widgets status level functionality start

$requester_withinlimit_approval_pending_sql ="EXEC Sales_Indent_Report_Details @Length='All',@Offset='', @Emp_Id='".$_SESSION['EmpID']."',@Product_Division='',@Dcode='".$_SESSION['Dcode']."',@Supply_Type='',@Zone_id='',@Region_Id='',@Terrirory_Id='',@Status='1',@QuotationType='',@Customer='',@SaleOrderType=''";
$requester_withinlimit_approval_pending_count= Get_user_indent_count($requester_withinlimit_approval_pending_sql);


$requester_limitexceed_recommend_pending_sql ="EXEC Sales_Indent_Report_Details @Length='All',@Offset='', @Emp_Id='".$_SESSION['EmpID']."',@Product_Division='',@Dcode='".$_SESSION['Dcode']."',@Supply_Type='',@Zone_id='',@Region_Id='',@Terrirory_Id='',@Status='2',@QuotationType='',@Customer='',@SaleOrderType=''";
$requester_limitexceed_recommend_pending_count= Get_user_indent_count($requester_limitexceed_recommend_pending_sql);


$requester_limitexceed_validate_pending_sql ="EXEC Sales_Indent_Report_Details @Length='All',@Offset='', @Emp_Id='".$_SESSION['EmpID']."',@Product_Division='',@Dcode='".$_SESSION['Dcode']."',@Supply_Type='',@Zone_id='',@Region_Id='',@Terrirory_Id='',@Status='3',@QuotationType='',@Customer='',@SaleOrderType=''";
$requester_limitexceed_validate_pending_count= Get_user_indent_count($requester_limitexceed_validate_pending_sql);


$requester_limitexceed_approval_pending_sql ="EXEC Sales_Indent_Report_Details @Length='All',@Offset='', @Emp_Id='".$_SESSION['EmpID']."',@Product_Division='',@Dcode='".$_SESSION['Dcode']."',@Supply_Type='',@Zone_id='',@Region_Id='',@Terrirory_Id='',@Status='4',@QuotationType='',@Customer='',@SaleOrderType=''";
$requester_limitexceed_approval_pending_count= Get_user_indent_count($requester_limitexceed_approval_pending_sql);

$requester_withinlimit_approval_direct_pending_sql ="EXEC Sales_Indent_Report_Details @Length='All',@Offset='', @Emp_Id='".$_SESSION['EmpID']."',@Product_Division='',@Dcode='".$_SESSION['Dcode']."',@Supply_Type='',@Zone_id='',@Region_Id='',@Terrirory_Id='',@Status='6',@QuotationType='',@Customer='',@SaleOrderType=''";
$requester_withinlimit_approval_direct_pending_count = Get_user_indent_count($requester_withinlimit_approval_direct_pending_sql);



$dashboard_controller = new Dashboard_details($conn);
$is_recommender = $dashboard_controller->recommender_role_check($_SESSION['EmpID'])[0]['recommender_count'];      
$role = ($is_recommender > 0) ? "Recommender" : "Requester";

$despatched_indents_sql  = "SELECT COUNT(*) as TOTALROW from Logistics_TRN_Sales_Quotation_Creation as despatch_tbl
inner join Sales_Indent ON Sales_Indent.ReqId =  despatch_tbl.INDENT_NO
inner join Sales_Indent_Material_Details as material_tbl ON material_tbl.SalesIndentId =  Sales_Indent.SalesIndentId";

if($role == "Requester") {
  $despatched_indents_sql  .= " where EMPLOYEE_ID = '".$_SESSION['EmpID']."'";
} elseif($role == "Recommender") {
  $despatched_indents_sql  .= " where EMPLOYEE_ID IN (
      SELECT DISTINCT Requester_Emp_Id from RASI_Role_Mapping_With_Crop where Recommender_Emp_Id = '".$_SESSION['EmpID']."' or Recommender_Level_2_Emp_Id = '".$_SESSION['EmpID']."' or Approver_Emp_Id = '".$_SESSION['EmpID']."'
      and type = 'Sales_Indent'
       )";
}

$despatched_indents_count = Get_user_indent_count($despatched_indents_sql);

//requester indent widgets status level functionality end


$completed_query =  "SELECT COUNT(*) as TOTALROW from Sales_Indent 
INNER JOIN Sales_Indent_Material_Details ON Sales_Indent_Material_Details.SalesIndentId=Sales_Indent.SalesIndentId
LEFT JOIN RASI_Role_Mapping_With_Crop AS Mapping ON Mapping.Requester_Emp_Id=Sales_Indent.RequestBy 
INNER JOIN (select distinct Season_Code,Default_Season from Master_Season) as Master_Season
ON Sales_Indent_Material_Details.season = Master_Season.Season_Code and Master_Season.Default_Season = 1
AND Mapping.Product_Division=Sales_Indent.ProductDivision AND Mapping.Sub_Type =
(CASE WHEN Sales_Indent.SupplyType='1' THEN 'Direct_Supply'  ELSE 'C&F_Supply' END) 
AND Mapping.Crop=Sales_Indent.CropId AND Mapping.Type='Sales_Indent'
left join Sales_Indnet_User_Plant_Master as CNF_Plant_Table on CNF_Plant_Table.Plant_Code=Sales_Indent.PlantId and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy
left join Logistics_TRN_Sales_Quotation_Creation as despatch_tbl on Sales_Indent.ReqId =  despatch_tbl.INDENT_NO and CNF_Plant_Table.Emp_Code=Sales_Indent.RequestBy

WHERE 1=1 and Sales_Indent_Material_Details.CurrentStatus = '5' AND Sales_Indent_Material_Details.RejectionStatus = '1' AND (Mapping.Requester_Emp_Id='".$_SESSION['EmpID']."' OR Mapping.Recommender_Emp_Id='".$_SESSION['EmpID']."' OR Mapping.Recommender_Level_2_Emp_Id='".$_SESSION['EmpID']."' OR Mapping.Approver_Emp_Id='".$_SESSION['EmpID']."') AND despatch_tbl.INDENT_NO IS NULL";




$completed_indent_count = Get_user_indent_count($completed_query);



?>