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
@include('errors.showerr')
@if(isset($email))
<form action="" method="POST" role="form">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<legend>Form title</legend>
	<div class="form-group">
		<label for="">Email</label>
		<input type="text" class="form-control" name="email" value="{{$email->email}}">
	</div>
	<div class="form-group">
		<label for="">Description</label>
		<textarea  name="description" cols="30" rows="10" class="form-control">{{$email->description}}</textarea>
	</div>
	<input type="submit" class="btn btn-primary" value="Cập nhật">
</form>
@endif
</div>
</div>
@endsection