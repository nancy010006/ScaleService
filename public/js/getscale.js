$(document).ready(function(){
	getData();
})
function getData(){
	$.ajax({
		type:'get',
		url:'../Scales',
		success:function(r){
			var i=1;
			$.each(r,function(key,value){
				$("#tbody").append("<tr><td>"+i+"</td><td>"+value.name+"</td><td>"+value.level+"</td><td>"+value.created_at+"</td><td><button type='button' class='btn btn-primary btn-circle'><i class='fa fa-pencil'></i></button> <button type='button' class='btn btn-danger btn-circle'><i class='fa fa-times'></i></button></td></tr>");
				i++;
			})
		}
	})
}