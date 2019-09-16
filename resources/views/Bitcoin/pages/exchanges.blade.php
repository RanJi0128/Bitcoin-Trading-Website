@extends('Bitcoin.layouts.default')
@section('content')
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body p-b-0">
            <h4 class="card-title">Cryptocurrencies</h4>
            <ul class="nav nav-tabs customtab2" role="tablist">
               <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home7" role="tab" aria-selected="true"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Bitcoin</span></a> </li>
               <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile7" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Ethereum</span></a> </li>
               <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#messages7" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Litecoin</span></a> </li>
            </ul>
            <div class="tab-content">
               <div class="tab-pane active" id="home7" role="tabpanel">
                     <div class="card" style="border:1px solid #cccccc;">
                        <div class="card-header" style="border-bottom:1px solid #858585 !important;">
                           <span style="font-size:18px;font-weight:bold;">Top 20 exchanges Volume Per Hour</span>
                           <input type="button" value="select none" onclick="select_none()">
                           <input type="button" value="select all" onclick="select_all()">
                           <!-- <input type="button" onclick="aa();" value="delete all"> -->
                           <div class="card-actions">
                              <a class="" data-action="collapse"><i class="ti-minus"></i></a>
                              <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
                           </div>
                        </div>
                        <div class="card-body collapse show">
                           <canvas id="chLine"  style="height:570px; width:100%;"></canvas>
                        </div>
                     </div>                  
                     <div class="card" style="border:1px solid #cccccc;">
                        <div class="card-body collapse show">
                           <table id="cc-table" class="table table-bordered table-striped" data-page-length='10'>
                              <thead>
                                 <tr>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Time(h)</th>
                                    <th class="text-center">exchange</th>
                                    <th class="text-center">mktcaphour</th>
                                    <th class="text-center">volumehour</th>
                                 </tr>
                              </thead>
                              <tbody>
                              <?php 
                                 $cnt=0;$label="";
                                 $vdatas=array();
                                 $legend=array();
                              ?>
                              @foreach($records as $record)
                                  @php($str="")
                                  @foreach($record as $obj)
                                    <?php
                                    if($cnt==0){
                                       $label.="$obj->hour,";
                                    }
                                    $str.=intval($obj->volumehour).",";
                                    $lg=$obj->name;
                                    ?>    
                                    <tr>
                                       <td class="text-center">{{ $obj->date}}</td>
                                       <td class="text-center">{{ $obj->hour}}</td>
                                       <td class="text-center">{{ $obj->name}}</td>
                                       <td class="text-center">{{ intval($obj->mktcap-$obj->volumehour)}}</td>
                                       <td class="text-center">{{ intval($obj->volumehour)}}</td>
                                    </tr>
                                  @endforeach 
                                  <?php
                                 // echo $str."<br>";
                                  $legend[]=$lg;
                                  $vdatas[]=$str;
                                  ?>
                                  @php($cnt++)
                              @endforeach 
                              </tbody>
                           </table>
                        </div>
                     </div>
               </div>
               <div class="tab-pane p-20" id="profile7" role="tabpanel">
                  You are now allowed to browser this content.<br>
                  You must upgrade your membership.
               </div>
               <div class="tab-pane p-20" id="messages7" role="tabpanel">
                  You are now allowed to browser this content.<br>
                  You must upgrade your membership.
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   function fnum(x) {
      if (isNaN(x)) return x;
      var sign="";
      if(x<0){
         x=Math.abs(x);  
         sign="-"; 
      }
      if (x < 9999) {
         return x;
      }

      if (x < 1000000) {
         return sign+(x / 1000).toFixed(2) + "K";
      }
      if (x < 10000000) {
         return sign+(x / 1000000).toFixed(2) + "M";
      }

      if (x < 1000000000) {
         return sign+Math.round((x / 1000000)) + "M";
      }

      if (x < 1000000000000) {
         return sign+Math.round((x / 1000000000)) + "B";
      }

      return "1T+";
   }

   var myChart;
   var config ;
   $(document).ready(function () {
      $('#cc-table').DataTable({
         "displayLength": 10
      });

      var dataset=
      [
         <?php 
         $i=0;
         foreach($vdatas as $vdata) 
         {
         ?>
            {
               label: "<?php echo $legend[$i];?>",
               yAxisID: 'A',
               data: [<?php echo $vdata;?>],
               borderColor: ['rgba(<?php echo(rand(0,255));?>, <?php echo(rand(0,255));?>, <?php echo(rand(0,255));?>, 1)'],
               borderWidth: 2,
               fill: false
            },         
         <?php
         $i++;
         }  
         ?> 
         ];

      config = {
         type: 'line',
         data: {
               labels: [<?php echo $label;?>],
               datasets:dataset
         },
         options: {
               scales: {
                  yAxes: [
                     {
                     id: 'A',
                     ticks: {
                        beginAtZero: false,
                        callback: function (value, index, values) {
                            return fnum(value);
                        }
                     },
                     position: 'right'
                     },                   
                  ]
               },
               legend: {
                  labels: {
                     // This more specific font property overrides the global property
                     fontSize:12
                  }
               }
         },
      };
      var ctx = document.getElementById('chLine').getContext('2d');
      myChart = new Chart(ctx,config);

   });
	var randomScalingFactor = function() {
		return Math.ceil(Math.random() * 10.0) * Math.pow(10, Math.ceil(Math.random() * 5));
   };
   
   function select_all(){
      myChart.data.datasets.forEach(function(obj) {
         obj.hidden = true;
         obj._meta[0].hidden = false;
         obj._meta[0].dataset.hidden = false;
      });
      myChart.update();
   } 
   function select_none(){
      myChart.data.datasets.forEach(function(obj) {
         obj.hidden =false;
         obj._meta[0].hidden = true;
         obj._meta[0].dataset.hidden = true;
      });
      myChart.update();
   } 
</script>
@stop

