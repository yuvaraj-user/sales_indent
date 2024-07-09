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
  var proxyurl = "https://cors-anywhere.herokuapp.com/";

  var Sap_url="<?php echo $SAP_Url;?>";
  var url = "http://182.18.162.108:8081/Sales_Indent/"+ Sap_url +"/ZIN_RFC_ERAIN_UPDATE_QUOT_DAS.php";
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
    // var postData = $(".sales_indent_validate").serialize()+'&Statement_Type=Approved&supply_type_id='+$(".supply_type").val()+'&Limit_Exceed=0';
    var postData = $(".sales_indent_validate").serialize()+'&Statement_Type=Approved&Limit_Exceed=0';
    console.log(postData);
    var user_input={};
    user_input.supply_type=$(".supply_type").val();
    $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
       data:postData,
       async:false,
      success: function(data){
         try {
     result=JSON.parse(data);
    if(result.Status == 1){
      Alert_Msg("Approved Successfully.","success");
      Server_Side_Datatable("yes",user_input);
      return false;
     }else{
         Alert_Msg("Something Went Wrong.","error");
     }
   
  } catch (e) {
    Server_Side_Datatable("yes",user_input);
    return false;
  }


     
        
        }
      });
   
  })

  $(document).on("click",".Reject_btn",function(){
    $(".submit_btn").attr("disabled",true);
    $(".Reject_btn").attr("disabled",true);
    var postData = $(".sales_indent_validate").serialize()+'&Statement_Type=Reject';
    var user_input={};
    user_input.supply_type=$(".supply_type").val();
    user_input.zone_id=$(".zone_id").val();
    user_input.region_id=$(".region_id").val();
    user_input.teritory_id=$(".territory_id").val();
   //user_input.customer_id=$(".customer_id").val();
   //user_input.supply_type=$(".supply_type").val();
    $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
       data:postData,
       async:false,
      success: function(data){
        try {
     result=JSON.parse(data);
    if(result.Status == 1){
       Alert_Msg("Rejected Successfully.","success");
      // Server_Side_Datatable("yes",user_input);
      location.reload();
      return false;
     }else{
         Alert_Msg("Something Went Wrong.","error");
     }
   
  } catch (e) {
    Server_Side_Datatable("yes",user_input);
    return false;
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
    var Price=$(this).closest("tr").find(".Price").val();
      var bag = parseInt($(this).val());
            var QtyInPkt = bag * MaterialQtyInPkt;
            if(isNaN(QtyInPkt))
            {
              QtyInPkt=0;
            }

          
      var QtyInKg = QtyInPkt * MaterialQtyInKg;
      var Total_Price=parseFloat(QtyInPkt)*parseFloat(Price);
       var Discount=$(this).closest('tr').find(".Discount").val();
              Discount=parseInt(Discount);
             var discount_amount=0;
    if(Discount !='' && Discount >0)
    {
      if(isNaN(Total_Price))
      {
        Total_Price=0;
      }
      discount_amount=Total_Price*(Discount/100);
    }
    var Grand_Total_Price=parseFloat(Total_Price)-parseFloat(discount_amount);
            $(this).closest("tr").find(".QtyInPkt").val(QtyInPkt);
            $(this).closest("tr").find(".QtyInKg").val(QtyInKg);
            $(this).closest("tr").find(".Total_Price").val(Grand_Total_Price);
   /* var MaterialQtyInPkt=$(this).closest("tr").find(".MaterialQtyInPkt").val();
    var MaterialQtyInKg=$(this).closest("tr").find(".MaterialQtyInKg").val();
      var bag = parseInt($(this).val());
            var QtyInPkt = bag * MaterialQtyInPkt;
            if(isNaN(QtyInPkt))
            {
              QtyInPkt=0;
            }
      var QtyInKg = QtyInPkt * MaterialQtyInKg;
            $(this).closest("tr").find(".QtyInPkt").val(QtyInPkt);
            $(this).closest("tr").find(".QtyInKg").val(QtyInKg); */
  });
  
  
  
  
$(document).on("submit",".adv_submit",function(){
  var user_input={};
  //user_input.product_division=$(".product_division").val();
  // user_input.crop_id=$(".crop_id").val();
   //user_input.quot_type=$(".quot_type").val();
   //user_input.sale_type=$(".sale_type").val();
   user_input.zone_id=$(".zone_id").val();
   user_input.region_id=$(".region_id").val();
   user_input.teritory_id=$(".territory_id").val();
  // user_input.customer_id=$(".customer_id").val();
   user_input.supply_type=$(".supply_type").val();
   user_input.product_division=$(".product_division").val();
   
Server_Side_Datatable("yes",user_input);
  return false;
})

  $(document).ready(function(){
    $('.js-example-basic-single').select2(); 
    var From_Date = '';
   var  to_Date = '';
   //var Division = '';
   //var region_code = '';
   //var teritory_code = '';
   //var productdivision ='';
   //var status = $('#filter_status').val();
   var user_input={};
   //user_input.product_division=$(".product_division").val();
  // user_input.crop_id=$(".crop_id").val();
   //user_input.quot_type=$(".quot_type").val();
  // user_input.sale_type=$(".sale_type").val();
   user_input.zone_id=$(".zone_id").val();
   user_input.region_id=$(".region_id").val();
   user_input.teritory_id=$(".territory_id").val();
   //user_input.customer_id=$(".customer_id").val();
   user_input.supply_type=$(".supply_type").val();
   user_input.product_division=$(".product_division").val();
  $('#Sales_Indent').DataTable({ "dom": 'rtip',
  
  //"columnDefs": [{ "className":"y desine", "targets": [1,2,3,4,5] }],
    "scrollX": true,
    "buttons": ['copy', 'csv', 'excel', 'pdf', 'print'],});
   Server_Side_Datatable("yes",user_input);
   });
   

   
    $(document).ready(function(){
     $('.js-example-basic-single').select2();
     var product_division = $(".product_division").val();
     var supply_type = $(".supply_type").val();  

    // role_based_filter(product_division,"Get_Zone_Details",0,0,0,0,0);

    get_plant_details(supply_type,0);
    var zone_id = $(".zone_id").val();
     var region_id = $(".region_id").val();
     var territory_id = $(".territory_id").val();
     var crop_code = $(".crop_id").val();
    // get_customer_details(product_division,zone_id,region_id,territory_id);
          get_material_details(product_division,crop_code,zone_id,0);
  });
  $(document).on("change",".supply_type",function(){
   get_plant_details($(this).val(),0);
   
  });

  $(document).on("change",".supply_type",function(){

   get_plant_details($(this).val(),0);
   
  });
  
   $(document).on("change",".zone_id,.region_id,.territory_id",function(){
    var product_division = $(".product_division").val();
   var zone_id = $(".zone_id").val();
     var region_id = $(".region_id").val();
     var territory_id = $(".territory_id").val();
     get_customer_details(product_division,zone_id,region_id,territory_id);
   
  });

$(document).ready(function(){
  var urlParams = new URLSearchParams(window.location.search);
  var filter_val = urlParams.get('filter');
  $('.plant_name').select2();
  $('.plant_id').change(function(){
    var value = $(this).val();
    $('.plant_name').val(value);
    $('.plant_name').select2().trigger('change');
  });

    var user_input={};
  //user_input.product_division=$(".product_division").val();
  // user_input.crop_id=$(".crop_id").val();
   //user_input.quot_type=$(".quot_type").val();
   //user_input.sale_type=$(".sale_type").val();
   user_input.zone_id=$(".zone_id").val();
   user_input.region_id=$(".region_id").val();
   user_input.teritory_id=$(".territory_id").val();
  // user_input.customer_id=$(".customer_id").val();
   // user_input.supply_type=$(".supply_type").val();
  if(filter_val) {
     user_input.supply_type=filter_val;
     $(".supply_type").val(filter_val).select2();
      user_input.product_division='';
     $(".product_division").val('').select2();

   } else {
     user_input.supply_type=$(".supply_type").val();
    user_input.product_division=$(".product_division").val();
     $(".product_division").val($(".product_division").val()).select2();

   }
   
Server_Side_Datatable("yes",user_input);
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

function get_plant_details(supply_type,status){
var output="";
     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Plant_Details","supply_type":supply_type},
       async:false,
      success: function(data){
         result=JSON.parse(data);
         output=result.data;
        
        }
      });
      if(status == 0){
        $('.plant_id').html(output);
        $('.plant_name').html(output);
        }else{
      return output;
        }
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
   var data_table='Sales_Indent'
    jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
                 if ( this.context.length ) {
                     var jsonResult = $.ajax({

       url: 'Ajax.php',
                         type:'POST',
                         dataType:'json',
                          "data": {Action:'Approve_Details_For_With_In_Limit',Zone_id:user_input.zone_id,Region_Id:user_input.region_id,Terrirory_Id:user_input.teritory_id,Supply_Type:user_input.supply_type,ProductDivision:user_input.product_division,length:'All'},
                         async: false
                     });

                      let headers=['S.No','ReqId','RequestBy','Req Date','Region','Customer Id','Customer Name','Customer Balance','Customer Credit Limit','Material Code','Quantity In Bag','Quantity In Pkt','Quantity In Kg','Price',  'Total Price']
      
                     
                     return {
                       body: jsonResult.responseJSON.data, 
                       header: headers};
                 }
             } );
   if(destroy_status == "yes")
  {
    $('#'+data_table).DataTable().destroy();
  }
 $('#' + data_table).DataTable({

    "dom": 'rtip',

    columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 14
    }],
    select: {
        style: 'os',
        selector: 'td:first-child'
    },
    order: [
        [1, 'asc']
    ],
  
    "scrollX": true,
    "buttons": ['copy', 'csv', 'excel', 'pdf', 'print'],
    "bprocessing": true,
    "serverSide": true,
    "pageLength": 10,
    "ajax": 
    {
      "url": "Ajax.php", 
      "type": "POST",
      "data": {Action:'Approve_Details_For_With_In_Limit',Zone_id:user_input.zone_id,Region_Id:user_input.region_id,Terrirory_Id:user_input.teritory_id,Supply_Type:user_input.supply_type,ProductDivision:user_input.product_division}
    },
    fnDrawCallback: function (settings, json) {
        $('.ellipsis').addClass('paginate_button');
        if($('.paginate_button').hasClass('ellipsis')) {
          $('.paginate_button').removeClass('ellipsis');
        }
    }
  });
}