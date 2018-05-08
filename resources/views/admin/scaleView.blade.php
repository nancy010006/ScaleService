@extends("admin.default")

@section("css")
    <!-- DataTables Responsive CSS -->
    <link href="{{url('')}}/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Social Buttons CSS -->
    <link href="{{url('')}}/vendor/bootstrap-social/bootstrap-social.css" rel="stylesheet">
    <link href="{{url('')}}/css/jquery.floatingscroll.css" rel="stylesheet">
    <style type="text/css">
        td{
            text-align: right;
            min-width: 100px;
        }
        .same{
            background-color: pink;
        }
        div{
            scrollbar-hightlight-color:pink;
        }
    </style>
@endsection

@section("js")
<!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="{{url('')}}/vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="{{url('')}}/vendor/datatables-responsive/dataTables.responsive.js"></script>
    <script src="{{url('')}}/js/jquery.floatingscroll.min.js"></script>
    <script type="text/javascript">
        var scale = {};
        $(document).ready(function(){
            initial();
            console.log(scale);
            addToTable();
            $(".table-responsive").floatingScroll();
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
                    console.error(r);
                    result = r;
                }
            })
            return result;
        }
        function addToTable(){
            var compare = [];
            console.log(scale.analysis.corr);
            var count = 1;
            var interupt = [];
            var colcount =1;
            $.each(scale.analysis.corr,function(index,val){
                console.log(index);
                var count = 1;
                $.each(val,function(innerindex,innerval){
                    $("#thead tr").append('<th>'+index+(count++)+'</th>');
                })
                interupt.push(count-1);
                var count = 1;
                // console.log(val);
                $.each(val,function(innerindex,innerval){
                    var td='';
                    var rowcount = 1 ;
                    $.each(innerval,function(innerindex2,innerval2){
                        // console.log(innerval);
                        if(innerval2!=1)
                            td+='<td>'+innerval2+'</td>';
                        else
                            td+='<td class="same">'+innerval2+'</td>';
                    })
                    $("#tbody").append('<tr><th>'+index+(count++)+'</th>'+td+'</tr>');
                    colcount++;
                })
                // console.log(val);
            })
            // interupt.reverse();
            // console.log(interupt);
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
                                <label>相關係數矩陣</label>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
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