        <!-- JAVASCRIPT -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->

        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/metismenujs/metismenujs.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/eva-icons/eva.min.js"></script>

        <script src="assets/js/app.js"></script>
        
        <!-- select2 js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

        <!-- datepicker js -->
        <script src="assets/libs/flatpickr/flatpickr.min.js"></script>

        <!-- datatable js -->
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

        <!-- datatable responsive and csv export js files start -->
        <script src="../global/vendor/datatables.net-fixedheader/dataTables.fixedHeader.minfd53.js?v4.0.1"></script>
        <script src="../global/vendor/datatables.net-fixedcolumns/dataTables.fixedColumns.minfd53.js?v4.0.1"></script>
        <script src="../global/vendor/datatables.net-rowgroup/dataTables.rowGroup.minfd53.js?v4.0.1"></script>
        <script src="../global/vendor/datatables.net-scroller/dataTables.scroller.minfd53.js?v4.0.1"></script>
        <script src="../global/vendor/datatables.net-responsive/dataTables.responsive.minfd53.js?v4.0.1"></script>
        <script src="../global/vendor/datatables.net-responsive-bs4/responsive.bootstrap4.minfd53.js?v4.0.1"></script>
        <script src="../global/vendor/datatables.net-buttons/dataTables.buttons.minfd53.js?v4.0.1"></script>
        <script src="../global/vendor/datatables.net-buttons/buttons.html5.minfd53.js?v4.0.1"></script>
        <script src="../global/vendor/datatables.net-buttons/buttons.print.minfd53.js?v4.0.1"></script>
        <script src="../global/vendor/datatables.net-buttons/buttons.colVis.minfd53.js?v4.0.1"></script>
        <script src="../global/vendor/datatables.net-buttons-bs4/buttons.bootstrap4.minfd53.js?v4.0.1"></script>
        <!-- datatable responsive and csv export js files end -->

        <!-- <script src="assets/libs/tui-chart/tui-chart-all.min.js"></script> -->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tui-chart/3.11.3/tui-chart-all.min.js" integrity="sha512-KsBen39PCqLKXaGIUdNop+3ovUktc51GrMawyaxR9SArdo9pFpNogpYNFtkmaB0rpx/cyZfJsRD3RVSRBdwkUA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<script src="https://uicdn.toast.com/chart/latest/toastui-chart.min.js"></script>
        <!-- daterangepicker js -->
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script>
        $(document).ready(function(){
                $('body').attr('data-sidebar-size','sm');
                  $("#preloader").fadeOut("slow");

                $('input[name="fromdate"]').daterangepicker({
                        opens: 'right',
                         autoUpdateInput: false
                }, function(start, end, label) {
                        $('input[name="fromdate"]').val(start.format('DD-MM-YYYY'));
                        $('input[name="todate"]').val(end.format('DD-MM-YYYY'));

                        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                });

                $('input[name="todate"]').daterangepicker({
                        opens: 'right',
                         autoUpdateInput: false
                }, function(start, end, label) {
                        $('input[name="fromdate"]').val(start.format('DD-MM-YYYY'));
                        $('input[name="todate"]').val(end.format('DD-MM-YYYY'));

                        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                });


                $('.sub-menu').children('li').each(function() {
                        if($(this).hasClass('mm-active')) {
                           $(this).removeClass('mm-active');
                        }
                });

        });

        $(document).on('click','.vertical-menu-btn',function(){
           $('.sub-menu').each(function(){
                if($(this).hasClass('mm-collapsing')) {
                        $(this).removeClass('mm-collapsing');
                }

                $(this).css('height','');
           });

           if($('body').hasClass('sidebar-enable')) {
                $('.sm-module-sidebar').addClass('dis-none');
                $('.lg-module-sidebar').removeClass('dis-none');
           } else {
                $('.sm-module-sidebar').removeClass('dis-none');
                $('.lg-module-sidebar').addClass('dis-none');
           } 
        });

        function countto(render_obj,count_value) 
        {
            $('#'+render_obj).countTo({
                from: 0,
                to: count_value,
                speed: 1000,
                refreshInterval: 25,
            });
        }


        function datatable_search_change()
        {
                //datatable search input style change functionality call start
                $('.dataTables_filter').children('label').addClass('d-flex align-items-center');
                $('.dataTables_filter').children('label').find('input[type=search]').addClass('form-control');
                $('.dataTables_filter').children('label').find('input[type=search]').addClass('form-control dt-search-br');
                //datatable search input style change functionality call end
        }

        </script>