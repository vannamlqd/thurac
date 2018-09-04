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
			<legend>Cập nhật nguồn thu thập</legend>
			<div class="form-group">
				<label for="">Tên nguồn thu thập</label>
				<input type="text" class="form-control" name="name" value="{{$origin->name}}">
			</div>
			<div class="form-group">
				<label for="">Địa chỉ nguồn</label>
				<input type="text" class="form-control" name="address" value="{{$origin->address}}">
			</div>
			<div class="form-group">
				<label for="">Kiểu thông tin</label>
				<input type="text" class="form-control" name="typemap" value="{{$origin->typemap}}">
			</div>
			<div class="form-group">
				<label for="">Thông tin cấu hình</label>
				<input type="text" class="form-control" name="account" value="{{$origin->account}}">
			</div>
			<div class="form-group">
				<label for="">Số emails get tối đa</label>
				<input type="text" class="form-control" name="maxget" value="{{$origin->maxget}}">
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