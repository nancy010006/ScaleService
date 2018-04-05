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
    <script type="text/javascript">
        $(document).ready(function(){
        })
        $("#dimension").change(function(){
                setDimension($("#dimension").val());
            })
        $("#scaleform").submit(function(e){
            e.preventDefault();
            // console.log($('input[name="_token"]').val());
            var data = objectifyForm($(this).serializeArray());
            // data.author="tmp";
            console.log(JSON.stringify(data));
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
        function addD(){
            var newcount = $("#AllDimensionAreas input").length+1;
            var AddQButton = '<button onclick="addQ(this)" type="button" class="btn btn-success btn-circle"><i class="fa fa-plus"></i></button>';
            var DelDButton = '<button id="DelDB'+newcount+'" style="float:right;" onclick="delD(this,'+newcount+')" type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash-o"></i></button>';
            var input = '<input id="DimensionInput'+newcount+'" name="DimensionInput" class="form-control" required="">';
            $("#AllDimensionAreas").append('<div id="dimensionArea'+newcount+'" class="form-group"><label>構面'+newcount+'</label>'+input+'</div>');
            //有form版本
            // $("#questions").append('<form id="Dform'+newcount+'"><div class="row"><div class="col-lg-12"><div class="panel panel-default"><div class="panel-heading"><span id="DimensionName'+newcount+'"> </span>'+PlusQButton+' </div><div class="panel-body"><form role="form" id="scaleform">@csrf<div class="row"><div class="col-lg-12"><div id="qarea'+newcount+'" class="form-group"></div></div></div></form></div></div></div></div></form>');
            $("#questions").append('<div class="row"><div class="col-lg-12"><div class="panel panel-default"><div class="panel-heading"><label id="DimensionName'+newcount+'"> </label> '+AddQButton+DelDButton+' </div><div class="panel-body"><div class="row"><div class="col-lg-12"><div id="qarea'+newcount+'" class="form-group"></div></div></div></div></div></div></div>');
            $("#nowDimensionCount").text(newcount);
            $('#DimensionInput'+newcount).keyup(function(){
                $('#DimensionName'+newcount).text($('#DimensionInput'+newcount).val());
            })
        }
        function delD(self,which){
            console.log($(self));
            console.log($(self).parent().parent());
            $('#dimensionArea'+which).remove();
            $(self).parent().parent().remove();
            var newcount = $("#AllDimensionAreas input").length;
            $("#nowDimensionCount").text(newcount);

            var dimensionAreas = $('div[id*="dimensionArea"]');
            $.each(dimensionAreas,function(index,value){
                $(value).prop('id','dimensionArea'+(index+1));
                $(value).children('label').text("構面"+(index+1));
                $(value).children('input').prop('id','DimensionInput'+(index+1));
            })

            var DelDBs = $('button[id*="DelDB"]');
            $.each(DelDBs,function(index,value){
                $(value).attr('onclick','delD(this,'+(index+1)+')');
            })

            var DimensionNames = $('label[id*="DimensionName"]');
            $.each(DimensionNames,function(index,value){
                $(value).prop('id','DimensionName'+(index+1));
            })
            $('#DimensionInput'+newcount).keyup(function(){
                $('#DimensionName'+newcount).text($('#DimensionInput'+newcount).val());
            })
        }
        function addQ(self){
            var whichD = $(self).prev().attr('id').slice(13);
            var divBody = $(self).parent().parent().children()[1];
            var count  = $(divBody).children("input").length+1;
            console.log(count);
            var DelQButton = '<button style="float:right;" onclick="delQ(this)" type="button" class="btn btn-warning btn-circle"><i class="fa fa-times"></i></button>';
            var question = '<label >'+count+'.</label>'+DelQButton+'<input name="d'+whichD+'" class="form-control" required="">';
            $(divBody).append(question);

        }
        function delQ(self){
            var divBody = $(self).parent().parent().children()[1];
            $(self).prev().remove();
            $(self).next().remove();
            $(self).remove();
            var labels = $(divBody).children("label");
            $.each(labels,function(index,value){
                $(value).text((index+1)+".");
            })
        }
        function objectifyForm(formArray) {//serialize data function
                var returnArray=[];
                var formObject = {};
                for (var i = 0; i < formArray.length; i++){
                        if(formArray[i]['name']!=tmp)
                            formObject[formArray[i]['name']] = formArray[i]['value'];
                        else
                            formObject[formArray[i]['name']] += "*"+formArray[i]['value'];
                        var tmp =formArray[i]['name'];
                }
                return formObject;
        }
    </script>
@endsection

@section("page-wrapper")
<div id="page-wrapper">
            <form role="form" id="scaleform">
                @csrf
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
                                <label>量表資料</label>
                            </div>
                            <div class="panel-body">
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
                                                <button onclick="addD()" type="button" class="btn btn-success btn-circle"><i class="fa fa-plus"></i></button>
                                                <p id="nowDimensionCount">0
                                                </p>
                                            </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div id="AllDimensionAreas" class="form-group"></div>
                                        <div class="col-lg-1 col-lg-offset-9">
                                            <button type="submit" class="btn btn-primary">建立量表</button>
                                        </div>

                                    </div>
                                </div>
                                <!-- /.row (nested) -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row" id="questions">
                    <div class="col-lg-12">
                        <h1 class="page-header">各構面題庫</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </form>
        </div>
@endsection