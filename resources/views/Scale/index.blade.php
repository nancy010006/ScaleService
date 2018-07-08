<!DOCTYPE html>
<html>
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="{{url('')}}/js/scale.js" type="text/javascript"></script>
	<title></title>
</head>
<body>
	    <form action="" method="POST">
			<input type="submit" value="Delete">
		</form>
		<button id="">update</button>
			<input type="submit" value="Update">
		</form>

	<form action="Scale" method="POST">
		@csrf
		<input type="text" name="name" placeholder="量表名稱">
		<input type="text" name="dimension" placeholder="構面">
		<input type="text" name="level" placeholder="等第">
		<input type="text" name="author" placeholder="作者">
		<input type="submit">
	</form>

	<form id="scale">
		@csrf
		<input type="text" name="name" placeholder="量表名稱">
		<input type="text" name="dimension" placeholder="構面">
		<input type="text" name="level" placeholder="等第">
		<input type="text" name="author" placeholder="作者">
		<input type="submit">
	</form>

	<div id="data"></div>
	
</body>
</html>