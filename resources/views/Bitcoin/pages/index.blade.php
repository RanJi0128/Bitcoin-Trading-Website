<?php
function number_format_short( $n, $precision = 1 ) {
   if ($n < 900000) {
       // 0 - 900
       $n_format = number_format($n, $precision);
       $suffix = '';
   }else if ($n < 900000000) {
       // 0.9m-850m
       $n_format = number_format($n / 1000000, $precision);
       $suffix = 'M';
   } else if ($n < 900000000000) {
       // 0.9b-850b
       $n_format = number_format($n / 1000000000, $precision);
       $suffix = 'B';
   } else {
       // 0.9t+
       $n_format = number_format($n / 1000000000000, $precision);
       $suffix = 'T';
   }
 // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
 // Intentionally does not affect partials, eg "1.50" -> "1.50"
   if ( $precision > 0 ) {
       $dotzero = '.' . str_repeat( '0', $precision );
       $n_format = str_replace( $dotzero, '', $n_format );
   }
   return $n_format . $suffix;
}

?>
@extends('Bitcoin.layouts.default')
@section('content')

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <ul id="webticker-5">
               <li><i class="cc BTC"></i><span class="text-info"> BTC </span><span class="text-warning"> $11.039232</span></li>
               <li><i class="cc ETH"></i><span class="text-info"> ETH </span><span class="text-warning"> $1.2792</span></li>
               <li><i class="cc GAME"></i><span class="text-info"> GAME </span><span class="text-warning"> $11.039232</span></li>
               <li><i class="cc LBC"></i> <span class="text-info"> LBC </span><span class="text-warning"> $0.588418</span></li>
               <li><i class="cc NEO"></i><span class="text-info"> NEO </span><span class="text-warning"> $161.511</span></li>
               <li><i class="cc STEEM"></i><span class="text-info"> STE </span><span class="text-warning"> $0.551955</span></li>
               <li><i class="cc LTC"></i><span class="text-info"> LIT </span><span class="text-warning"> $177.80</span></li>
               <li><i class="cc NOTE"></i><span class="text-info"> NOTE </span><span class="text-warning"> $13.399</span></li>
               <li><i class="cc MINT"></i><span class="text-info"> MINT </span><span class="text-warning"> $0.880694</span></li>
               <li><i class="cc IOTA"></i><span class="text-info"> IOT </span><span class="text-warning"> $2.555</span></li>
               <li><i class="cc DASH"></i><span class="text-info"> DAS </span><span class="text-warning"> $769.22</span></li>
            </ul>
         </div>
      </div>
   </div>
</div>

<div class="card-group">
   <div class="card">
   <div class="card-header" style="border-bottom:1px solid #858585 !important;">
      <span style="font-size:18px;font-weight:bold;">Total Transactions vs Bitcoin Price</span>
         <div class="card-actions">
               <a class="" data-action="collapse"><i class="ti-minus"></i></a>
               <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
         </div>
      </div>
      <div class="card-body collapse show">
         <canvas id="chLine"  style="height:260px; width:100%;"></canvas>
      </div>
   </div>

   <div class="card">
      <div class="card-header" style="border-bottom:1px solid #858585 !important;">
      <span style="font-size:18px;font-weight:bold;">Transactions from_owner=exchange vs Bitcoin Price</span>
         <div class="card-actions">
               <a class="" data-action="collapse"><i class="ti-minus"></i></a>
               <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
         </div>
      </div>
      <div class="card-body collapse show">
         <canvas id="chLine1"  style="height:260px; width:100%;"></canvas>
      </div>
   </div>
   <div class="card">
      <div class="card-header" style="border-bottom:1px solid #858585 !important;">
         <span style="font-size:18px;font-weight:bold;">Transactions to_owner=exchange vs  Bitcoin Price</span>
         <div class="card-actions">
               <a class="" data-action="collapse"><i class="ti-minus"></i></a>
               <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
         </div>
      </div>
      <div class="card-body collapse show">
         <canvas id="chLine2"  style="height:260px; width:100%;"></canvas>
      </div>
   </div>
</div>

<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title">Crypto Statitics</h4>
            <div class="table-responsive m-t-20">
               <table id="cc-table" class="table table-bordered table-striped" data-page-length='10'>
                  <thead>
                     <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">Time(h)</th>
                        <th class="text-center">Bitcoin</th>
                        <th class="text-center">Amount(Usd)</th>
                        <th class="text-center">Amount(from_owner=exchange)</th>
                        <th class="text-center">Amount(to_owner=exchange)</th>
                        <th class="text-center">Timestamp</th>
                        <th class="text-center">Readable DateTime)</th>
                     </tr>
                  </thead>
                  <tbody>
                  @php($labels='')
                  @php($bit_val='')
                  @php($amt_val='')
                  @php($amt_val_from='')
                  @php($amt_val_to='')
                  @foreach($bits as $bit_data)
                        @php($hs=explode("-",$bit_data->dtime))
                        @php($labels.=("'".$hs[0]."',"))
                        @php($bit_val.=("'".$bit_data->rate."',"))
                        @php($amt_val.=("'".$bit_data->amount_usd."',"))
                        @php($amt_val_from.=("'".$bit_data->amount_usd_from."',"))
                        @php($amt_val_to.=("'".$bit_data->amount_usd_to."',"))
                        @php($rdtm=date('m/d/Y H:i:s', $bit_data->timestamp))
                        <tr>
                            <td class="text-center">{{ $daterange }}</td>
                            <td class="text-center">{{ $hs[0] }}</td>
                            <td class="text-center"> {{ number_format_short($bit_data->rate) }}</td>
                            <td class="text-center"> {{ number_format_short($bit_data->amount_usd) }}</td>
                            <td class="text-center"> {{ number_format_short($bit_data->amount_usd_from) }}</td>
                            <td class="text-center"> {{ number_format_short($bit_data->amount_usd_to) }}</td>
                            <td class="text-center"> {{ $bit_data->timestamp }}</td>
                            <td class="text-center"> {{ $rdtm }} </td>
                        </tr>
                  @endforeach 
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="row">
   <!-- Column -->
   <div class="col-lg-3 col-md-6">
      <div class="card">
         <div class="card-body">
            <h5 class="card-title">TOTAL VISIT</h5>
            <div class="d-flex no-block align-items-center m-t-20 m-b-20">
               <div id="sparklinedash"></div>
               <div class="ml-auto">
                  <h2 class="text-success"><i class="ti-arrow-up"></i> <span class="counter">8659</span></h2>
               </div>
            </div>
         </div>
         <div id="sparkline8" class="sparkchart"></div>
      </div>
   </div>
   <!-- Column -->
   <div class="col-lg-3 col-md-6">
      <div class="card">
         <div class="card-body">
            <h5 class="card-title">TOTAL PAGE VIEWS</h5>
            <div class="d-flex no-block align-items-center m-t-20 m-b-20">
               <div id="sparklinedash2"></div>
               <div class="ml-auto">
                  <h2 class="text-purple"><i class="ti-arrow-up"></i> <span class="counter">7469</span></h2>
               </div>
            </div>
         </div>
         <div id="sparkline8" class="sparkchart"></div>
      </div>
   </div>
   <!-- Column -->
   <div class="col-lg-3 col-md-6">
      <div class="card">
         <div class="card-body">
            <h5 class="card-title">UNIQUE VISITOR</h5>
            <div class="d-flex no-block align-items-center m-t-20 m-b-20">
               <div id="sparklinedash3"></div>
               <div class="ml-auto">
                  <h2 class="text-info"><i class="ti-arrow-up"></i> <span class="counter">6011</span></h2>
               </div>
            </div>
         </div>
         <div id="sparkline8" class="sparkchart"></div>
      </div>
   </div>
   <!-- Column -->
   <!-- Column -->
   <div class="col-lg-3 col-md-6">
      <div class="card">
         <div class="card-body">
            <h5 class="card-title">BOUNCE RATE</h5>
            <div class="d-flex no-block align-items-center m-t-20 m-b-20">
               <div id="sparklinedash4"></div>
               <div class="ml-auto">
                  <h2 class="text-danger"><i class="ti-arrow-down"></i> <span class="counter">18%</span></h2>
               </div>
            </div>
         </div>
         <div id="sparkline8" class="sparkchart"></div>
      </div>
   </div>
   <!-- Column -->
</div>
<script>
   $(document).ready(function () {
    function fnum(x) {
        if (isNaN(x)) return x;

        if (x < 9999) {
            return x;
        }

        if (x < 1000000) {
            return (x / 1000).toFixed(2) + "K";
        }
        if (x < 10000000) {
            return (x / 1000000).toFixed(2) + "M";
        }

        if (x < 1000000000) {
            return Math.round((x / 1000000)) + "M";
        }

        if (x < 1000000000000) {
            return Math.round((x / 1000000000)) + "B";
        }

        return "1T+";
    }

    $('#cc-table').DataTable({
        "displayLength": 10
    });
    var ctx = document.getElementById('chLine').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php echo $labels;
            ?>],
            datasets: [{
                label: "bitcoin",
                yAxisID: 'A',
                data: [<?php echo $bit_val;
                ?>],
                backgroundColor: ['rgba(0, 255, 0, 0.2)', ],
                borderColor: ['rgba(255, 0, 0, 1)'],
                borderWidth: 1
            }, {
                label: "Amount_Usd",
                yAxisID: 'B',
                data: [<?php echo $amt_val;
                ?>],
                backgroundColor: ['rgba(255, 0, 0, 0.2)', ],
                borderColor: ['rgba(0, 255, 0, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    id: 'A',
                    type: 'linear',
                    position: 'left',
                    gridLines: {
                        display: false
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Bitcoin'
                    },
                    ticks: {
                        beginAtZero: false,
                        callback: function (value, index, values) {
                            return fnum(value);
                        }
                    }
                }, {
                    id: 'B',
                    type: 'linear',
                    position: 'right',
                    scaleLabel: {
                        display: true,
                        labelString: 'Amount_Usd'
                    },
                    ticks: {
                        beginAtZero: false,
                        callback: function (value, index, values) {
                            return fnum(value);
                        }
                    }
                }],
                legend: {
                    position: 'bottom',
                    labels: {
                        fontColor: "white",
                        boxWidth: 20,
                        padding: 20
                    }
                }
            }
        }
    });
    ctx1 = document.getElementById('chLine1').getContext('2d');
    var myChart1 = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: [<?php echo $labels;
            ?>],
            datasets: [{
                label: "bitcoin",
                yAxisID: 'A',
                data: [<?php echo $bit_val;
                ?>],
                backgroundColor: ['rgba(0, 255, 0, 0.2)', ],
                borderColor: ['rgba(255, 0, 0, 1)'],
                borderWidth: 1
            }, {
                label: "Amount_Usd_from",
                yAxisID: 'B',
                data: [<?php echo $amt_val_from;
                ?>],
                backgroundColor: ['rgba(255, 0, 0, 0.2)', ],
                borderColor: ['rgba(0, 255, 0, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    id: 'A',
                    type: 'linear',
                    position: 'left',
                    gridLines: {
                        display: false
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Bitcoin'
                    },
                    ticks: {
                        beginAtZero: false,
                        callback: function (value, index, values) {
                            return fnum(value);
                        }
                    }
                }, {
                    id: 'B',
                    type: 'linear',
                    position: 'right',
                    scaleLabel: {
                        display: true,
                        labelString: 'Amount_Usd_from'
                    },
                    ticks: {
                        beginAtZero: false,
                        callback: function (value, index, values) {
                            return fnum(value);
                        }
                    }
                }],
                legend: {
                    position: 'bottom',
                    labels: {
                        fontColor: "white",
                        boxWidth: 20,
                        padding: 20
                    }
                }
            }
        }
    });
    ctx2 = document.getElementById('chLine2').getContext('2d');
    var myChart2 = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: [<?php echo $labels;
            ?>],
            datasets: [{
                label: "bitcoin",
                yAxisID: 'A',
                data: [<?php echo $bit_val;
                ?>],
                backgroundColor: ['rgba(0, 255, 0, 0.2)', ],
                borderColor: ['rgba(255, 0, 0, 1)'],
                borderWidth: 1
            }, {
                label: "Amount_Usd",
                yAxisID: 'B',
                data: [<?php echo $amt_val_to;
                ?>],
                backgroundColor: ['rgba(255, 0, 0, 0.2)', ],
                borderColor: ['rgba(0, 255, 0, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    id: 'A',
                    type: 'linear',
                    position: 'left',
                    gridLines: {
                        display: false
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Bitcoin'
                    },
                    ticks: {
                        beginAtZero: false,
                        callback: function (value, index, values) {
                            return fnum(value);
                        }
                    }
                }, {
                    id: 'B',
                    type: 'linear',
                    position: 'right',
                    scaleLabel: {
                        display: true,
                        labelString: 'Amount_Usd'
                    },
                    ticks: {
                        beginAtZero: false,
                        callback: function (value, index, values) {
                            return fnum(value);
                        }
                    }
                }],
                legend: {
                    position: 'bottom',
                    labels: {
                        fontColor: "white",
                        boxWidth: 20,
                        padding: 20
                    }
                }
            },
        }
    });
});
</script>
@stop

