@extends('layouts.main')

<!-- 面包屑 -->
@section('BreadcrumbTrail')

<section class="content-header">
    <div class="pull-left">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.index')}}">首页</a></li>
            <li><a href="{{route('admin.index')}}">用户列表</a></li>
            <li class="active">添加用户</li>
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
                    <form action="{{route('user.store')}}" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="col-md-1 control-label">Default Input</label>
                            <div class="col-md-4">
                                <input type="text" name="name" placeholder="Default input" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Disabled Input</label>
                            <div class="col-md-4">
                                <input type="text"  placeholder="Disabled" disabled="true" class="form-control" />
                        	</div>
                      	</div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Circle Input</label>
                                <div class="col-md-4">
                                    <input type="text" name="hehe" placeholder=".input-circle" class="input-circle form-control" />
                                </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Squared Input</label>
                            <div class="col-md-4">
                                <input type="text" placeholder=".input-squared" class="input-squared form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Border Input</label>
                            <div class="col-md-4">
                                <input type="text" placeholder=".input-border" class="input-border form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">No Border Input</label>
                            <div class="col-md-4">
                                    <input type="text" placeholder=".input-no-border" class="input-no-border form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Dark Input</label>
                            <div class="col-md-4">
                                <input type="text" placeholder=".input-dark" class="input-dark form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Left Icon</label>
                            <div class="col-md-4">
                                <div class="input-icon"><i class="icon-like"></i>
                                    <input type="text" placeholder="Left Icon" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Right Icon</label>
                            <div class="col-md-4">
                                <div class="input-icon right"><i class="icon-user-female"></i>
                                    <input type="text" placeholder="Right Icon" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Left Icon</label>
                            <div class="col-md-4">
                                <div class="input-group"><span class="input-group-addon"><i class="icon-envelope-open"></i></span>
                                    <input type="email" placeholder="Left Icon" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Right Icon</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="email" placeholder="Right Icon" class="form-control" /><span class="input-group-addon"><i class="icon-envelope-open"></i></span></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Select</label>
                            <div class="col-md-4">
                                <select class="form-control">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Select (multiple)</label>
                            <div class="col-md-4">
                                <select multiple="" class="form-control">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Textarea</label>
                            <div class="col-md-4">
                                <textarea placeholder="Textarea" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Checkbox</label>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="" />Checkbox Label 1</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="" />Checkbox Label 2</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Inline Checkbox</label>
                            <div class="col-md-4">
                                <label class="checkbox-inline">
                                    <input type="checkbox" value="" />Checkbox Label 1</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" value="" />Checkbox Label 2</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Radio Button</label>
                            <div class="col-md-4">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optionsRadios" valu " />Radio option 1</label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optionsRadios" value="option2" />Radio option 2</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Inline Radio Button</label>
                            <div class="col-md-4">
                                <label class="radio-inline">
                                            <input type="radio" name="optionsRadios" value="option1" checked="" />Radio option 1
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optionsRadios" value="option2" />Radio option 2
                                </label>
                            </div>
     					</div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Lightbulbs Rating</label>
                            <div class="col-md-4">
                                <div class="rating">
                                   	<input id="lightbulbs-rating-1" type="radio" name="lightbulbs-rating" />
                                       <label for="lightbulbs-rating-1"><i class="fa fa-lightbulb-o"></i></label>
                                       <input id="lightbulbs-rating-2" type="radio" name="lightbulbs-rating" />
                                       <label for="lightbulbs-rating-2"><i class="fa fa-lightbulb-o"></i></label>
                                       <input id="lightbulbs-rating-3" type="radio" name="lightbulbs-rating" />
                                       <label for="lightbulbs-rating-3"><i class="fa fa-lightbulb-o"></i></label>
                                       <input id="lightbulbs-rating-4" type="radio" name="lightbulbs-rating" />
                                       <label for="lightbulbs-rating-4"><i class="fa fa-lightbulb-o"></i></label>
                                       <input id="lightbulbs-rating-5" type="radio" name="lightbulbs-rating" />
                                       <label for="lightbulbs-rating-5"><i class="fa fa-lightbulb-o"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Stars Rating</label>
                            <div class="col-md-4">
                                <div class="rating">
                                    <input id="stars-rating-1" type="radio" name="stars-rating" />
                                    <label for="stars-rating-1"><i class="fa fa-star"></i></label>
                                    <input id="stars-rating-2" type="radio" name="stars-rating" />
                                    <label for="stars-rating-2"><i class="fa fa-star"></i></label>
                                    <input id="stars-rating-3" type="radio" name="stars-rating" />
                                    <label for="stars-rating-3"><i class="fa fa-star"></i></label>
                                    <input id="stars-rating-4" type="radio" name="stars-rating" />
                                    <label for="stars-rating-4"><i class="fa fa-star"></i></label>
                                    <input id="stars-rating-5" type="radio" name="stars-rating" />
                                    <label for="stars-rating-5"><i class="fa fa-star"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Trophies Rating</label>
                            <div class="col-md-4">
                                <div class="rating">
                                    <input id="trophies-rating-1" type="radio" name="trophies-rating" />
                                    <label for="trophies-rating-1"><i class="fa fa-trophy"></i></label>
                                    <input id="trophies-rating-2" type="radio" name="trophies-rating" />
                                    <label for="trophies-rating-2"><i class="fa fa-trophy"></i></label>
                                    <input id="trophies-rating-3" type="radio" name="trophies-rating" />
                                    <label for="trophies-rating-3"><i class="fa fa-trophy"></i></label>
                                    <input id="trophies-rating-4" type="radio" name="trophies-rating" />
                                    <label for="trophies-rating-4"><i class="fa fa-trophy"></i></label>
                                    <input id="trophies-rating-5" type="radio" name="trophies-rating" />
                                    <label for="trophies-rating-5"><i class="fa fa-trophy"></i></label>
                                    <input id="trophies-rating-6" type="radio" name="trophies-rating" />
                                    <label for="trophies-rating-6"><i class="fa fa-trophy"></i></label>
                                    <input id="trophies-rating-7" type="radio" name="trophies-rating" />
                                    <label for="trophies-rating-7"><i class="fa fa-trophy"></i></label>
                                    <input id="trophies-rating-8" type="radio" name="trophies-rating" />
                                    <label for="trophies-rating-8"><i class="fa fa-trophy"></i></label>
                                    <input id="trophies-rating-9" type="radio" name="trophies-rating" />
                                    <label for="trophies-rating-9"><i class="fa fa-trophy"></i></label>
                                    <input id="trophies-rating-10" type="radio" name="trophies-rating" />
                                    <label for="trophies-rating-10"><i class="fa fa-trophy"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-success has-feedback">
                            <label class="col-md-1 control-label">Input with Success</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" /><span class="fa fa-check form-control-feedback"></span></div>
                        </div>
                        <div class="form-group has-warning has-feedback">
                            <label class="col-md-1 control-label">Input with Warning</label>
							<div class="col-md-4">
							<input type="text" class="form-control" /><span class="fa fa-warning form-control-feedback"></span></div>
						</div>
						<div class="form-group has-error has-feedback">
							<label class="col-md-1 control-label">Input with Error</label>
							<div class="col-md-4">
							<input type="text" class="form-control" /><span class="fa fa-times form-control-feedback"></span></div>
						</div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Submit</label>
                            <div class="col-md-4">
                             	<button type="submit" class="btn btn-sm btn-success">Submit Button</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Tag</label>
                            <div class="col-md-4"><a href="javascript:;" class="tag">Front-end development</a></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Tag List</label>
                            <div class="col-md-4">
                                <ul class="tags">
                                    <li><a href="#" class="tag">HTML</a></li>
                                    <li><a href="#" class="tag">CSS</a></li>
                                    <li><a href="#" class="tag">JavaScript</a></li>
                                </ul>
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
<script src="{{URL::asset('yazan/global/plugins/select2/select2.min.js')}}"></script>
<script src="{{URL::asset('yazan/assets/js/form-validation.js')}}"></script>
<script>
	/*$(document).ready(function(){

	});*/
</script>
@endsection