function Alert_Msg(Msg,Type){
  Swal.fire({
  title: Msg,
  icon: Type,
});
}

function post_data_to_sap(datas)
{
	console.log(datas);
  var result=[];
  var status=0;
  var SAP_Url="<?php echo $SAP_Url?>";
  var proxyurl = "https://cors-anywhere.herokuapp.com/";
    var url = "http://182.18.162.108:8081/Sales_Indent/"+SAP_Url+"/ZIN_RFC_ERAIN_UPDATE_QUOT_STO_DAS.php";
   $.ajax ({
        method: "POST",
       
		type:'json',
		 data:datas,
        url:proxyurl + url, 
        async: false,
        success: function(data)
        {
          
          result=data;
          if(data.trim() !=""){
            status=1;
          }

        }
    });
   return status;
}
  $(document).on("click",".submit_btn",function(){
    $(".submit_btn").attr("disabled",true);
     $(".Reject_btn").attr("disabled",true);
	  var sap_data={};
	  var postData = $(".sales_indent_validate").serialize()+'&Statement_Type=Approved&Limit_Exceed=1';
	  var user_input={};
	 user_input.plant_id=$(".plant_id").val();
	  $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
       data:postData,
       async:false,
      success: function(data){
         result=JSON.parse(data);
		
var sap_data={};
sap_data.JSON=result.data;

		 // post_data_to_sap(sap_data);
         if(result.Status == 1){
			Alert_Msg("Approved Successfully.","success");
			Server_Side_Datatable("yes",user_input);
			return false;
		 }else{
			   Alert_Msg("Something Went Wrong.","error");
		 }
        
        }
      });
	 
  })
  

  $(document).on("click",".Reject_btn",function(){
    $(".submit_btn").attr("disabled",true);
     $(".Reject_btn").attr("disabled",true);
     var postData = $(".sales_indent_validate").serialize()+'&Statement_Type=Reject';
    var user_input={};
     user_input.plant_id=$(".plant_id").val();
   //user_input.customer_id=$(".customer_id").val();
   //user_input.supply_type=$(".supply_type").val();
    $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
       data:postData,
       async:false,
      success: function(data){
         result=JSON.parse(data);
        
         if(result.Status == 1){
      Alert_Msg("Rejected Successfully.","success");
      Server_Side_Datatable("yes",user_input);
      return false;
     }else{
         Alert_Msg("Something Went Wrong.","error");
     }
        
        }
      });
   
  })


  $(document).on("click",".validate,.checkAll",function(){
	  var checked_count=0;
	 $(".submit_btn").attr("disabled",false);
	  $(".validate:checked").each(function(){
		  checked_count++;
	  });
	  
	  if(checked_count >0){
		   $(".submit_btn").attr("disabled",false);
	  }else{
		  $(".submit_btn").attr("disabled",true);
	  }
	  
  })

  $(document).on("click",".validate,.checkAll",function(){
    var checked_count=0;
   $(".Reject_btn").attr("disabled",false);
    $(".validate:checked").each(function(){
      checked_count++;
    });
    
    if(checked_count >0){
       $(".Reject_btn").attr("disabled",false);
    }else{
      $(".Reject_btn").attr("disabled",true);
    }
    
  })


  
  $(".checkAll").click(function () {
	  if($(this).prop('checked')){
		 $(".All_Validate").prop( "checked", true );
	  }else{
		 $(".All_Validate").prop( "checked", false );
	  }
 });
 
  $(document).on("keyup",".QtyInBag",function(){
	  var MaterialQtyInPkt=$(this).closest("tr").find(".MaterialQtyInPkt").val();
	  var MaterialQtyInKg=$(this).closest("tr").find(".MaterialQtyInKg").val();
			var bag = parseInt($(this).val());
            var QtyInPkt = bag * MaterialQtyInPkt;
            if(isNaN(QtyInPkt))
            {
              QtyInPkt=0;
            }
			var QtyInKg = QtyInPkt * MaterialQtyInKg;
            $(this).closest("tr").find(".QtyInPkt").val(QtyInPkt);
            $(this).closest("tr").find(".QtyInKg").val(QtyInKg);
  });
  
  
  
  
$(document).on("submit",".adv_submit",function(){
	var user_input={};
  
	 /*user_input.product_division=$(".product_division").val();
	 user_input.crop_id=$(".crop_id").val();
	 user_input.quot_type=$(".quot_type").val();
	 user_input.sale_type=$(".sale_type").val();
	 user_input.zone_id=$(".zone_id").val();
	 user_input.region_id=$(".region_id").val();
	 user_input.teritory_id=$(".territory_id").val();
	 user_input.customer_id=$(".customer_id").val();*/
   user_input.plant_id=$(".plant_id").val();
   if(user_input.plant_id !='' && user_input.plant_id !=0){
    Server_Side_Datatable("yes",user_input);
   }else{
    Alert_Msg("Please Select the plant.","error");
   }
   
	
	 
	 console.log(user_input);

return false;
})

  $(document).ready(function(){
     $('.js-example-basic-single').select2(); 
	  //var From_Date = '';
	 //var  to_Date = '';
	 //var Division = '';
	 //var region_code = '';
	 //var teritory_code = '';
	 //var productdivision ='';
	 //var status = $('#filter_status').val();
	 var user_input={};
	/* user_input.product_division=$(".product_division").val();
	 user_input.crop_id=$(".crop_id").val();
	 user_input.quot_type=$(".quot_type").val();
	 user_input.sale_type=$(".sale_type").val();
	 user_input.zone_id=$(".zone_id").val();
	 user_input.region_id=$(".region_id").val();
	 user_input.teritory_id=$(".territory_id").val();
	 user_input.customer_id=$(".customer_id").val();*/
	 user_input.plant_id=$(".plant_id").val();
   
	$('#Sales_Indent').DataTable({ "dom": 'rtp',
  
  //"columnDefs": [{ "className":"y desine", "targets": [1,2,3,4,5] }],
    "scrollX": true,
    "ordering":false,
    "buttons": ['copy', 'csv', 'excel', 'pdf', 'print'],});
	 //Server_Side_Datatable("no",user_input);
    Server_Side_Datatable("yes",user_input);

   });
   

   
    $(document).ready(function(){
     $('.js-example-basic-single').select2();
     var product_division = $(".product_division").val();
     var supply_type = $(".supply_type").val();  

    role_based_filter(product_division,"Get_Zone_Details",0,0,0,0,0);

    get_plant_details();
    var zone_id = $(".zone_id").val();
     var region_id = $(".region_id").val();
     var territory_id = $(".territory_id").val();
     var crop_code = $(".crop_id").val();
    // get_customer_details(product_division,zone_id,region_id,territory_id);
     get_material_details(product_division,crop_code,zone_id,0);


     var urlParams = new URLSearchParams(window.location.search);
     var filter_val = atob(urlParams.get('filter'));
     var urlParams_size = urlParams.size;

     var user_input={};
     if(urlParams_size > 0) {
      user_input.plant_id=filter_val;
      $(".plant_id").val(filter_val).select2().trigger('change');
    } else {
      user_input.plant_id=$(".plant_id").val();
      $(".plant_id").val(user_input.plant_id).select2().trigger('change');

    }

    Server_Side_Datatable("yes",user_input);
  });
  

  
  
   $(document).on("change",".zone_id,.region_id,.territory_id",function(){
    var product_division = $(".product_division").val();
   var zone_id = $(".zone_id").val();
     var region_id = $(".region_id").val();
     var territory_id = $(".territory_id").val();
     get_customer_details(product_division,zone_id,region_id,territory_id);
   
  });

$(document).ready(function(){
  // $('.plant_name').select2();
  // $('.plant_id').change(function(){
  //   var value = $(this).val();
  //   $('.plant_name').val(value);
  //   $('.plant_name').select2().trigger('change');
  //   // var user_input = {};
  //   // user_input.plant_id=value;
  //   // Server_Side_Datatable("yes",user_input);
  // });
});

function get_material_details(product_division,crop_code,zone_id,status){
  var output="";
     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
       data:{"Action":"Get_Material_Details","product_division":product_division,"crop_code":crop_code},
       async:false,
      success: function(data){
         result=JSON.parse(data);
         output=result.data;
        
        }
      });
      if(status == 0){
        $('.material_id').html(output);
        
        }else{
      return output;
        }



}

function get_plant_details(){
     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_STO_Plant_Details","Type":"Direct"},
       async:false,
      success: function(data){
         result=JSON.parse(data);
         $('.plant_id').html(result.data);
        $('.plant_name').html(result.data);
        
        }
      })
  }
  
   function get_customer_details(product_division,zone_code,region_code,territory_code){
    if(product_division !='' && product_division !='0' && zone_code !='' && zone_code !='0'&& region_code !='' && region_code !='0' && territory_code !='' && territory_code !='0'){
     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Customer_Details","product_division":product_division,"zone_code":zone_code,"region_code":region_code,"territory_code":territory_code},
       async:false,
      success: function(data){
        
        var result=JSON.parse(data);
        $('.customer_id').html(result.data);

        }
      });
    }
  }
  
  function role_based_filter(product_division,action_type,zone_code,region_code,territory_code,crop_code,variety_code)
  {
      $.ajax 
      ({
      type: "POST",
      url: "Role_Based_Location_Dimensions_for_Sales_Mis.php",
      data:{"product_division":product_division,"action_type":action_type,"zone_code":zone_code,"region_code":region_code,"territory_code":territory_code,"crop_code":crop_code,"variety_code":variety_code},
       async:false,
      success: function(data){
        var zone_dets="0";
        var region_dets="0";
        var territory_dets="0";
        var crop_dets="0";
        var variety_dets="0";
        var result=JSON.parse(data);
        if(action_type == "Get_Zone_Details"){
          zone_dets="1";
          region_dets="1";
          territory_dets="1"; 
          crop_dets="1"; 
          variety_dets="1"; 
        }else if(action_type == "Get_Region_Details"){
          zone_dets="0";
          region_dets="1";
          territory_dets="1";
        }else if(action_type == "Get_Territory_Details"){
          zone_dets="0";
          region_dets="0";
          territory_dets="1";
        }else if(action_type == "Get_varirty_Details"){
         variety_dets="1"; 
        }
        
        
        if(crop_dets == "1"){
          $(".crop_id").html(result.crop_details);
        }

        if(variety_dets == "1"){
          $(".hybrid_id").html(result.variety_details);
        }

         if(zone_dets == "1"){
          $(".zone_id").html(result.zone_details);
        }

        if(region_dets == "1"){
          $(".region_id").html(result.region_details);
        }
        if(territory_dets == "1"){
          $(".territory_id").html(result.territory_details);
        }

        }
      });    
  }

  $(document).on("change",".product_division",function(){
    var zone_id=0;
    var region_id=0;
    var territory_id=0;
    var crop_code=0;
    var variety_code=0;
    var product_division = $(".product_division").val();
    role_based_filter(product_division,"Get_Zone_Details",zone_id,region_id,territory_id,crop_code,variety_code);
  });
  $(document).on("change",".zone_id",function(){
    var zone_id=$(this).val();
    var region_id=0;
    var territory_id=0;
    var crop_code=0;
    var variety_code=0;
    var product_division = $(".product_division").val();
    role_based_filter(product_division,"Get_Region_Details",zone_id,region_id,territory_id,crop_code,variety_code);
  });

  $(document).on("change",".crop_id",function(){
    var zone_id=0;
    var region_id=0;
    var territory_id=0;
    var crop_code=$(this).val();
    var variety_code=0;
    var product_division = $(".product_division").val();
    role_based_filter(product_division,"Get_varirty_Details",zone_id,region_id,territory_id,crop_code,variety_code);
  });

  $(document).on("change",".region_id",function(){
    var zone_id=$(".zone_id").val();
    var region_id=$(this).val();
    var territory_id=0;
    var crop_code=0;
    var variety_code=0;
    var product_division = $(".product_division").val();
    role_based_filter(product_division,"Get_Territory_Details",zone_id,region_id,territory_id,crop_code,variety_code);
  });
   $(document).on("change",".product_division,.crop_id,.zone_id",function(){
    var product_division = $(".product_division").val();
   var zone_id = $(".zone_id").val();
     var crop_code = $(".crop_id").val();
     
   
     get_material_details(product_division,crop_code,zone_id,0);
   
  });
  
  
   
   function Server_Side_Datatable(destroy_status,user_input)
{
/*var Year=user_input.Year;
var Season_code=user_input.Season_code;
var Crop_code=user_input.Crop_code;
var Plant_Code=user_input.Plant_Code;
var StorageLocation_Code=user_input.StorageLocation_Code;*/


   var data_table='Sales_Indent'
   if(destroy_status == "yes")
  {
    //return false;
    $('#'+data_table).DataTable().destroy();
  }
 $('#' + data_table).DataTable({

    "dom": 'rtp',
columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 10
    }],
    select: {
        style: 'os',
        selector: 'td:first-child'
    },
    order: [
        [1, 'asc']
    ],
  
  
  //"columnDefs": [{ "className":"y desine", "targets": [1,2,3,4,5] }],
    "scrollX": true,
    "buttons": ['copy', 'csv', 'excel', 'pdf', 'print'],
    "bprocessing": true,
    "serverSide": true,
    "ordering":false,
    "pageLength": 8,
    "ajax": 
    {
      "url": "Sto_Approve_Limit_Exceed_Sales_Indent_Data_Table_Details.php", 
      "type": "POST",
      "data": {Plant_Id:user_input.plant_id}
    },
    fnDrawCallback: function (settings, json) {
        $('.ellipsis').addClass('paginate_button');
        if($('.paginate_button').hasClass('ellipsis')) {
          $('.paginate_button').removeClass('ellipsis');
        }
    }
  });
}