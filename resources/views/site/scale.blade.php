@extends('site.default')
@section('js')
<script type="text/javascript">
	var ScaleData;
	var pd = 1;
	var i = 1;
	$(document).ready(function(){
		init();
				console.log(ScaleData);
		$.each(ScaleData.dimensions,function(index,val){
			var Qnum = 1;
			$('#page_divide').append('<div id="divide'+pd+'" name="'+pd+'"></div>');
			$('#divide'+pd).append('<br><div class="alert alert-warning"><ul class="list-group"><strong>'+val.name+'</strong></ul></div>');
			$.each(val.questions,function(qindex,qval){
				$('#divide'+pd).append('<li class="list-group-item"><p>'+(Qnum++)+'.'+qval.description+'</p></li>');
				for (var i = 1; i <= ScaleData.level; i++) {
					$('#divide'+pd).append(i+'<input type="radio" name="'+ScaleData.dimensions[1].name+'"id="'+ScaleData.dimensions[1].name+i+'">');
				}
			})
			pd++;
		})
		$('div[id^="divide"]').hide();
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
	function page_down(){
		$('div[id^="divide"]').hide();
		$('#divide'+i).show();
		console.log(i);
		if(i == ScaleData.dimensions.length){
			$('#down').hide();
		}else
			i++;
	}
	function page_up(){
	    --i;
		$('div[id^="divide"]').hide();
		$('#divide'+i).show();
		console.log(i);
		// if(i == 1){
		// 	$('#up').hide();
		// }
	}
</script>
@endsection
@section('content')
<div id="page_divide">
	<div id="questions" class="container">
</div></div>
<button id="up" onclick="page_up()"><img src="{{url('')}}/img/left-arrow.png"></button>
<button id="down" onclick="page_down()"><img src="{{url('')}}/img/right-arrow.png"></button>
<!-- <ul class="pagination">
	<li><a href="#">&laquo;</a></li>
	<li><a href="#">1</a></li>
	<li><a href="#">2</a></li>
	<li><a href="#">3</a></li>
	<li><a href="#">4</a></li>
	<li><a href="#">5</a></li>
	<li><a href="#">&raquo;</a></li>
</ul> -->
@endsection
@section('css')
@endsection