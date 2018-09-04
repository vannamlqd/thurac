@extends('admin.layoutmaster')
@section('active')
@include('Email::active')
@endsection
@section('content')
<div class="panel panel-primary">
<div class="panel-heading">
	<span>Thống Kê Phân Loại</span>
	<!-- <a href="{{route('addemail')}}" class="add">Thêm mới</a> -->
	<div class="clear"></div>
</div>
<div class="panel-body">
	<form action="" class="form-inline" method="get" role="form">
		{{ csrf_field() }}
		<div class="form-group col-md-3">
			<input placeholder="Từ ngày" type="text" name="datefrom" class="form-control date" value="{{ $data['datefrom'] }}" id="from">
		</div>
		<div class="form-group col-md-3">
			<input placeholder="đến ngày" type="text" name="dateto" class="form-control date" value="{{ $data['dateto'] }}" id="to">
		</div>
		<div class="clear" style="height:15px"></div>
		<div class="form-group col-md-3">
	        <select class="form-control" name="filter_type" style="width: 175px">
	            <option value="">Lọc</option>
	         		<option value="1" @if($data['filter_type'] == 1) selected @endif>Địa chỉ IP</option>
							<option value="2" @if($data['filter_type'] == 2) selected @endif>Domain</option>
							<option value="3" @if($data['filter_type'] == 3) selected @endif>Người gửi</option>
	        </select>
		</div>
		<div class="form-group col-md-3">
	        <input placeholder="Keyworld" type="text" name="keyworld" class="form-control" value="{{ $data['keyworld'] }}">
    </div>
	<div class="form-group col-md-2">
        <select class="form-control" name="status" style="width: 150px">
            <option value="">Loại email</option>
            @foreach(config('filesystems.status') as $key => $value)
					<option value="{{ $key }}"
            @if($data['status'] == $key)
                selected
            @endif  
            >{{ $value }}</option>
            @endforeach
        </select>
    </div>
		<div class="col-md-3">
			<button type="submit" class="btn btn-primary">Hiển Thị</button>
			@if(!empty($_GET['_token']) && (isset($allemail) && count($allemail) > 0 ))
				@if($data['filter_type'] == 1)
					<a href="{{route('exportxls',['model'=>'Ip'])}}?{{ $_SERVER['QUERY_STRING'] }}" class="btn btn-success" style="margin-left:20px">Xuất file</a>
				@elseif($data['filter_type'] == 2)
					<a href="{{route('exportxls',['model'=>'Domain'])}}?{{ $_SERVER['QUERY_STRING'] }}" class="btn btn-success" style="margin-left:20px">Xuất file</a>
				@elseif($data['filter_type'] == 2)
					<a href="{{route('exportxls',['model'=>'Sender'])}}?{{ $_SERVER['QUERY_STRING'] }}" class="btn btn-success" style="margin-left:20px">Xuất file</a>
				@else
					<a href="{{route('exportxls',['model'=>'Email'])}}?{{ $_SERVER['QUERY_STRING'] }}" class="btn btn-success" style="margin-left:20px">Xuất file</a>
				@endif
			@endif
		</div>
	</form>
	<div class="clear" style="padding-bottom: 20px"></div>
		<div class="wtable table-responsive col-md-12">
		@if(isset($allemail) && count($allemail) > 0 )
			<table class="table table-bordered ">
				<thead>
					<tr class="danger">
						<th  class="text-center">Nguồn</th>
						<th  class="text-center">Người gửi</th>
						<th class="text-center">Địa chỉ Ip</th>
						<th class="text-center">Ngày thêm</th>
						<th class="text-center">Phân loại</th>
					</tr>
				</thead>
				<tbody>
					@foreach($allemail as $email)
					<tr class="even">
						<td align="center">
						<div style="width:150px;word-wrap:break-word;">    
						{{config('filesystems.collect')[$email->collect_id]}}
						</div>
						</td>
						<td align="center"><div style="width:200px;word-wrap:break-word;"><strong>{{ $email->email }}</strong></div></td>
						<td align="center">{{ $email->ip }}</td>
						<td align="center">
						<div style="width:100px;word-wrap:break-word;">
						{{ date('Y-m-d', strtotime($email->created_at)) }}
						</div>
						</td>
						<td align="center">
						<div style="width:110px;word-wrap:break-word;">    
						{{config('filesystems.status')[$email->status]}}
						</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			{{ $allemail->links() }}
				@else
				@if(!empty($_GET['_token']))
				<h3>Không tìm thấy email nào</h3>
				@endif
			@endif
	</div>
	<div role="tabpanel" class="tab-pane mb" id="report">

	</div>
	</div>

</div>
</div>
@endsection