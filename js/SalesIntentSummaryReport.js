  $(document).ready(function(){
     $('.js-example-basic-single').select2(); 


    var product_division_id,user_zone_id,user_region_id,plant_code,hybrid_id,crop_id,indent_type_id=0;

    product_division_id=$(".product_division_id").val();
    role_based_filter(product_division_id,"Get_Zone_Details",0,0,0,0,0);
    
    user_zone_id=$(".zone_id").val();
    user_region_id=$(".region_id").val();
    crop_id=$(".crop_id").val();
    zone_wise_summary_details("yes","Get_Zone_Wise_Summary",product_division_id,user_zone_id,user_region_id,crop_id);
    crop_wise_summary_details("yes","Get_Crop_Wise_Summary",product_division_id,user_zone_id,user_region_id,crop_id);
    /* region_wise_summary_details("yes","Get_Region_Wise_Summary",product_division_id,user_zone_id,user_region_id,crop_id);
    territory_wise_summary_details("yes","Get_Territory_Wise_Summary",product_division_id,user_zone_id,user_region_id,crop_id);
    vareity_wise_summary_details("yes","Get_Variety_Wise_Summary",product_division_id,user_zone_id,user_region_id,crop_id); */
    $(".region_wise_summary_div,.vareity_wise_summary_div,territory_wise_summary_div").css("display","none");

});

$(document).ready(function(){

 var user_input={};
 user_input.crop_id=$(".crop_id").val();
 user_input.product_division_id=$(".product_division_id").val();
 user_input.region_id=$(".region_id").val();
 user_input.supply_type=$(".supply_type").val();
 $('#Sales_Indent').DataTable({ "dom": 'Bfrtip',

 //"columnDefs": [{ "className":"y desine", "targets": [1,2,3,4,5] }],
 "scrollX": true,
 "buttons": ['copy', 'csv', 'excel', 'pdf', 'print'],});

});

$(document).on("click",".crop_wise_summary tbody .summary",function()
{
   var crop_id=$(this).closest("tr").find(".table_crop_id").val();
   var status=$(this).closest('tr').find(".summary_status").val();
   var product_division_id=$(".product_division_id").val();
   var zone_id=$(".zone_id").val();
   var region_id=$(".region_id").val();

   if(status == "0"){
    $(this).closest('tr').find(".summary_status").val(1);
    $(".vareity_wise_summary_div").css("display","block");
    vareity_wise_summary_details("yes","Get_Variety_Wise_Summary",product_division_id,zone_id,region_id,crop_id); 
}else{
    $(this).closest('tr').find(".summary_status").val(0);
    $(".vareity_wise_summary_div").css("display","none");
}
});

$(document).on("click",".region_wise_summary tbody .summary",function()
{
   var crop_id=$(".crop_id").val();
   var status=$(this).closest('tr').find(".summary_status").val();
   var product_division_id=$(".product_division_id").val();
   var zone_id=$(this).closest("tr").find(".table_zone_id").val();
   var region_id=$(this).closest("tr").find(".table_region_id").val();


   if(status == "0"){
    $(this).closest('tr').find(".summary_status").val(1);
    $(".territory_wise_summary_div").css("display","block");
    territory_wise_summary_details("yes","Get_Territory_Wise_Summary",product_division_id,zone_id,region_id,crop_id);
}else{
    $(this).closest('tr').find(".summary_status").val(0);
    $(".territory_wise_summary_div").css("display","none");


}
});


$(document).on("click",".zone_wise_summary tbody .summary",function()
{
   var zone_id=$(this).closest("tr").find(".table_zone_id").val();
   var status=$(this).closest('tr').find(".summary_status").val();
   var product_division_id=$(".product_division_id").val();
   var crop_id=$(".crop_id").val();
   var hybrid_id=$(".hybrid_id").val();

   var region_id=$(".region_id").val();
   var plant_code=$(".c_f_id").val();
   var indent_type_id=$(".indent_type_id").val();
   if(status == "0"){
    $(this).closest('tr').find(".summary_status").val(1);
    $(".region_wise_summary_div").css("display","block");
    region_wise_summary_details("yes","Get_Region_Wise_Summary",product_division_id,zone_id,0,crop_id);
}else{
    $(this).closest('tr').find(".summary_status").val(0);
    $(".region_wise_summary_div").css("display","none");
    $(".territory_wise_summary_div").css("display","none");
}
});
$(document).on("submit",".submit_form",function(){
    $(".region_wise_summary_div,.vareity_wise_summary_div,.territory_wise_summary_div").css("display","none");

	var product_division_id,user_zone_id,user_region_id,plant_code,hybrid_id,crop_id,indent_type_id=0;
   product_division_id=$(".product_division_id").val();
   user_zone_id=$(".zone_id").val();
   user_region_id=$(".region_id").val();
   crop_id=$(".crop_id").val();
   zone_wise_summary_details("yes","Get_Zone_Wise_Summary",product_division_id,user_zone_id,user_region_id,crop_id);
   crop_wise_summary_details("yes","Get_Crop_Wise_Summary",product_division_id,user_zone_id,user_region_id,crop_id);
   /* region_wise_summary_details("yes","Get_Region_Wise_Summary",product_division_id,user_zone_id,user_region_id,crop_id);
   territory_wise_summary_details("yes","Get_Territory_Wise_Summary",product_division_id,user_zone_id,user_region_id,crop_id);
   vareity_wise_summary_details("yes","Get_Variety_Wise_Summary",product_division_id,user_zone_id,user_region_id,crop_id); */
   //alert("in");
   return false;
   var user_input={};
   user_input.zone_id=$(".zone_id").val();
   user_input.crop_id=$(".crop_id").val();
   user_input.product_division_id=$(".product_division_id").val();
   user_input.region_id=$(".region_id").val();
   
   Server_Side_Datatable("yes",user_input);
   return false;
})

function numberWithCommas(x) {
 return x.toString().replace(/\B(?!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
}

function zone_wise_summary_details(destroy_status, Action, product_division_id, ZoneId, RegionId, CropCode) {

    if (destroy_status == "yes") {
        $('.zone_wise_summary').DataTable().destroy();
    }

    $('.zone_wise_summary').DataTable({
        "columnDefs": [{
            "className": "summary amount_align td_align",
            "targets": [2, 3, 4]
        },{
            "className": "summary",
            "targets": 0
        },{
            "className": "popup_indent td_align",
            "targets": 1
        }],
        /* Footer Start Here */
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api(),
            data;
            // converting to interger to find total
            var intVal = function(i) {
                return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                i : 0;
            };
            var qty = 0;
            for (var i = 1; i < 5; i++) {
                qty = api.column(i)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

                $(api.column(i).footer()).html(numberWithCommas(qty));
            }
            $(api.column(0).footer()).html('Total');
        },
        /* Footer End Here */
        "bPaginate": false,
        "bInfo": false,
        "searching": false,
        // "scrollX": true,
        responsive: true,
        "pageLength": 10,
        "aProcessing": true,
        "aServerSide": true,

        "ajax": {
            "url": "Sales_Indent_Summary_Ajax.php",
            "type": "POST",
            "data": {
                Action: Action,
                product_division_id: product_division_id,
                ZoneId: ZoneId,
                RegionId: RegionId,
                CropCode: CropCode


            }
        },

    });
}

function crop_wise_summary_details(destroy_status, Action, product_division_id, ZoneId, RegionId, CropCode) {

    if (destroy_status == "yes") {
        $('.crop_wise_summary').DataTable().destroy();
    }

    $('.crop_wise_summary').DataTable({
        "columnDefs": [{
            "className": "summary amount_align td_align",
            "targets": [2, 3, 4]
        },{
            "className": "summary",
            "targets": 0
        },{
            "className": "popup_indent td_align",
            "targets": 1
        }],
        /* Footer Start Here */
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api(),
            data;
            // converting to interger to find total
            var intVal = function(i) {
                return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                i : 0;
            };
            var qty = 0;
            for (var i = 1; i < 5; i++) {
                qty = api.column(i)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

                $(api.column(i).footer()).html(numberWithCommas(qty));
            }
            $(api.column(0).footer()).html('Total');
        },
        /* Footer End Here */
        "bPaginate": false,
        "bInfo": false,
        "searching": false,
        // "scrollX": true,
        responsive: true,
        "pageLength": 10,
        "aProcessing": true,
        "aServerSide": true,

        "ajax": {
            "url": "Sales_Indent_Summary_Ajax.php",
            "type": "POST",
            "data": {
             Action: Action,
             product_division_id: product_division_id,
             ZoneId: ZoneId,
             RegionId: RegionId,
             CropCode: CropCode

         }
     },

 });
}



function region_wise_summary_details(destroy_status, Action, product_division_id, ZoneId, RegionId, CropCode) {

    if (destroy_status == "yes") {
        $('.region_wise_summary').DataTable().destroy();
    }

    $('.region_wise_summary').DataTable({
        "columnDefs": [{
            "className": "summary amount_align td_align",
            "targets": [2, 3, 4]
        },{
            "className": "summary",
            "targets": 0
        },{
            "className": "popup_indent td_align",
            "targets": 1
        }],
        /* Footer Start Here */
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api(),
            data;
            // converting to interger to find total
            var intVal = function(i) {
                return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                i : 0;
            };
            var qty = 0;
            for (var i = 1; i < 5; i++) {
                qty = api.column(i)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

                $(api.column(i).footer()).html(numberWithCommas(qty));
            }
            $(api.column(0).footer()).html('Total');
        },
        /* Footer End Here */
        "bPaginate": false,
        "bInfo": false,
        "searching": false,
        
        responsive: true,
        "pageLength": 10,
        "aProcessing": true,
        "aServerSide": true,

        "ajax": {
            "url": "Sales_Indent_Summary_Ajax.php",
            "type": "POST",
            "data": {
               Action: Action,
               product_division_id: product_division_id,
               ZoneId: ZoneId,
               RegionId: RegionId,
               CropCode: CropCode


           }
       },

   });
}
function territory_wise_summary_details(destroy_status, Action, product_division_id, ZoneId, RegionId, CropCode) {

    if (destroy_status == "yes") {
        $('.territory_wise_summary').DataTable().destroy();
    }

    $('.territory_wise_summary').DataTable({
        "columnDefs": [{
            "className": "summary amount_align td_align",
            "targets": [2, 3, 4]
        },{
            "className": "summary",
            "targets": 0
        },{
            "className": "popup_indent td_align",
            "targets": 1
        }],
        /* Footer Start Here */
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api(),
            data;
            // converting to interger to find total
            var intVal = function(i) {
                return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                i : 0;
            };
            var qty = 0;
            for (var i = 1; i < 5; i++) {
                qty = api.column(i)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

                $(api.column(i).footer()).html(numberWithCommas(qty));
            }
            $(api.column(0).footer()).html('Total');
        },
        /* Footer End Here */
        "bPaginate": false,
        "bInfo": false,
        "searching": false,
        responsive: true,
        "pageLength": 10,
        "aProcessing": true,
        "aServerSide": true,

        "ajax": {
            "url": "Sales_Indent_Summary_Ajax.php",
            "type": "POST",
            "data": {
               Action: Action,
               product_division_id: product_division_id,
               ZoneId: ZoneId,
               RegionId: RegionId,
               CropCode: CropCode


           }
       },

   });
}

function vareity_wise_summary_details(destroy_status, Action, product_division_id, ZoneId, RegionId, CropCode) {

    if (destroy_status == "yes") {
        $('.vareity_wise_summary').DataTable().destroy();
    }

    $('.vareity_wise_summary').DataTable({
        "columnDefs": [{
            "className": "summary amount_align td_align",
            "targets": [2, 3, 4]
        },{
            "className": "summary",
            "targets": 0
        },{
            "className": "popup_indent td_align",
            "targets": 1
        }],
        /* Footer Start Here */
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api(),
            data;
            // converting to interger to find total
            var intVal = function(i) {
                return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                i : 0;
            };
            var qty = 0;
            for (var i = 1; i < 5; i++) {
                qty = api.column(i)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

                $(api.column(i).footer()).html(numberWithCommas(qty));
            }
            $(api.column(0).footer()).html('Total');
        },
        /* Footer End Here */
        "bPaginate": false,
        "bInfo": false,
        "searching": false,
        responsive: true,
        "pageLength": 10,
        "aProcessing": true,
        "aServerSide": true,

        "ajax": {
            "url": "Sales_Indent_Summary_Ajax.php",
            "type": "POST",
            "data": {
                Action: Action,
                product_division_id: product_division_id,
                ZoneId: ZoneId,
                RegionId: RegionId,
                CropCode: CropCode

            }
        },

    });
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

$(document).on("change",".product_division_id",function(){
    var zone_id=0;
    var region_id=0;
    var territory_id=0;
    var crop_code=0;
    var variety_code=0;
    var product_division = $(".product_division_id").val();
    role_based_filter(product_division,"Get_Zone_Details",zone_id,region_id,territory_id,crop_code,variety_code);
});
$(document).on("change",".zone_id",function(){
    var zone_id=$(this).val();
    var region_id=0;
    var territory_id=0;
    var crop_code=0;
    var variety_code=0;
    var product_division = $(".product_division_id").val();
    role_based_filter(product_division,"Get_Region_Details",zone_id,region_id,territory_id,crop_code,variety_code);

});

$(document).on("change",".crop_id",function(){
    var zone_id=0;
    var region_id=0;
    var territory_id=0;
    var crop_code=$(this).val();
    var variety_code=0;
    var product_division = $(".product_division_id").val();
    role_based_filter(product_division,"Get_varirty_Details",zone_id,region_id,territory_id,crop_code,variety_code);
});

$(document).on("change",".region_id",function(){
    var zone_id=$(".zone_id").val();
    var region_id=$(this).val();
    var territory_id=0;
    var crop_code=0;
    var variety_code=0;
    var product_division = $(".product_division_id").val();
    role_based_filter(product_division,"Get_Territory_Details",zone_id,region_id,territory_id,crop_code,variety_code);
});



$(document).on("click",".popup_indent",function(){
    var product_division_id=$(".product_division_id").val();
    var from = $(this).closest('tr').find('.table_name').val();
    var req_obj = { Action : 'get_indent_sumary_details',from : from,product_division_id : product_division_id  };

    if(from == 'zone') {
        var zone_id = $(this).closest('tr').find('.table_zone_id').val();
        req_obj.zone_id = zone_id; 
    } else if(from == 'region') {
        var zone_id   = $(this).closest('tr').find('.table_zone_id').val();
        var region_id = $(this).closest('tr').find('.table_region_id').val();
        req_obj.zone_id   = zone_id; 
        req_obj.region_id = region_id; 
    } else if(from == 'territory') {
        var zone_id         = $(this).closest('tr').find('.table_zone_id').val();
        var region_id       = $(this).closest('tr').find('.table_region_id').val();
        var territory_id    = $(this).closest('tr').find('.table_territory_id').val();
        req_obj.zone_id     = zone_id; 
        req_obj.region_id   = region_id;
        req_obj.territory_id= territory_id; 
    } else if(from == 'crop') {
        var crop_id = $(this).closest('tr').find('.table_crop_id').val();
        req_obj.crop_id = crop_id; 
    } else if(from == 'variety') {
        var crop_id = $(this).closest('tr').find('.table_crop_id').val();
        var material_code = $(this).closest('tr').find('.table_variety_code').val();
        req_obj.crop_id = crop_id; 
        req_obj.material_code = material_code; 
    }


    $.ajax({
      type: "POST",
      url: "Sales_Indent_Summary_Ajax.php",
      data: req_obj,
      dataType: 'json',
      success: function(data){
        $('#indent_summary_tbl').DataTable().destroy(); 
         var html = '';
         var pkt_total = 0;
         data.forEach(function(val){
            pkt_total = parseInt(pkt_total) + parseInt(val.QtyInPkt);
            html += `<tr>
            <td>${ val.ReqId }</td>
            <td>${ val.Zone_Name }</td>
            <td>${ val.REGIONNAME }</td>
            <td>${ val.TMNAME }</td>
            <td>${ val.MaterialCode }</td>
            <td class='td_align'>${ parseInt(val.QtyInBag) }</td>
            <td class='td_align'>${ parseInt(val.QtyInPkt) }</td>
            </tr>`; 
         });
         $('.pkt_total').text(pkt_total);
         $('.indent_summary_tbody').html(html);
         indent_summary_datatable();
         $('.indent_summary_modal').modal('show');
      }
    });

});


function indent_summary_datatable() 
{
   $('#indent_summary_tbl').DataTable({
    "dom": 'Brtp',
   });
}