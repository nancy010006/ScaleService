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
        var nowdimension = 0;
        $(document).ready(function(){
            // 設定構面數量
            getScaleData();
        })
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
        function setDimension(count){
            $("#dimensionArea").empty();
            for (var i = 1; i <= count; i++) {
                $("#dimensionArea").append('<div id="dimensionArea" class="form-group"><label>構面'+i+'</label><input id="d'+i+'" name="dimension" class="form-control" required=""></div>');   
            }
        }
        function getScaleData(){
            var scaleid ={{$scale->id}};
            $.ajax({
                type:'get',
                url:'../../../Scale/'+scaleid,
                success:function(r){
                    console.log(r);
                    $("#name").val(r.scale.name);
                    var Dlength = r.Dimension.length;
                    setDimension(Dlength);
                    for (var i = 0; i < Dlength; i++) {
                        $("#d"+(i+1)).val(r.Dimension[i].name);
                    }
                    $("#dimension").val(r.Dimension.length);
                    $("#level").val(r.scale.level);
                    for (var i = 0; i < Dlength; i++) {
                        $("#page-wrapper").append('<div class="row"><div class="col-lg-12"><div class="panel panel-default"><div class="panel-heading">'+r.Dimension[i].name+'</div><div class="panel-body"><form role="form" id="scaleform">@csrf<div class="row"><div class="col-lg-12"><div id="qarea'+i+'" class="form-group"></div></div></div></form></div></div></div></div>');
                    }
                    for (var i = 0; i < Dlength; i++) {
                        // console.log(r.Dimension[i].questions.length);
                        for (var j = 0; j <r.Dimension[i].questions.length; j++) {
                            var question = '<label>'+(j+1)+'.</label><input id="d'+(i+1)+'q'+(j+1)+'" name="name" class="form-control" value="'+r.Dimension[i].questions[j]+'" required="">';
                            $("#qarea"+i).append(question);
                        }
                    }
                }
            })
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
                    <h1 class="page-header">編輯量表</h1>
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
                                                <input id="name" name="name" class="form-control" required="">
                                            </div>
                                            <div class="form-group">
                                                <label>等第</label>
                                                <select id="level" name="level" class="form-control">
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
                                            <button type="submit" class="btn btn-primary">確定修改</button>
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
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">各構面題庫</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            測試用
                        </div>
                        <div class="panel-body">
                            <form role="form" id="scaleform">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>-1.</label>
                                            <input id="name" name="name" class="form-control" required="">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
@endsection