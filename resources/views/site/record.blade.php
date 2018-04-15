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
    </div>
@endsection
@section('js')
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
			console.log(val.created_at);
			$.each(val.score,function(sindex,sval){
				td+='<td>'+sval+'</td>';
			})
			td+='<td>'+val.created_at+'</td>';
			var tr = '<tr>'+td+'</tr>';
			$('#tbody').append(tr);
		})
	}
	// for (var i = 0; i < 10; i++) {
	// 	$('#tbody').append('<tr><td>'+i+'</td><td>'+i+'</td><td>'+i+'</td><td>'+i+'</td><td>'+i+'</td><td>'+i+'</td></tr>')
	// }
</script>
@endsection