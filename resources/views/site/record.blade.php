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
          <div id="container"></div>
        </div>
        <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
      </div>
@endsection
@section('css')
<style type="text/css">
	.highcharts-label{
		display:none;
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
	$(document).ready(function(){
		init();
		console.log(scale);
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
	function addToTable(){
		//放THEAD和TFOOT
		var allD = scale[0].score;
		var th='';
		$.each(allD,function(index,val){
			tdLength+=1;
			th+='<th>'+index+'</th>';
		})
		th+='<th>填寫時間</th>';
		var tr = '<tr>'+th+'</tr>';
		$('#thead').append(tr);
		$('#tfoot').append(tr);

		//放TD
		$.each(scale,function(index,val){
			var td = '';
			$.each(val.score,function(sindex,sval){
				td+='<td>'+sval+'</td>';
			})
			td+='<td>'+val.created_at+'</td>';
			var tr = '<tr>'+td+'</tr>';
			$('#tbody').append(tr);
		})
	}
	function makeChart(){
		var xAxis = [];
		$.each(scale,function(index,val){
			xAxis.push(val.created_at);
		})
		var allD = scale[0].score;
		var data = [];
		$.each(allD,function(index,val){
			var tmp = {name:index,data:[]};
			$.each(scale,function(sindex,sval){
				// console.log();
				tmp.data.push(sval.score[index])
			})
			data.push(tmp);
		})
		Highcharts.chart('container', {
 
		    title: {
		        text: '歷史紀錄'
		    },

		    yAxis: {
		        title: {
		            text: '分數'
		        }
		    },

		    xAxis:{
		    	categories:xAxis
		    },
		    series: data,

		    responsive: {
		        rules: [{
		            condition: {
		                maxWidth: 500
		            },
		            chartOptions: {
		                legend: {
		                    layout: 'horizontal',
		                    align: 'center',
		                    verticalAlign: 'bottom'
		                }
		            }
		        }]
		    }

		});
	}
</script>
@endsection