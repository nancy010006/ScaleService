<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SB Admin - Start Bootstrap Template</title>
  <link rel="shortcut icon" href="{{url('')}}/favicon.ico" type="image/x-icon" />
  <!-- Bootstrap core CSS-->
  <link href="{{url('')}}/vendor2/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="{{url('')}}/vendor2/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="{{url('')}}/css/sb-admin.css" rel="stylesheet">
  <link href="{{url('')}}/css/creative.min.css" rel="stylesheet">

  <style type="text/css">
    strong{
      color: red;
    }
  </style>
</head>

<body class="bg-dark">
  <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="{{url('')}}/site">ScaleService</a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
      </div>
    </div>
  </nav>
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">
        <form method="POST" action="{{ url('site/login') }}">
          <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input class="form-control" id="exampleInputEmail1" type="email" name="email" aria-describedby="emailHelp" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input class="form-control" id="exampleInputPassword1" type="password" name="password" placeholder="Password">
          </div>
          <div class="form-group">
            <div class="form-check">
              <label class="form-check-label">
                <!-- <input class="form-check-input" type="checkbox"> Remember Password</label> -->
            </div>
            @csrf
          @if ($errors->has('msg'))
              <span >
                  <strong>{{ $errors->first('msg') }}</strong>
              </span>
          @endif
          </div>
          <button class="btn btn-primary btn-block" type="submit">登入</button>
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="{{url('')}}/site/register">註冊</a>
          <!-- <a class="d-block small" href="forgot-password.html">忘記密碼</a> -->
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="{{url('')}}/vendor2/jquery/jquery.min.js"></script>
  <script src="{{url('')}}/vendor2/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="{{url('')}}/vendor2/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>
