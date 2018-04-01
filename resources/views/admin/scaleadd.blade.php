@extends("admin.default")

@section("css")
    <!-- DataTables Responsive CSS -->
    <link href="{{url('')}}/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Social Buttons CSS -->
    <link href="{{url('')}}/vendor/bootstrap-social/bootstrap-social.css" rel="stylesheet">
@endsection

@section("js")
<!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="{{url('')}}/vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="{{url('')}}/vendor/datatables-responsive/dataTables.responsive.js"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script type="text/javascript">
        $(document).ready(function(){
            // 設定構面數量
            setDimension($("#dimension").val());
            $("#dimension").change(function(){
                setDimension($("#dimension").val());
            })
            $("#scaleform").submit(function(e){
                e.preventDefault();
                console.log($('input[name="_token"]').val());
                var data = objectifyForm($(this).serializeArray());
                data.author="tmp";
                console.log(data);
                $.ajax({
                    type:'post',
                    url:'../../Scale',
                    data:data,
                    success:function(r){
                        alert(r.msg);
                        window.location.href="../scale";
                    }
                })
            })
        })
        function setDimension(count){
            $("#dimensionArea").empty();
            for (var i = 1; i <= count; i++) {
                $("#dimensionArea").append('<div id="dimensionArea" class="form-group"><label>構面'+i+'</label><input name="dimension" class="form-control" required=""></div>');   
            }
        }
        function objectifyForm(formArray) {//serialize data function
                var returnArray=[];
                var formObject = {};
                for (var i = 0; i < formArray.length; i++){
                        if(formArray[i]['name']!=tmp)
                            formObject[formArray[i]['name']] = formArray[i]['value'];
                        else
                            formObject[formArray[i]['name']] += ","+formArray[i]['value'];
                        var tmp =formArray[i]['name'];
                }
                return formObject;
        }
    </script>
@endsection

@section("page-wrapper")
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">新增量表</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            量表資料
                        </div>
                        <div class="panel-body">
                            <form role="form" id="scaleform">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>量表名稱</label>
                                                <input name="name" class="form-control" required="">
                                            </div>
                                            <div class="form-group">
                                                <label>等第</label>
                                                <select name="level" class="form-control">
                                                    <option value="5">5等第</option>
                                                    <option value="7">7等第</option>
                                                    <option value="9">9等第</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>構面數量</label>
                                                <select id="dimension" class="form-control">
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                    <option>5</option>
                                                    <option>6</option>
                                                    <option>7</option>
                                                    <option>8</option>
                                                    <option>9</option>
                                                </select>
                                            </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div id="dimensionArea" class="form-group"></div>
                                        <div class="col-lg-1 col-lg-offset-9">
                                            <button type="submit" class="btn btn-primary">建立量表</button>
                                        </div>

                                    </div>
                                </div>
                            </form>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
@endsection