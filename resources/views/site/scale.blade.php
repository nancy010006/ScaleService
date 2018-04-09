@extends('site.default')
@section('js')
<script type="text/javascript">
	var ScaleData;
	$(document).ready(function(){
		init();
		console.log(ScaleData);
		$.each(ScaleData.dimensions,function(index,val){
			var Qnum = 1;
			$('#questions').append('<strong>'+val.name+'</strong>');
			$.each(val.questions,function(qindex,qval){
				// console.log(qval);
				$('#questions').append('<p>'+(Qnum++)+'.'+qval.description+'</p>');
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
<div id="questions"></div>
@endsection