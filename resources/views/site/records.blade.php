@extends('site.default')
@section('content')
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Cards</li>
  </ol>
  <h1>Cards</h1>
  <hr>
  <!-- Icon Cards-->
  <div class="row" id="ScaleArea">
  </div>
</div>
@endsection
@section('js')
	<script type="text/javascript">
		var token;
		var Scales;
		var ColorArray = ["primary","warning","success","danger"];
		var ColorCount = 0;
		$(document).ready(function(){
			init();
			$.each(Scales,function(index,val){
				// console.log(val);
				addScale(val);
			})
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
				url:'{{url('')}}/api/historyResponses?api_token='+token,
				type:'get',
				async:false,
				success:function(r){
					Scales = r;
				}
			})
		}
		function addScale(data){
			console.log(data);
			var Scale = '<div class="col-xl-3 col-sm-6 mb-3"><div class="card text-white bg-'+ColorArray[ColorCount%4]+' o-hidden h-100"><div class="card-body"><div class="card-body-icon"><i class="fa fa-fw fa-book"></i></div><div class="mr-5">'+data.name+'</div></div><a class="card-footer text-white clearfix small z-1" href="{{url('')}}/site/scales/'+data.id+'"><span class="float-left">'+data.responses+'筆記錄</span><span class="float-right"><i class="fa fa-angle-right"></i></span></a></div></div>';
			$("#ScaleArea").append(Scale);
			ColorCount++;
		}
	</script>
@endsection