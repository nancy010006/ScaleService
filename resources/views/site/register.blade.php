
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
                    <option value="臺北市">臺北市</option>
                    <option value="新北市">新北市</option>
                    <option value="桃園市">桃園市</option>
                    <option value="臺中市">臺中市</option>
                    <option value="臺南市">臺南市</option>
                    <option value="高雄市">高雄市</option>
                    <option value="基隆市">基隆市</option>
                    <option value="新竹市">新竹市</option>
                    <option value="嘉義市">嘉義市</option>
                    <option value="新竹縣">新竹縣</option>
                    <option value="苗栗縣">苗栗縣</option>
                    <option value="彰化縣">彰化縣</option>
                    <option value="南投縣">南投縣</option>
                    <option value="雲林縣">雲林縣</option>
                    <option value="嘉義縣">嘉義縣</option>
                    <option value="屏東縣">屏東縣</option>
                    <option value="宜蘭縣">宜蘭縣</option>
                    <option value="花蓮縣">花蓮縣</option>
                    <option value="臺東縣">臺東縣</option>
                    <option value="澎湖縣">澎湖縣</option>
                    <option value="金門縣">金門縣</option>
                    <option value="連江縣">連江縣</option>
                    <option value="其他">其他</option>
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
                    <option value="農牧業">農牧業</option>
                    <option value="漁業">漁業</option>
                    <option value="木材、森林業">木材、森林業</option>
                    <option value="礦業、採石業">礦業、採石業</option>
                    <option value="交通運輸業">交通運輸業</option>
                    <option value="餐旅業">餐旅業</option>
                    <option value="建築工程業">建築工程業</option>
                    <option value="製造業">製造業</option>
                    <option value="新聞、出版、廣告業">新聞、出版、廣告業</option>
                    <option value="娛樂業">娛樂業</option>
                    <option value="文教">文教</option>
                    <option value="宗教">宗教</option>
                    <option value="公共事業">公共事業</option>
                    <option value="商業">商業</option>
                    <option value="金融業">金融業</option>
                    <option value="服務業">服務業</option>
                    <option value="家庭管理">家庭管理</option>
                    <option value="治安人員">治安人員</option>
                    <option value="軍人">軍人</option>
                    <option value="體育">體育</option>
                    <option value="資訊">資訊</option>
                    <option value="學生">學生</option>
                    <option value="其它">其它</option>
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
