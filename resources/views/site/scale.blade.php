@extends('site.default')
@section('js')
<script type="text/javascript">
	var ScaleData;
	$(document).ready(function(){
		init();
		console.log(ScaleData);
		$.each(ScaleData.dimensions,function(index,val){
			var Qnum = 1;
			// var dp = 1;
			// console.log(dp);
			// var create_page = document.createElement('div');
			// create_page.setAttribute("id",'div'+dp);
			// dp++;
			$('#questions').append('<br><div class="alert alert-warning"><ul class="list-group"><strong>'+val.name+'</strong></ul></div>');
			$.each(val.questions,function(qindex,qval){
				// console.log(qval);
				$('#questions').append('<li class="list-group-item"><p>'+(Qnum++)+'.'+qval.description+'</p></li>');
				for (var i = 1; i <= ScaleData.level; i++) {
					$('#questions').append(i+'<input type="radio" name="'+ScaleData.dimensions[1].name+'"id="'+ScaleData.dimensions[1].name+i+'">');
				}
			})
		})
	})
	function init(){
		$.ajax({
			url:'{{url('')}}/Scale/{{$id}}',
			type:'get',
			async:false,
			success:function(r){
				ScaleData = r;
			}
		})
	}
</script>
@endsection
@section('content')
<div id="questions" class="container"></div>
@endsection
@section('css')
@endsection