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
        $("#add").click(function(){
            window.location.href="{{url('')}}/admin/scale/add";
        })
        $(document).ready(function(){
            getData();
        })
        function getData(){
            $.ajax({
                type:'get',
                url:'../Scales',
                success:function(r){
                    var i=1;
                    $.each(r,function(key,value){
                        if(value.questions>0)
                            $("#tbody").append("<tr><td>"+i+"</td><td>"+value.name+"</td><td>"+value.level+"</td><td>"+value.created_at+"</td><td><button onclick='editScale("+value.id+")' type='button' class='btn btn-success btn-circle'><i class='fa fa-check'></i></button> <button onclick='deleteScale("+value.id+")' type='button' class='btn btn-danger btn-circle'><i class='fa fa-times'></i></button></td></tr>");
                        else
                            $("#tbody").append("<tr><td>"+i+"</td><td>"+value.name+"</td><td>"+value.level+"</td><td>"+value.created_at+"</td><td><button onclick='editScale("+value.id+")' type='button' class='btn btn-primary btn-circle'><i class='fa fa-pencil'></i></button> <button onclick='deleteScale("+value.id+")' type='button' class='btn btn-danger btn-circle'><i class='fa fa-times'></i></button></td></tr>");
                        i++;
                    })
                }
            })
        }
        function deleteScale(id){
            var check = confirm("確定要刪除嗎?");
            if(check){
                $.ajax({
                    type:'delete',
                    url:'../Scale/'+id,
                    data:{"_token":"{{ csrf_token() }}"},
                    success:function(r){
                        alert(r.msg);
                        window.location.href="scale";
                    }
                })
            }
        }
        function editScale(id){
            window.location.href="{{url('')}}/admin/scale/edit/"+id;
        }
    </script>
@endsection

@section("page-wrapper")
    <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="page-header">量表管理</h1>
                </div>
                <div class="col-lg-6">
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            量表目錄 <button id="add" type="button" class="btn btn-primary btn-sm">新增量表</button>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="scalelist">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>量表名稱</th>
                                            <th>等第</th>
                                            <th>建立日期</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        <!-- <tr>
                                            <td>1</td>
                                            <td>Mark</td>
                                            <td>Otto</td>
                                            <td>@mdo</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Jacob</td>
                                            <td>Thornton</td>
                                            <td>@fat</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Larry</td>
                                            <td>the Bird</td>
                                            <td>@twitter</td>
                                        </tr> -->
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
    </div>
@endsection