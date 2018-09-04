@extends('admin.layoutmaster')

@section('active')
@include('Email::active2')
@endsection

@section('content')
<div class="panel panel-primary">
<div class="panel-heading">
<span>Danh sách nguồn thu thập thư rác</span>
<a href="{{ route('getaddorigin') }}" class="add"> Thêm nguồn thu thập</a>
<div class="clear"></div>
</div>
<div class="panel-body">
@include('errors.showerr')
<div class="form">
    <!-- <form class="form-inline">
        <div class="form-group">
            <span>Tìm kiếm nguồn thu thập thư rác: </span>
            <input type="text" class="form-control" placeholder="Nhập thông tin về nguồn thu thập">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Tìm kiếm</button>
    </form> -->
</div>
@if(isset($origins) && count($origins) > 0)
<div class="wtable">
   <table class="table table-bordered">
				<thead>
					<tr class="danger">
						<th class="text-center" width="300 px">Tên nguồn thu thập</th>
						<th class="text-center" width="">Địa chỉ nguồn</th>
						<th class="text-center" width="">Kiểu thông tin</th>
						<th class="text-center" width="">Thông tin cấu hình</th>
						<th class="text-center" width="">Max get</th>
						<th class="text-center" width="100px">Thao tác</th>
					</tr>
				</thead>
					<tbody>
					@foreach($origins as $value)
					<tr class="even">
					<td align="left"><strong>{{$value->name}}</strong></td>
					<td align="left">{{$value->address}}</td>
					<td align="left">{{$value->typemap}}</td>
					<td align="left">{{$value->account}}</td>
					<td align="left">{{$value->maxget}}</td>
					<td align="center">
						<a href="{{ route('geteditorigin',['id'=>$value->origin_id]) }}">Sửa </a>
					</tr>
					@endforeach
					</tbody>
				</table>
</div>
@endif
</div>
</div>
@endsection