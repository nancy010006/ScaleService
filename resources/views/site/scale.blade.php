@extends('site.default')
@section('js')
<script type="text/javascript">
	var ScaleData;
	var pd = 1;
	var i = 1;
	var Qnum = 1;
	$(document).ready(function(){
		init();
		console.log(ScaleData.dimensions.length);
		$('#submit').hide();
		$.each(ScaleData.dimensions,function(index,val){
			$('#page_divide').append('<div id="divide'+pd+'" name="'+pd+'"></div>');
			$('#divide'+pd).append('<br><div class="alert alert-warning"><ul class="list-group"><strong>'+val.name+'</strong></ul></div>');
			$.each(val.questions,function(qindex,qval){
				$('#divide'+pd).append('<li class="list-group-item"><p>'+(Qnum++)+'.'+qval.description+'</p></li>');
				for (var i = 1; i <= ScaleData.level; i++) {
					$('#divide'+pd).append(i+'<input type="radio"  name="'+ScaleData.dimensions[index].name+(qindex)+'"id="'+ScaleData.dimensions[index].name+i+'"'+'value="'+i+'">');
				}
			})
			pd++;
		})
		$('div[id^="divide"]').hide();
		test();
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
		if(check_answer()==0)
			return;
		$('div[id^="divide"]').hide();
		$('#divide'+i).show();
		$('#up').show();
		$('#down').show();
		// console.log("d"+i);
		if(i == ScaleData.dimensions.length){
			$('#down').hide();
			$('#submit').show();
		 }//else if(i == 1){
		// 	i++;
		// 	$('#up').hide();
		// }
		else{
			++i;
			$('#submit').hide();
		}
	}
	function page_up(){
	    --i;
		$('div[id^="divide"]').hide();
		$('#divide'+i).show();
		console.log("u"+i);
		$('#up').show();
		$('#down').show();
		$('#submit').hide();
		if(i == 1){
			$('#up').hide();
		}
	}
	function check_answer(){
		$('p[class="warn"]').remove();
		var title = $($('strong')[i-2]).text();
		var count = $('#divide'+(i-1)+' li').length;
		for(var j = 0; j < count; j++){
			var input = $('input[name="'+title+j+'"]');
			var check = input.is(":checked");
			if(check==false){
				var li = input.prev("li");
				li.append('<p class="warn">請填寫這個題目</p>');
				input.focus();
				return 0;
			}
		}
		return 1;
	}
	function test(){
		$('input').attr('checked',true);
	}
	function submit(){
		// alert(123);
		var count = 0;
		var returnObj={"respose":[]};
		var qidarr = [];
		// obj.respose = question_arr;
		// console.log(ScaleData.dimensions.length);
		for (var i = 0; i < ScaleData.dimensions.length; i++) {
			for (var j = 0; j <ScaleData.dimensions[i].questions.length; j++) {
				qidarr.push(ScaleData.dimensions[i].questions[j].id)
				count++;
			}
		}
		$.each($('input[type="radio"]:checked'),function(index,val){
			var tmp ={"qid":qidarr[index],"val":$(val).val()};
			returnObj.respose.push(tmp);
		})
		console.log(JSON.stringify(returnObj));
	}
</script>
@endsection
@section('content')
<!-- <form id="myform"> -->
<div class="row">
<div class="col-sm-2">
<button id="up" class="btn btn-light" onclick="page_up()"><p class="fa fa-angle-left"></p></button></div>
<div class="col-sm-8" id="page_divide">
	<div id="questions" class="container">
</div></div>
<!-- </form> -->
<div class="col-sm-2">
<button id="down" class="btn btn-light" onclick="page_down()"><p class="fa fa-angle-right"></p></button></div>
<input class="btn btn-outline-primary" align="center" style="width:100%" id="submit" type="submit" name="SUBMIT" onclick="submit()">
@endsection
@section('css')
<style type="text/css">
	.warn{
		color:red;
	}
	input[type="radio"]{
		width: 30px;
		height: 30px;
		margin: 5px;
	}
</style>
@endsection