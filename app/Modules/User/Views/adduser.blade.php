@extends('admin.layoutmaster')
@section('active')
@include('User::active')
@endsection
@section('content')
<div class="panel panel-primary">
<div class="panel-heading">
    <span>Thêm mới user </span>
</div>
<div class="panel-body">
@include('errors.showerr')
<form action="" method="POST" role="form">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<legend>Form title</legend>
	<div class="form-group">
		<label for="">Họ và tên: </label>
		<input type="text" class="form-control" name="name" value="{{old('name')}}">
	</div>
	<div class="form-group">
		<label for="">Email: </label>
		<input type="text" class="form-control" name="email" value="{{old('email')}}">
	</div>
	<div class="form-group">
		<label for="">Password: </label>
		<input type="password" class="form-control" name="password" value="{{old('password')}}">
	</div>
	@if(Session::get('user')['type'] != 0)
	<div class="form-group">
		<label for="">Type: </label>
		<select class="form-control" name="type">
			<option value="0">Quản trị viên</option>
			<option value="1">Quản trị hệ thống</option>
		</select>
	</div>
	@endif
	<input type="submit" class="btn btn-primary" value="Thêm mới">
</form>
</div>
</div>
</div>
</div>

@endsection