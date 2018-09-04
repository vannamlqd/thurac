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
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<form action="{{route('csvread')}}" method="POST" role="form" enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<legend>Thêm mới từ file File</legend>
			<div class="form-group">
				<label for="">Họ và tên: </label>
				<input type="file" class="form-control" name="filecsv" value="{{old('filecsv')}}">
			</div>
			<div class="form-group">
			<label for="">Chọn nguồn thu thập: </label>
				<select name="collect_id" id="input" class="form-control">
					@foreach($origins as $origin)
					<option value="{{ $origin->origin_id }}">{{ $origin->name}}</option>
					@endforeach
				</select>
			</div>
			<input type="submit" class="btn btn-primary" value="Thêm mới">
		</form>
	</div>
	<!-- <div class="col-md-8">
		<form action="{{route('postaddemail')}}" method="POST" role="form">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<legend>Thêm mới 1 email</legend>
			<div class="form-group">
				<label for="">Email</label>
				<input type="text" class="form-control" name="email" value="{{old('email')}}">
			</div>
			<div class="form-group">
				<label for="">Description</label>
				<textarea  name="description" cols="30" rows="10" class="form-control">{{old('description')}}</textarea>
			</div>
			<input type="submit" class="btn btn-primary" value="Thêm mới">
		</form>
	</div> -->
</div>
</div>
</div>
@endsection