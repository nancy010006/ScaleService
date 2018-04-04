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
        var scaledata;
        var nowdimension = 0;
        var dimension;
        function setOldQuestion(){
            var tmp={};
            $.each(scaledata.Dimension,function(index,value){
                // tmp.name = value.name;
                tmp[value.name] = {};
                tmp[value.name].oldquestions = value.questionsid;
            })
            console.log(tmp);
            dimension = tmp;
            // $.each($("form[id*='Dform']"),function(index,value){
            //     var formdata = objectifyForm($(value).serializeArray());
            //     // var key = Object.keys(formdata);
            //     $.each(formdata,function(index,value){
            //         if(index!="_token")
            //             formdata[index]={"oldquestions":value};
            //     })
            //     tmp =Object.assign({},tmp, formdata); 
            // })
            // var result={};
            // result.questions={};
            // $.each(tmp,function(index,value){
            //     if(index!="_token")
            //         result.questions[index]=value;
            // })
            // oldquestions =result;
        }
        function updateQuestions() {
            //新題目
            var tmp;
            var token;
            $.each($("form[id*='Dform']"),function(index,value){
                var formdata = objectifyForm($(value).serializeArray());
                $.each(formdata,function(index,value){
                    if(index!="_token"){
                        value = value.split(",");
                        dimension[index].newquestions= value;
                    }else{
                        token = value;
                    }
                })
            })
            dimension.scaleid = scaledata.scale.id;
            dimension._token = token;
            console.log(dimension);
            console.log(JSON.stringify(dimension));
            $.ajax({
                type:'put',
                url:"{{url('')}}/Question/",
                data:dimension,
                success:function(r){
                    alert(r.msg);
                    console.error(r.msg);
                    history.go(0);
                }
            })

        }
        $(document).ready(function(){
            // 設定構面數量
            getScaleData();
            setOldQuestion();
        })
        $("#update").click(function(e){
            e.preventDefault();
            var formlist = $('form[id*="Dform"]');
            var check = 1;
            $.each(formlist,function(index,value){
                if(!value.checkValidity()){
                    alert("題目不得為空!");
                    check = 0;
                    return false;
                }
            })
            if(check)
                updateQuestions();
        })
        $("#dimension").change(function(){
            setDimension($("#dimension").val());
        })
        $("#scaleform").submit(function(e){
            e.preventDefault();
            console.log($('input[name="_token"]').val());
            var data = objectifyForm($(this).serializeArray());
            data.author="tmp";
            console.log(JSON.stringify(data));
            $.ajax({
                type:'put',
                url:'{{url('')}}/Scale/'+{{$scale->id}},
                data:data,
                success:function(r){
                    alert(r.msg);
                    history.go(0);
                }
            })
        })
        function setDimension(count){
            $("#dimensionArea").empty();
            for (var i = 1; i <= count; i++) {
                $("#dimensionArea").append('<div id="dimensionArea" class="form-group"><label>構面'+i+'</label><input id="d'+i+'" name="newdimension" class="form-control" required="" disabled="true"></div>');
            }
        }
        function getScaleData(){
            var scaleid ={{$scale->id}};
            $.ajax({
                type:'get',
                url:'../../../Scale/'+scaleid,
                async:false,
                success:function(r){
                    scaledata = r;
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
                        var name = r.Dimension[i].name;
                        var scaleid = r.Dimension[i].scaleid;
                        var PlusQButton = '<button onclick="addQ(/'+name+'/,'+scaleid+','+(i+1)+')" type="button" class="btn btn-success btn-circle"><i class="fa fa-plus"></i></button>';
                        var Plus5QButton = '<button onclick="add5Q(/'+name+'/,'+scaleid+','+(i+1)+')" type="button" class="btn btn-success btn-circle"><i class="fa fa-plus"></i> 5</button>';
                        $("#questions").append('<form id="Dform'+i+'"><div class="row"><div class="col-lg-12"><div class="panel panel-default"><div class="panel-heading">'+name+' '+PlusQButton+' </div><div class="panel-body"><form role="form" id="scaleform">@csrf<div class="row"><div class="col-lg-12"><div id="qarea'+i+'" class="form-group"></div></div></div></form></div></div></div></div></form>');
                    }
                    for (var i = 0; i < Dlength; i++) {
                        var question = '<input id="d'+(i+1)+'q0" type="hidden">';
                            $("#qarea"+i).append(question);
                        for (var j = 0; j <r.Dimension[i].questions.length; j++) {
                            addQ('_'+r.Dimension[i].name+'_',scaleid,i+1,'/'+r.Dimension[i].questions[j]+'/');
                        }
                    }
                }
            })
        }
        function addQ(name,scaleid,d,preValue){
            var lastLabel = $("#qarea"+(d-1)+">label").last()[0];
            lastLabel = $(lastLabel).html();
            if(!lastLabel){
                lastLabel = 1;
            }else{
                lastLabel = parseInt(lastLabel.slice(0,-1))+1;
            }
            // console.log(lastLabel);
            var name = name.toString().slice(1,-1);
            if (preValue)
                var preValue = preValue.toString().slice(1,-1);
            else{
                var preValue = "";
            }
            var last = $("#qarea"+(d-1)+">input").last()[0];
            var DimensionPart = last.id.split("q")[0]+"q";
            var lastnum = parseInt(last.id.split("q")[1]);
            var DelQButton = '<button style="float:right;" onclick="DelQ(this,/'+name+'/,'+scaleid+','+d+','+(lastnum+1)+','+lastLabel+')" type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button>';
            var question = '<label id="ld'+d+'q'+lastLabel+'">'+lastLabel+'.</label>'+DelQButton+'<input id="'+DimensionPart+(lastnum+1)+'" name="'+name+'" class="form-control" value="'+preValue+'" required="">';
            $(last).after(question);
        }
        // function addQ(name,scaleid,d){
        //     var name = name.toString().slice(1,-1);
        //     var last = $("input[id*='d"+d+"q']").last()[0];
        //     var DimensionPart = last.id.split("q")[0]+"q";
        //     var lastnum = parseInt(last.id.split("q")[1]);
        //     var DelQButton = '<button style="float:right;" onclick="DelQ(this,/'+name+'/,'+scaleid+','+(lastnum+1)+','+d+')" type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button>';
        //     var question = '<label id="ld'+d+'q'+(lastnum+1)+'">'+(lastnum+1)+'.</label>'+DelQButton+'<input id="'+DimensionPart+(lastnum+1)+'" name="'+name+'" class="form-control" value="" required="">';
        //     $(last).after(question);
        // }
        function add5Q(name,scaleid,d){
            var name = name.toString().slice(1,-1);
            for (var i = 0; i < 5; i++) {
                var last = $("input[id*='d"+d+"q']").last()[0];
                var DimensionPart = last.id.split("q")[0]+"q";
                var lastnum = parseInt(last.id.split("q")[1]);
                console.log(lastnum+1);
                var question = '<label>'+(lastnum+1)+'.</label><input id="'+DimensionPart+(lastnum+1)+'" name="name" class="form-control" value="" required="">';
                $(last).after(question);
                lastnum++;
            }
        }
        function DelQ(but,name,scaleid,d,q,label){
            $(but).remove();
            $("#ld"+d+"q"+label).remove();
            $("#d"+d+"q"+q).val("");
            $("#d"+d+"q"+q).prop("type","hidden");
            var AllLabel = $("#qarea"+(d-1)+">label");
            $.each(AllLabel,function(index,value){
                $(value).html((index+1)+".");
                $(value).prop("id",'ld'+d+'q'+(index+1));
            })
            var AllButton = $("#qarea"+(d-1)+">button");
            $.each(AllButton,function(index,value){
                var DelLength = $(value).attr("onclick").split(",")[5].length;
                var newDelfun = $(value).attr("onclick").slice(0,-DelLength);
                newDelfun += (index+1)+")";
                $(value).attr("onclick",newDelfun)
                // console.log($(value).attr("onclick").slice(0,-2));
                // $(value).html((index+1)+".");
                // $(value).prop("id",'ld'+d+'q'+(index+1));
            })
            // console.log(lastLabel);
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
                                                <select id="level" name="level" class="form-control" >
                                                    <option value="5">5等第</option>
                                                    <option value="7">7等第</option>
                                                    <option value="9">9等第</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>構面數量</label>
                                                <select id="dimension" class="form-control" disabled="true">
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
            <div class="row" id="questions">
                <div class="col-lg-12">
                    <h1 class="page-header">各構面題庫 <button id="update" type="submit" class="btn btn-primary">儲存變更</button></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
        </div>
@endsection