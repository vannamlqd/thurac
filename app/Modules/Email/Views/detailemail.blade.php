@extends('admin.layoutmaster')

@section('active')
@include('Email::active')
@endsection
@section('content')
<div class="panel panel-primary">
<div class="panel-heading">
    <span>Nội dung email</span>
</div>
<div class="panel-body">
@include('errors.showerr')
@if(isset($detailemail))
<table class="table">
	<tr>
		<td>Nguồn thu thập:</td>
		<td>{{ config('filesystems.collect')[$detailemail->collect_id] }}</td>
	</tr>
	<tr>
		<td>Loại:</td>
		<td>{{ config('filesystems.status')[$detailemail->status] }}</td>
	</tr>
	<tr>
		<td>Thời gian:</td>
		<td>{{ $detailemail->created_at }}</td>
	</tr>
	<tr>
		<td>Ip:</td>
		<td>{{ $detailemail->ip }}</td>
	</tr>
	<tr>
		<td>Đại chỉ email gửi:</td>
		<td>{{ $detailemail->email }}</td>
	</tr>
	<tr>
		<td>Địa chỉ email nhận:</td>
		<td>{{ $detailemail->to }}</td>
	</tr>
	<tr>
		<td>Tiêu đề :</td>
		<td>{{ $detailemail->subject }}</td>
	</tr>
	<tr>
		<td>Nội dung:</td>
		<td><div class="modal fade  bs-example-modal-lg" id="myModal{{ $detailemail->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Nội dung email</h4>
                    </div>
                    <div class="modal-body">
                    {{ $detailemail->description }}
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                    </div>
                    </div>    
        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal{{ $detailemail->id }}">Hiển thị</button>
                    </div></td>
	</tr>
</table>
@endif
</div>
</div>
@endsection