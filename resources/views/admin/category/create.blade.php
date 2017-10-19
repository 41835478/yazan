@extends('layouts.main')

@section('head_content')
<link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/select2/select2-custom.css')}}">
<link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/multi-select/css/multi-select-custom.css')}}">
<link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/bootstrap-validator/css/bootstrapValidator.min.css')}}">
    <style type="text/css">
        input.shaddress{
            margin-bottom:5px;
        }
    </style>
@endsection

<!-- 面包屑 -->
@section('BreadcrumbTrail')

<section class="content-header">
    <div class="pull-left">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.index')}}">首页</a></li>
            <li><a href="{{route('category.index')}}">系列列表</a></li>
            <li class="active">添加系列</li>
        </ol>
    </div>
</section>
@endsection
<!-- 主体 -->
@section('content')

@include('layouts.message')

<section class="main-content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body">
                    <form id="categoryCreate" action="{{route('category.store')}}" class="form-horizontal" method="post">
                    	{!! csrf_field() !!}
                        <!-- 系列名 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>系列名</label>
                            <div class="col-md-4">
                                <input type="text" name="name" required placeholder="系列名" class="form-control" value="{{old('name')}}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">状态</label>
                            <div class="col-md-2">
                                <select class="form-control" name="status">
                                    <option  value="1">正常</option>
                                    <option  value="0">废弃</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4" style="text-align:center;">
                             	<button type="button" id="category_add" class="btn btn-sm btn-success">添加</button>
                                <button class="btn" onclick="window.history.go(-1);return false;">返回</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script_content')
<!-- 引入表单验证js -->
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-validator/js/bootstrapValidator.min.js')}}"></script>
<script>
	$(document).ready(function(){

		$('#category_add').click(function(){

			var request_url = '{{route('category.checkRepeat')}}';
			var category_name = $("input[name='name']").val();

			
			if(category_name == ''){
				alert('请输入名称');
				return false;
			}

 			$.ajax({
				method: 'POST',
				url: request_url,
				data:$("#categoryCreate").serialize(),
				dataType: 'json',
				headers: {		
					'X-CSRF-TOKEN': '{{ csrf_token() }}'		
				},
				success:function(data){

					if(data.status == '1'){
						//系列重复
						alert(data.message);
						return false;
					}else{
						//系列不重复
						// alert('tijiao');
						$('#categoryCreate').submit();
						// return true;
					}
				},
				error: function(xhr, type){
	
					alert('添加失败，请重新添加或联系管理员');
				}
			});
		});		  
	});
</script>
@endsection
