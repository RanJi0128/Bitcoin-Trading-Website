@extends('Bitcoin.layouts.default')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <!-- Column -->
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Data Table</h4>
                            <div class="table-responsive m-t-40">
                                <table id="myTable" class="table table-bordered table-striped">
                                    <thead style="background-color:#01c0c8;color:floralwhite;">
                                        <tr style="height:25px;">
                                            <th style="border-top:0px;border-bottom:0px;">Date</th>
                                            <th style="border-top:0px;border-bottom:0px;">Bitcoin</th>
                                            <th style="border-top:0px;border-bottom:0px;">amount_usd</th>
                                            <th style="border-top:0px;border-bottom:0px;">amount_usd_from</th>
                                            <th style="border-top:0px;border-bottom:0px;">amount_usd_to</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php($labels='')
                                    @php($bit_val='')
                                    @php($amt_val='')
                                    @php($amt_val_from='')
                                    @php($amt_val_to='')
                                    @foreach($bits as $bit_data)
                                        @php($labels.=("'".$bit_data['date']."',"))
                                        @php($bit_val.=("'".$bit_data['rate']."',"))
                                        @php($amt_val.=("'".$bit_data['amount_usd']."',"))
                                        @php($amt_val_from.=("'".$bit_data['amount_usd_from']."',"))
                                        @php($amt_val_to.=("'".$bit_data['amount_usd_to']."',"))
                                        <tr>
                                            <td> {{ $bit_data['date'] }}</td>
                                            <td> {{ $bit_data['rate'] }}</td>
                                            <td> {{ $bit_data['amount_usd'] }}</td>
                                            <td> {{ $bit_data['amount_usd_from'] }}</td>
                                            <td> {{ $bit_data['amount_usd_to'] }}</td>
                                        </tr>
                                    @endforeach 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <div class="col-lg-6 col-md-12">
                        <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Data Table</h4>
                                    <div class="table-responsive m-t-40">
                                            <canvas id="chLine"></canvas>
                                    </div>
                                </div>
                        </div>

                        <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Data Table</h4>
                                    <div class="table-responsive m-t-40">
                                            <canvas id="chLine1"></canvas>
                                    </div>
                                </div>
                        </div>


                        <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Data Table</h4>
                                    <div class="table-responsive m-t-40">
                                            <canvas id="chLine2"></canvas>
                                    </div>
                                </div>
                        </div>                            
                </div>
            </div>
        </div>
    </div>
    <script>
        $( document ).ready(function() {
			$('#myTable').DataTable({
				"displayLength": 10,
				"bFilter": false,
				"bInfo": false,
				"bLengthChange" : false,
		    });

            var ctx = document.getElementById('chLine').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [<?php echo $labels;?>],
                    datasets: [{
                        label: "bitcoin",
                        yAxisID: 'A',
                        data:[<?php echo $bit_val;?>],
                        backgroundColor: [
                            'rgba(0, 255, 0, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 0, 0, 1)'
                        ],
                        borderWidth: 2
                    },
                    {
                        label: "Amount_Usd",
                        yAxisID: 'B',
                        data:[<?php echo $amt_val;?>],
                        backgroundColor: [
                            'rgba(255, 0, 0, 0.2)',
                        ],
                        borderColor: [
                            'rgba(0, 255, 0, 1)'
                        ],
                        borderWidth:2
                    }]
                },
                options: {
                scales: {
                    yAxes: [{
                        id: 'A',
                        type: 'linear',
                        position: 'left',
                        scaleLabel: {
                            display: true,
                            labelString: 'Bitcoin'
                        }
                    }, {
                        id: 'B',
                        type: 'linear',
                        position: 'right',
                        scaleLabel: {
                            display: true,
                            labelString: 'Amount_Usd'
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

            var year=['a','a','a','a','a','a','a'];
            ctx1 = document.getElementById('chLine1').getContext('2d');
            var myChart1 = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels:  [<?php echo $labels;?>],
                    datasets: [{
                        label: "bitcoin",
                        yAxisID: 'A',
                        data:[<?php echo $bit_val;?>],
                        backgroundColor: [
                            'rgba(0, 255, 0, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 0, 0, 1)'
                        ],
                        borderWidth: 2
                    },
                    {
                        label: "Amount_Usd_from",
                        yAxisID: 'B',
                        data:[<?php echo $amt_val_from;?>],
                        backgroundColor: [
                            'rgba(255, 0, 0, 0.2)',
                        ],
                        borderColor: [
                            'rgba(0, 255, 0, 1)'
                        ],
                        borderWidth:2
                    }]
                },
                options: {
                scales: {
                    yAxes: [{
                        id: 'A',
                        type: 'linear',
                        position: 'left',
                        scaleLabel: {
                            display: true,
                            labelString: 'Bitcoin'
                        }
                    }, {
                        id: 'B',
                        type: 'linear',
                        position: 'right',
                        scaleLabel: {
                            display: true,
                            labelString: 'Amount_Usd_from'
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
                    labels:  [<?php echo $labels;?>],
                    datasets: [{
                        label: "bitcoin",
                        yAxisID: 'A',
                        data:[<?php echo $bit_val;?>],
                        backgroundColor: [
                            'rgba(0, 255, 0, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 3
                    },
                    {
                        label: "Amount_Usd",
                        yAxisID: 'B',
                        data:[<?php echo $amt_val_to;?>],
                        backgroundColor: [
                            'rgba(255, 0, 0, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth:3
                    }]
                },
                options: {
                scales: {
                    yAxes: [{
                        id: 'A',
                        type: 'linear',
                        position: 'left',
                        scaleLabel: {
                            display: true,
                            labelString: 'Bitcoin'
                        }
                    }, {
                        id: 'B',
                        type: 'linear',
                        position: 'right',
                        scaleLabel: {
                            display: true,
                            labelString: 'Amount_Usd'
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
        });      
    </script>
@stop

