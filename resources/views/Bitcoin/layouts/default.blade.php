<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('public/bitcoin/assets/images/favicon.png') }}">
        <title>Cryptoflow : Monitoring crypto for Investors</title>
        <link href="{{ asset('public/bitcoin/assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
        <link href="{{ asset('public/bitcoin/assets/node_modules/morrisjs/morris.css') }}" rel="stylesheet">        
        <link href="{{ asset('public/bitcoin/assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">        
        <link href="{{ asset('public/bitcoin/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('public/bitcoin/assets/node_modules/timepicker/bootstrap-timepicker.min.css') }}" rel="stylesheet">     
        <link href="{{ asset('public/bitcoin/assets/node_modules/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">        
        <link href="{{ asset('public/bitcoin/assets/node_modules/c3-master/c3.min.css') }}" rel="stylesheet">
        <link href="{{ asset('public/bitcoin/custom/dist1/css/style.min.css') }}" rel="stylesheet">
        <link href="{{ asset('public/bitcoin/custom/dist1/css/pages/dashboard4.css') }}" rel="stylesheet">
        <link href="{{ asset('public/bitcoin/custom/dist1/css/pages/widget-page.css') }}" rel="stylesheet">
        <link href="{{ asset('public/bitcoin/custom/dist1/css/pages/tab-page.css') }}" rel="stylesheet">

        <style>
            .show-ranges {
                right:10px !important;
                left:auto  !important;
                position:absolute;
            }
            body{
                font-family:"Times New Roman";
            }
            #cc-table td{padding:5px;line-height:28px;text-align:center;}
    
            #his td, #his th{
                padding:5px;
            }
        </style>
        <script src="{{ asset('public/bitcoin/assets/node_modules/jquery/jquery-3.2.1.min.js') }}"></script>
        <script src="{{ asset('public/bitcoin/assets/node_modules/popper/popper.min.js') }}"></script>
        <script src="{{ asset('public/bitcoin/assets/node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>
            $( document ).ready(function() {
            var daterange='<?php echo $daterange;?>';
            var myarr = daterange.split(" - ");
            var start = myarr[0];
                var end = myarr[1];
                function cb(start, end) {
                    $('#reportrange input').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
                }
                $('#reportrange').daterangepicker({
                    startDate: start,
                    endDate: end,
                    ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, cb);
                $('#reportrange input').val(start + ' - ' + end);
                $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                    go();
                });
            // http://www.daterangepicker.com/#usage
                
            $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });            
            });
            var timer_cnt=0;
            var timestamp=<?php echo time();?>;
            var mytimer= setInterval(myFunction, 1000);
            function myFunction(){
        
                timer_cnt++;
                /************************ */
                timestamp++;
                date=new Date(timestamp*1000);
                var year = date.getFullYear();
                var month =date.getMonth()+1;
                var day = date.getDate();
                var hours = date.getHours();
                var minutes = "0" + date.getMinutes();
                var seconds = "0" + date.getSeconds();
                var convdatetime = month+'-'+day+'-'+year+' '+hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
                $('#cdt').html(convdatetime);
                /************************ */
                if(timer_cnt!=10)return;
                timer_cnt=0;
                $.ajax({
                    type: "post",
                    url:"{{url('cron')}}",
                    data:{"param":""},
                    dataType: "text",
                    success: function(response) {
                        document.getElementById("csrf-token").value =response;
                        console.log(response);
                    },
                    error: function(response) {
                    console.log("error");
                    }
                });
            }        
            function go(){
                myform.action="{{url($selected)}}";
                myform.submit();
            }

            function go1(){
                myform.action="{{url('/')}}";
                myform.submit();
            }

            function go2(){
                myform.action="{{url('transaction')}}";
                myform.submit();
            }
            function go3(){
                myform.action="{{url('exchanges')}}";
                myform.submit();
            }
        </script>
    </head>
    <body class="horizontal-nav skin-megna fixed-layout">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Crypto Flow Loading</p>
            </div>
        </div>
        <div id="main-wrapper">
            <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                    <div class="navbar-header">
                            <a class="navbar-brand" href="{{url('/')}}">
                                <b>
                                    <img src="{{ asset('public/bitcoin/assets/images/logo-icon.png') }}" alt="homepage" class="dark-logo" />
                                    <img src="{{ asset('public/bitcoin/assets/images/logo-light-icon.png') }}" alt="homepage" class="light-logo" />
                                </b>
                                <span class="hidden-sm-down">
                                    <img src="{{ asset('public/bitcoin/assets/images/logo-text.png') }}" alt="homepage" class="dark-logo" />
                                    <img src="{{ asset('public/bitcoin/assets/images/logo-light-text.png') }}" class="light-logo" alt="homepage" />
                                </span>
                            </a>
                    </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                           
                        </li>
                    </ul>
                    <ul  class="navbar-nav my-lg-0">
                        <li class="nav-item right-side-toggle"> <a class="nav-link  waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
                    </ul>
                </div>
            </nav>
            </header>
            <aside class="left-sidebar">
                <!-- Sidebar scroll-->
                <div class="scroll-sidebar row">
                    <div class="col-md-9 align-self-center">
                        <nav class="sidebar-nav">
                            <ul id="sidebarnav">
                                <li class="nav-small-cap">--- PERSONAL</li>
                                <li onclick="go1();">
                                    @if($selected=="/")
                                        <a class="active has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="icon-speedometer"></i><span class="hide-menu">Home<span class="badge badge-pill badge-cyan ml-auto">4</span></span></a>
                                    @else
                                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="icon-speedometer"></i><span class="hide-menu">Home<span class="badge badge-pill badge-cyan ml-auto">4</span></span></a>
                                    @endif                            
                                </li>
                                <li onclick="go2();">
                                    @if($selected=="transaction")
                                    <a class="active has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Transaction</span></a>
                                    @else
                                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Transaction</span></a>
                                    @endif                                       
                                </li>
                                <li onclick="go3();">
                                    @if($selected=="exchanges")
                                    <a class="active has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">exchanges</span></a>
                                    @else
                                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">exchanges</span></a>
                                    @endif                                       
                                </li>
                            </ul>                    
                        </nav>
                    </div>  
                    <div class="col-md-3 align-self-center">
                        <form name="myform" id="myform" method="post" style="margin:0px;">
                            <button type="submit" disabled style="display: none" aria-hidden="true"></button>
                            <input type="hidden" name="_token" id="csrf-token" value="{{ csrf_token() }}" />
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="input-group" id="reportrange">
                                    <input class="form-control" id="daterange" name="daterange" value="" placeholder="Now">
                                    <div class="input-group-append">
                                        <button type="button" id="check-minutes" class="btn btn-dark waves-effect waves-light"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;Selecte the Date</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>                                    
                </div>
                <!-- End Sidebar scroll-->
            </aside>
            <div class="page-wrapper">
                <div class="container-fluid">
                    @yield('content')                   
                    <div class="right-sidebar">
                        <div class="slimscrollright">
                            <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                            <div class="r-panel-body">
                                <ul id="themecolors" class="m-t-20">
                                    <li><b>With Light sidebar</b></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-default" class="default-theme">1</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-green" class="green-theme">2</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-red" class="red-theme">3</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-blue" class="blue-theme">4</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-purple" class="purple-theme">5</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-megna" class="megna-theme working">6</a></li>
                                    <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-default-dark" class="default-dark-theme ">7</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-green-dark" class="green-dark-theme">8</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-red-dark" class="red-dark-theme">9</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-blue-dark" class="blue-dark-theme">10</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-purple-dark" class="purple-dark-theme">11</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-megna-dark" class="megna-dark-theme ">12</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
               Please contact me to advertise on this space 
            </footer>
        </div>
    </body>

    <script src="{{ asset('public/bitcoin/custom/dist1/js/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('public/bitcoin/custom/dist1/js/waves.js') }}"></script>
    <script src="{{ asset('public/bitcoin/custom/dist1/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('public/bitcoin/custom/dist1/js/custom.min.js') }}"></script>
    <script src="{{ asset('public/bitcoin/custom/dist1/js/jquery.webticker.min.js') }}"></script>
    <script src="{{ asset('public/bitcoin/custom/dist1/js/fastclick.js') }}"></script>
    <script src="{{ asset('public/bitcoin/custom/dist1/js/web-ticker.js') }}"></script>        
    <script src="{{ asset('public/bitcoin/assets/node_modules/skycons/skycons.js') }}"></script>
    <script src="{{ asset('public/bitcoin/assets/node_modules/morrisjs/morris.min.js') }}"></script>
    <script src="{{ asset('public/bitcoin/assets/node_modules/d3/d3.min.js') }}"></script>
    <script src="{{ asset('public/bitcoin/assets/node_modules/c3-master/c3.min.js') }}"></script>
    <script src="{{ asset('public/bitcoin/assets/node_modules/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('public/bitcoin/assets/node_modules/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('public/bitcoin/assets/node_modules/moment/moment.js') }}"></script>
    <script src="{{ asset('public/bitcoin/assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ asset('public/bitcoin/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('public/bitcoin/assets/node_modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('public/bitcoin/assets/node_modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/bitcoin/assets/node_modules/Chart.js/Chart.js') }}"></script>
</html>
