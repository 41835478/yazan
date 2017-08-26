@extends('layouts.main')

@section('head_content')
	<link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}">
@endsection

@section('BreadcrumbTrail')
	<section class="content-header">
        <div class="pull-left">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.index')}}">首页</a></li>
                <li class="active">商户列表</li>
            </ol>
        </div>
    </section>
@endsection

@section('content')

@include('layouts.message')
<section class="main-content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body">
            		<ul class="nav nav-tabs">
            		  	<li style="display: inline-block;line-height:20px;">
							<a class="btn btn-search" href="#"><i class="halflings-icon search"></i>搜索订单</a>
						</li>
            		  	<li style="display: inline-block;line-height:20px;float:right;">
							<a class="btn btn-primary" href="{{route('suppliers.create')}}">添加订单</a>
						</li>
						<li style="display:inline-block;line-height:20px;float:right;">
							<a href="#" onclick="window.history.go(-1);return false;" class="btn ">返回</a>
						</li>
            		</ul>
                    <table id="datatables" class="table table-striped table-no-border">
                        <thead class="bg-default">
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Office</th>
                                <th>Age</th>
                                <th>Start date</th>
                                <th>Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tiger Nixon</td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                                <td>2011/04/25</td>
                                <td>$320,800</td>
                            </tr>
                            <tr>
                                <td>Garrett Winters</td>
                                <td>Accountant</td>
                                <td>Tokyo</td>
                                <td>63</td>
                                <td>2011/07/25</td>
                                <td>$170,750</td>
                            </tr>
                            <tr>
                                <td>Ashton Cox</td>
                                <td>Junior Technical Author</td>
                                <td>San Francisco</td>
                                <td>66</td>
                                <td>2009/01/12</td>
                                <td>$86,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>



                <div class="col-md-7 col-sm-12">
                	<div class="dataTables_paginate paging_simple_numbers" id="datatables_paginate">
                	<div class="pagination pagination-centered">
                	<ul class="pagination"><li class="disabled"><span>«</span></li> <li class="active"><span>1</span></li><li><a href="http://www.sjztcl.com/admin/want/index?page=2">2</a></li><li><a href="http://www.sjztcl.com/admin/want/index?page=3">3</a></li><li><a href="http://www.sjztcl.com/admin/want/index?page=4">4</a></li><li><a href="http://www.sjztcl.com/admin/want/index?page=5">5</a></li><li><a href="http://www.sjztcl.com/admin/want/index?page=6">6</a></li><li><a href="http://www.sjztcl.com/admin/want/index?page=7">7</a></li><li><a href="http://www.sjztcl.com/admin/want/index?page=8">8</a></li><li class="disabled"><span>...</span></li><li><a href="http://www.sjztcl.com/admin/want/index?page=80">80</a></li><li><a href="http://www.sjztcl.com/admin/want/index?page=81">81</a></li> <li><a href="http://www.sjztcl.com/admin/want/index?page=2" rel="next">»</a></li></ul></div>
                	</div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script_content')
<!-- 引入表格js -->
<!-- <script src="{{URL::asset('yazan/assets/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script> -->
<!-- <script src="{{URL::asset('yazan/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script> -->
<!-- <script src="{{URL::asset('yazan/assets/js/table-datatables.js')}}"></script> -->

<script type="text/javascript">

	jQuery(document).ready(function($){


	});
</script>

@endsection
