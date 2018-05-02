@extends('site.default')
@section('content')
<div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Tables</li>
      </ol>
      <span class="highest descr">偏高</span>
  	  <span class="lowest descr">偏低</span>
      <!-- Example DataTables Card-->
      <div class="card mb-3">
      	
        <div class="card-header">
          <i class="fa fa-table"></i> {{$name}}</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead id="thead">
              </thead>
              <tfoot id="tfoot">
              </tfoot>
              <tbody id="tbody">
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
      </div>
      <!-- Area Chart Example-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-area-chart"></i> Area Chart Example</div>
        <div class="card-body">
			<canvas id="canvas"></canvas>
        </div>
        <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
      </div>

@endsection
@section('css')
<style type="text/css">
	.highcharts-label{
		/*display:none;*/
	}
	.highest{
		background-color: #ff2d2d;
	}
	.lowest{
		background-color: #93ff93;
	}
	.descr{
		display: inline;
		width: 40px;
		margin: 10px 10px;
	}
</style>
@endsection
@section('js')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script type="text/javascript">
	var token;
	var scale;
	var tdLength = 0;
	var stdAndAvg;
	$(document).ready(function(){
		init();
		// console.log(scale);
	    getStdAvg();
		addToTable();
		$("#dataTable").dataTable({
	        "order": [[ tdLength, "desc" ]]
	    });
	    makeChart();
		// $.each(Scales,function(index,val){
		// 	// console.log(val);
		// 	addScale(val);
		// })
	})
	function init(){
		$.ajax({
			url:'{{url('')}}/site/token',
			type:'get',
			async:false,
			success:function(r){
				token = r;
			}
		})
		$.ajax({
			url:'{{url('')}}/api/historyResponse/{{$id}}?api_token='+token,
			type:'get',
			async:false,
			success:function(r){
				scale = r;
			}
		})
	}
	function getStdAvg(){
		timearr = [];
		$.each(scale,function(index,val){
			timearr.push(val.created_at);
		});
		$.ajax({
			url:'{{url('')}}/api/getstd/{{$id}}?time='+timearr,
			type:'get',
			async:false,
			success:function(r){
				console.log(r);
				stdAndAvg = r;
			}
		})
	}
	function addToTable(){
		//放THEAD和TFOOT
		var allD = scale[0].score;
		var th='';
		$.each(allD,function(index,val){
			th+='<th colspan="2">'+index+'</th>';
		})
		th+='<th rowspan="2">填寫時間</th>';
		var tr = '<tr>'+th+'</tr>';
		$('#thead').append(tr);
		// $('#tfoot').append(tr);

		var th='';
		$.each(allD,function(index,val){
			tdLength+=1;
			th+='<th>得分</th>';
			th+='<th>平均</th>';
		})
		var tr = '<tr>'+th+'</tr>';
		$('#thead').append(tr);

		//放TD
		$.each(scale,function(index,val){
			var td = '';
			$.each(val.score,function(sindex,sval){
				console.log(stdAndAvg);
				avg = stdAndAvg[index].avg[sindex];
				std = stdAndAvg[index].std[sindex]
				if(sval>avg+std){
					td+='<td class="highest">'+sval+'</td>';
					td+='<td>'+avg.toString().slice(0,4)+'</td>';
				}
				else if(sval<avg-std){
					td+='<td class="lowest">'+sval+'</td>';
					td+='<td>'+avg.toString().slice(0,4)+'</td>';
				}
				else{
					td+='<td>'+sval+'</td>';
					td+='<td>'+avg.toString().slice(0,4)+'</td>';
				}
			})
			td+='<td>'+val.created_at.slice(0,10)+'</td>';
			var tr = '<tr>'+td+'</tr>';
			$('#tbody').append(tr);
		})
	}
	function makeChart(){
		window.chartColors =[
			'rgb(255, 99, 132)',
			'rgb(255, 159, 64)',
			'rgb(255, 205, 86)',
			'rgb(75, 192, 192)',
			'rgb(54, 162, 235)',
			'rgb(153, 102, 255)',
			'rgb(201, 203, 207)'
		];
		var xAxis = [];
		$.each(scale,function(index,val){
			xAxis.push(val.created_at.slice(0,10));
		})
		var allD = scale[0].score;
		var data = [];
		var i = 0;
		$.each(allD,function(index,val){
			var tmp = {label:index,data:[],backgroundColor: window.chartColors[i%7],borderColor: window.chartColors[i%7],fill: false,pointHitRadius: 20};
			if(index=="total")
				tmp.borderDash= [5, 5];
			i++;
			$.each(scale,function(sindex,sval){
				// console.log();
				tmp.data.push(sval.score[index])
			})
			data.push(tmp);
		})
		console.error(data);
		var config = {
			type: 'line',
			data: {
				labels: xAxis,
				datasets: data
			},
			options: {
				//拿掉=曲線
				elements: {
		            line: {
		                tension: 0, // disables bezier curves
		            }
		        },
		        //上面拿掉曲線
				responsive: true,
				legend: {
					position: 'bottom',
				},
				hover: {
					mode: 'index'
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: '時間'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: '得分'
						}
					}]
				},
				title: {
					display: true,
					text: '歷程記錄'
				}
			}
		};
		var ctx = document.getElementById('canvas').getContext('2d');
		window.myLine = new Chart(ctx, config);
	}
</script>
@endsection