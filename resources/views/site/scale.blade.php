@extends('site.default')
@section('js')
<script type="text/javascript">
	var ScaleData;
	var pd = 1;
	var i = 0;
	var Qnum = 1;
	var dimensionsL;
	$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	$(document).ready(function(){
		init();
		$('.jumbotron').html('<h1>您現在正在填寫'+ScaleData.name+'問卷 1/'+dimensionsL+'</h1>');
		$('#submit').hide();
		$.each(ScaleData.dimensions,function(index,val){
			$('#page_divide').append('<div id="divide'+pd+'" name="'+pd+'"></div>');
			$('#divide'+pd).append('<div class="alert alert-warning"><ul class="list-group"><strong>'+val.name+'</strong></ul></div>');
			$.each(val.questions,function(qindex,qval){
				$('#divide'+pd).append('<br><li class="list-group-item"><p>'+(Qnum++)+'.'+qval.description+'</p></li>');
				for (var i = 1; i <= ScaleData.level; i++) {
					$('#divide'+pd).append(i+'<input type="radio"  name="'+ScaleData.dimensions[index].name+(qindex)+'"id="'+ScaleData.dimensions[index].name+i+'"'+'value="'+i+'">');
				}
			})
			pd++;
		})
		$('#up').hide();
		$('div[id^="divide"]').hide();
		$('#divide1').show();
		i++;
		test();
	})
	function init(){
		$.ajax({
			url:'{{url('')}}/Scale/{{$id}}',
			type:'get',
			async:false,
			success:function(r){
				ScaleData = r;
				dimensionsL = r.dimensions.length;
			}
		})
	}
	function page_down(){
		if(check_answer()==0)
			return;
		i++;
		$('.jumbotron').html('<h1>您現在正在填寫'+ScaleData.name+'問卷 '+i+'/'+dimensionsL+'</h1>');
		$('div[id^="divide"]').hide();
		$('#divide'+i).show();
		$('#up').show();
		$('#down').show();
		if(i == ScaleData.dimensions.length){
			$('#down').hide();
			$('#submit').show();
		 }//else if(i == 1){
		else{
			$('#submit').hide();
		}
		// console.log(i);
		$("html, body").animate({scrollTop:0}, 0); 
	}
	function page_up(){
	    i--;
		$('.jumbotron').html('<h1>您現在正在填寫'+ScaleData.name+'問卷 '+i+'/'+dimensionsL+'</h1>');
		$('div[id^="divide"]').hide();
		$('#divide'+i).show();
		$('#up').show();
		$('#down').show();
		$('#submit').hide();
		if(i == 1){
			$('#up').hide();
		}
		$("html, body").animate({scrollTop:0}, 0); 
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
		var count = 0;
		var returnObj={"response":[]};
		var qidarr = [];
		for (var i = 0; i < ScaleData.dimensions.length; i++) {
			for (var j = 0; j <ScaleData.dimensions[i].questions.length; j++) {
				qidarr.push(ScaleData.dimensions[i].questions[j].id)
				count++;
			}
		}
		$.each($('input[type="radio"]:checked'),function(index,val){
			var tmp ={"qid":qidarr[index],"val":$(val).val()};
			returnObj.response.push(tmp);
		})
		returnObj.scaleid=ScaleData.id;
		$.ajax({
			url:'{{url('')}}/Response',
			type:'post',
			data:returnObj,
			async:false,
			success:function(r){
				alert(r.msg)
            	window.location.href='{{url("")}}/site/records';
			}
		})
	}
</script>
@endsection
@section('content')
<!-- <form id="myform"> -->
<div class="jumbotron">
 </div>
<div class="row">
<div class="col-sm-1"></div>
<div class="col-sm-10" id="page_divide">
	<div id="questions" class="container">
</div></div>
<div class="col-sm-1"></div>
</div>
<!-- </form> -->
<div class="row">
<div align="middle" class="col">
<button id="up" style="width: 50%" class="btn btn-light" onclick="page_up()" align="middle"><p class="fa fa-angle-left fa-4x"><br><span style="font-size: 15px;">上一頁</span></p></button></div>
<div align="middle" class="col">
<button id="down" style="width: 50%" class="btn btn-light" onclick="page_down()"><p class="fa fa-angle-right fa-4x" ><br><span style="font-size: 15px;">下一頁</span></p></button></div>
<input class="btn btn-outline-primary btn-lg btn-block" style="width:100%" id="submit" type="submit" name="SUBMIT" onclick="submit()">
@endsection
@section('css')
<style type="text/css">
	.warn{
		color:red;
	}
	input[type="radio"]{
		width: 15px;
		height: 30px;
		margin: 10px;
	}

</style>
@endsection