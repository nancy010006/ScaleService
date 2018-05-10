@extends("admin.default")

@section("css")
    <!-- DataTables Responsive CSS -->
    <!-- <link href="{{url('')}}/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet"> -->
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedcolumns/3.2.4/css/fixedColumns.dataTables.min.css" rel="stylesheet">

    <!-- Social Buttons CSS -->
    <link href="{{url('')}}/vendor/bootstrap-social/bootstrap-social.css" rel="stylesheet">
    <link href="{{url('')}}/css/jquery.floatingscroll.css" rel="stylesheet">
    <style type="text/css">
        td{
            text-align: right;
            min-width: 100px;
        }
        .color1{
            background-color: #00DCFFFF;
        }
        .color2{
            background-color: #00FFA5FF;
        }
        .color3{
            background-color: #EBFF00FF;
        }
        .color4{
            background-color: #FFB900FF;
        }
        .color5{
            background-color: #FF0073FF;
        }
        .color6{
            background-color: #CF00FFFF;
        }
        .same{
            background-color: pink;
        }
    </style>
@endsection

@section("js")
<!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.2.4/js/dataTables.fixedColumns.min.js"></script>
    <script src="{{url('')}}/vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="{{url('')}}/vendor/datatables-responsive/dataTables.responsive.js"></script>
    <script src="{{url('')}}/js/jquery.floatingscroll.min.js"></script>
    <script type="text/javascript">
        var scale = {};
        $(document).ready(function(){
            initial();
            addBasic();
            addToTable();
            $("#table").dataTable({
                scrollY:'800px',
                scrollX:        true,
                scrollCollapse: true,
                paging:         false,
                fixedColumns:   {
                    leftColumns: 1,
                },
                ordering: false,
                info:     false,
                searching:false
            });
            // $(".table-responsive").floatingScroll();
        })
        function initial(){
            scale.basic = getScaleData();
            scale.analysis = getScaleAnalysis();
        }
        function getScaleData(){
            var result;
            var scaleid ={{$scale->id}};
            $.ajax({
                type:'get',
                url:'{{url("")}}/Scale/'+scaleid,
                async:false,
                success:function(r){
                    // console.table(r);
                    result = r;
                }
            })
            return result;
        }
        function getScaleAnalysis(){
            var result;
            var scaleid ={{$scale->id}};
            $.ajax({
                type:'get',
                url:'{{url("")}}/api/getAnalysis/'+scaleid,
                async:false,
                success:function(r){
                    result = r;
                }
            })
            return result;
        }
        function addBasic(){
            $("#halfReliablity").html(scale.analysis.halfReliablity);
        }
        function addToTable(){
            var compare = [];
            console.log(scale.analysis.corr);
            var count = 1;
            var interupt = [];
            var colcount =1;
            var row = 1;
            var test = 1;
            $.each(scale.analysis.corr,function(index,val){
                // console.log(index);
                var count = 1;
                $.each(val,function(innerindex,innerval){
                    $("#thead tr").append('<th>'+index+(count++)+'</th>');
                })
                interupt.push(count-1);

                var count = 1;
                $.each(val,function(innerindex,innerval){
                    var td='';
                    var col = 1;

                    // 相關係數全部秀出來
                    $.each(innerval,function(innerindex2,innerval2){
                        if(innerval2!=1)
                            td+='<td id="'+col+','+row+'">'+innerval2+'</td>';
                        else
                            td+='<td id="'+col+','+row+'" class="same">'+innerval2+'</td>';
                        col++;
                    })

                    //相關係數只秀一半
                    // for (var i = 0; i < test; i++) {
                    //     if(innerval[i]!=1)
                    //         td+='<td id="'+col+','+row+'">'+innerval[i]+'</td>';
                    //     else
                    //         td+='<td id="'+col+','+row+'" class="same">'+innerval[i]+'</td>';
                    //     col++;
                    // }
                    // for (var i = 56; i > test; i--) {
                    //     if(innerval[i]!=1)
                    //         td+='<td id="'+col+','+row+'"></td>';
                    //     else
                    //         td+='<td id="'+col+','+row+'" class="same"></td>';
                    //     col++;
                    // }
                    $("#tbody").append('<tr><th class="headcol">'+index+(count++)+'</th>'+td+'</tr>');
                    row++;
                    test++;
                })
                // console.log(val);
            })
            interupt.reverse();
            var total = 0;
            $.each(interupt,function(index,val){
                total+=val;
            })
            var colorcount = 1;
            $.each(interupt,function(index,val){
                $.each($('#tbody td'),function(innerindex,innerval){
                    var x = this.id.split(',')[0];
                    var y = this.id.split(',')[1];
                    if(x<=total&&y<=total){
                        $(this).addClass('color'+colorcount);
                    }

                    // if(x)
                })
                total-=val;
                colorcount++;
            })
            // console.log(colcount-1);
            // var colorcount = 1;
            // $.each($("td"),function(index,val){
            //     x = val.id.split(',')[0];
            //     y = val.id.split(',')[1];
            //     $.each(interupt,function(innerindex,innerval){
            //         if(x<=colcount-innerval&&y<=colcount-innerval){
            //             // $(this).attr('class','color');
            //         }    
            //     })
            //     colorcount++;
            // })
        }
    </script>
@endsection

@section("page-wrapper")
<div id="page-wrapper">
            <form role="form" id="scaleform">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">量表詳細內容</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <label>分析資料</label>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>折半信度</label>
                                                <p id="halfReliablity">
                                                </p>
                                            </div>
                                    </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <label>相關係數矩陣</label>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table id="table" class="table table-striped table-bordered table-hover">
                                                <thead id="thead">
                                                    <tr>
                                                        <th>#</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody">
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.panel-body -->
                                    <!-- /.panel -->
                                </div>
                                <!-- /.row (nested) -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            </form>
        </div>
@endsection