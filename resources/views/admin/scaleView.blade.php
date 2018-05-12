@extends("admin.default")

@section("css")
    <!-- DataTables Responsive CSS -->
    <!-- <link href="{{url('')}}/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet"> -->
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedcolumns/3.2.4/css/fixedColumns.dataTables.min.css" rel="stylesheet">

    <!-- Social Buttons CSS -->
    <link href="{{url('')}}/vendor/bootstrap-social/bootstrap-social.css" rel="stylesheet">
    {{-- <link href="{{url('')}}/css/jquery.floatingscroll.css" rel="stylesheet"> --}}
    <style type="text/css">
        td{
            text-align: right;
            min-width: 100px;
        }
        .color1{
            background-color: #FF837D;
        }
        .color2{
            background-color: #7497BB;
        }
        .color3{
            background-color: #ACDC9D;
        }
        .color4{
            background-color: #EED19C;
        }
        .color5{
            background-color: #EFB28C;
        }
        .color6{
            background-color: #E8837E;
        }
        .color7{
            background-color: #7497BB;
        }
        .color8{
            background-color: #ACDC9D;
        }
        .same{
            background-color: #FFFC70;
        }
        .loader {
            border: 16px solid #f3f3f3; /* Light grey */
            border-top: 16px solid #3498db; /* Blue */
            border-bottom: 16px solid #3498db;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
            text-align: center;
            padding: 30px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .worst{
            /*color: red;*/
            background-color: red;
            font-weight: bold;  
        }
    </style>
@endsection

@section("js")
<!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.2.4/js/dataTables.fixedColumns.min.js"></script>
    <script src="{{url('')}}/vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="{{url('')}}/vendor/datatables-responsive/dataTables.responsive.js"></script>
    {{-- <script src="{{url('')}}/js/jquery.floatingscroll.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script type="text/javascript">
        var scale = {};
        $(document).ready(function(){
            initial();
            addBasic();
            addToTable();
            fixTableHeader();
            setPopover();
            setMinVailtyColor();
        })
        function setPopover(){
            $(function () {
              $('[data-toggle="popover"]').popover({
                container: 'body'
              })
            })
        }
        function loadingEffect() {
            var loading = $('.loader');
            loading.hide();
            $(document).ajaxStart(function () {
                loading.show();
            }).ajaxStop(function () {
                loading.hide();
            });
        }
        function initial(){
            loadingEffect();
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
            $('#halfReliablity').html(scale.analysis.halfReliablity);
            $('#alpha').html(scale.analysis.alpha);
            $('#DiscriminantValidity').html(scale.analysis.DiscriminantValidity);
            $.each(scale.analysis.MinVality,function(index,val){
                $("#minValityArea").append('<li><strong>'+index+'</strong> : <span>'+val+'</span></li>');
            })
        }
        function setMinVailtyColor(){
            var size = Object.keys(scale.analysis.MinVality).length;
            console.log(size);
            $.each(scale.analysis.MinVality,function(index,val){
                $.each($('td[name="team'+size+'"]'),function(innerindex,innerval){
                    if($(this).text()==val)
                        $(this).addClass('worst');
                })
                size--;
            })
        }
        function fixTableHeader(){
            $("#table").dataTable({
                scrollY:document.documentElement.clientHeight-100+'px',
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
                var count = 1;
                $.each(val,function(innerindex,innerval){
                    $("#thead tr").append('<th data-placement="top"  data-toggle="popover" title="題目敘述" data-trigger="hover" data-content="'+innerindex+'">'+index+(count++)+'</th>');
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
                    $("#tbody").append('<tr><th class="headcol" data-placement="left"  data-toggle="popover" title="題目敘述" data-trigger="hover" data-content="'+innerindex+'">'+index+(count++)+'</th>'+td+'</tr>');
                    row++;
                    test++;
                })
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
                        $(this).attr('name','team'+colorcount);
                    }

                })
                total-=val;
                colorcount++;
            })
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
                <div class="loader">分析中</div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <label>分析資料</label>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Cronbach &#945</label>
                                    <p id="alpha">
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>折半信度</label>
                                    <p id="halfReliablity">
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>區別效度</label>
                                    <p id="DiscriminantValidity">
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>收斂效度</label>
                                    <ul id="minValityArea">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- /.col-lg-12 -->
            </div>
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