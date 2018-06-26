
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
    <div class="card card-register mx-auto mt-5">
      <div class="card-header">註冊新帳號</div>
      <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="post" action="{{url('')}}/register">
          <div class="form-group">
                <label for="exampleInputName">姓名</label>
                <input name="name" class="form-control" id="exampleInputName" type="text" aria-describedby="nameHelp" required="" value="{{old('name')}}">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Email</label>
            <input name="email" class="form-control" id="exampleInputEmail1" type="email" aria-describedby="emailHelp" required="">
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-md-6">
                <label for="exampleInputPassword1">密碼</label>
                <input name="password" class="form-control" id="exampleInputPassword1" type="password" required="">
              </div>
              <div class="col-md-6">
                <label for="exampleConfirmPassword">確認密碼</label>
                <input name="password_confirmation" class="form-control" id="exampleConfirmPassword" type="password" required="">
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-md-6">
                <label for="exampleConfirmPassword">生日</label>
                <input name="birthday" class="form-control" id="exampleConfirmPassword" type="date" required="" value="{{old('birthday')}}">
              </div>
              <div class="col-md-6">
              <label>地區</label>
                <select name="area" class="form-control" required="">
                    <option value="{{old('area')}}">{{old('area')}}</option>
                    <option value="北部">北部</option>
                    <option value="中部">中部</option>
                    <option value="南部">南部</option>
                    <option value="東部">東部</option>
                    <option value="離島">離島</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="form-row">
            <div class="col-md-3">
                <label class="radio-inline">
                <input type="radio" name="sex" id="optionsRadiosInline1" value="男性" required="">男性
                </label>
              </div>
              <div class="col-md-3">
                <label class="radio-inline">
                <input type="radio" name="sex" id="optionsRadiosInline2" value="女性" required="">女性
                </label>
              </div>
              <div class="col-md-6">
              <label>職業</label>
                <select name="job" class="form-control" required="">
                    <option value="{{old('job')}}">{{old('job')}}</option>
                    <option value="工">工</option>
                    <option value="商">商</option>
                    <option value="農">農</option>
                    <option value="東部">東部</option>
                    <option value="離島">離島</option>
                </select>
              </div>

            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block">註冊</button>
          @csrf
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="{{url('')}}/site/login">登入頁面</a>
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
