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
			<legend>Thêm mới nguồn thu thập</legend>
			<div class="form-group">
				<label for="">Tên nguồn thu thập</label>
				<input type="text" class="form-control" name="name" value="{{old('name')}}">
			</div>
			<div class="form-group">
				<label for="">Địa chỉ nguồn</label>
				<input type="text" class="form-control" name="address" value="{{old('address')}}">
			</div>
			<div class="form-group">
				<label for="">Kiểu thông tin</label>
				<input type="text" class="form-control" name="typemap" value="{{old('typemap')}}">
			</div>
			<div class="form-group">
				<label for="">Thông tin cấu hình</label>
				<input type="text" class="form-control" name="account" value="{{old('account')}}">
			</div>
			<div class="form-group">
				<label for="">Số emails get tối đa</label>
				<input type="text" class="form-control" name="maxget" value="{{old('maxget')}}">
			</div>
			<input type="submit" class="btn btn-primary" value="Thêm mới">
		</form>
	</div>
	<div class="col-md-4">
		@include('errors.showerr')
	</div>
</div>
</div>
</div>
@endsection