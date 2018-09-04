@extends('admin.layoutmaster')

@section('active')
@include('Email::active')
@endsection
@section('content')
<div class="panel panel-primary">
<div class="panel-heading">
    <span>Thêm mới </span>
</div>
<div class="panel-body">
<div class="row">
	<div class="col-md-8">
		<form action="" method="POST" role="form">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<legend>Thêm mới luật phân loại</legend>
			<div class="form-group">
				<label for="">Tên luật phân loại</label>
				<input type="text" class="form-control" name="name" value="{{ isset($type->name)?$type->name:''}}">
			</div>
			<div class="form-group">
				<label for="">Luật nhận dạng</label>
				<textarea name="rude" id="input" class="form-control" rows="3">{{ isset($type->rude)?$type->rude:''}}</textarea>
			</div>
			<div class="form-group">
			<label for="">Tình trạng</label>
			<select name="public"  class="form-control">
				<option value="0" selected >no public</option>
				<option value="1"
				{{ (isset($type->public) && $type->public == 1)?'selected':''}}
				>public</option>
			</select>
			</div>
			<input type="submit" class="btn btn-primary" value="Cập nhật">
		</form>
	</div>
	<div class="col-md-4">
		@include('errors.showerr')
	</div>
</div>
</div>
</div>
@endsection