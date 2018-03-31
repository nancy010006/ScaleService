$(document).ready(function(){
	getData();
	$("#insert").submit(function(e){
		e.preventDefault();
		console.log($('input[name="_token"]').val());
		console.log($('input[name="title"]').val());
		console.log({
				'_token':$('input[name="_token"]').val(),
				'title':$('input[name="title"]').val()
			});
		$.ajax({
			type:'post',
			url:'todo',
			data:{
				'_token':$('input[name="_token"]').val(),
				'title':$('input[name="title"]').val()
			},
			success:function(r){
				window.location.reload();
				console.log(r);
			}
		})
	});
	$("button[id^='title']").click(function(){
		var id = this.id.substr(5,1);
		console.log(id);
		var form = "<form id='update"+id+"'><input name='upid' type='hidden' value='"+id+"'><input name='uptitle' type='text' placeholder='title'><input type='submit' value='確認'></form>"; 
		$(this).after(form);
		$(this).remove();
		updateButton();
	})
})
function getData(){
	$.ajax({
		type:'get',
		url:'Scale/data',
		success:function(r){
			console.log(r);
			$.each(r,function(key,value){
				// console.error(value);
				$("#data").append("<p>");
				$("#data").append(value.id+" ");
				$("#data").append(value.name);
				$("#data").append("</p>");
			})
		}
	})
}
function updateButton(){
	$("form[id^='update']").submit(function(e){
		e.preventDefault();
		console.log($('input[name="uptitle"]').val());
		$.ajax({
			type:'put',
			url:'todo',
			data:{
				'_token':$('input[name="_token"]').val(),
				'title':$('input[name="uptitle"]').val(),
				'id':$('input[name="upid"]').val()
			},
			success:function(r){
				window.location.reload();
				console.log(r);
			}
		})
	})
}