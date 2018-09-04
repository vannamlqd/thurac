@extends('admin.layoutmaster')

@section('active')
@include('Email::active')
@endsection
@section('content')
<div class="panel panel-primary">
<div class="panel-heading">
    <span>Quản lí địa chỉ IP phát tán thư rác</span>
    <!-- <a href="{{route('addemail')}}" class="add">Thêm mới</a> -->
    <div class="clear"></div>
</div>
<div class="panel-body">
    @include('errors.showerr')
    <div class="row form">
        <div class="col-md-6">
            <form class="form-inline" action="" method="">
                <div class="form-group">
                    <span>Truy vấn địa chỉ Ip</span>
                    <input type="text" name="search" class="form-control" placeholder="Search" value="{{ $search }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Truy Vấn</button>
            </form>
        </div>
        <div class="col-md-6 text-right">
            <span style="vertical-align: -webkit-baseline-middle;">Kết quả hiện thị trang {{ $allip->currentPage() }} trong tổng số {{ $allip->lastpage() }} trang</span>
<!--             <a href="{{route('exportxls',['model'=>'Ip'])}}" class="btn btn-success btn-sm" style="vertical-align: -webkit-baseline-middle;margin-left:20px">Xuất file</a> -->
        </div>
    </div>
    @if(isset($allip) && count($allip) > 0)
    <div class="wtable">
        <table id="example" class="table-responsive table table-bordered ">
							<thead>
								<tr class="danger">
									<!-- <th class="text-center">Id</th> -->
									<th class="text-center">Địa chỉ ip </th>
									<th class="text-center">Số lần spam </th>
									<th class="text-center">Ngày thêm</th>
									<!-- <th class="text-center">Ngày update gần nhất</th> -->
									<!-- <th class="text-center">Hành động</th> -->
								</tr>
							</thead>
													<tbody>
								@foreach($allip as $ip)
							<tr class="even">
								<!-- <td align="center"><strong>{{ $ip->ip_id }}</strong></td> -->
								<td align="center"><strong>{{ $ip->ip }}</strong></td>
								<td align="center">{{ $ip->time }}</td>
								<td align="center">
								<div style="width:100px;word-wrap:break-word;">
								{{ $ip->created_at }}
								</div>
								</td>
								<!-- <td align="center">{{ $ip->updated_at }}</td> -->
								<!-- <td align="center">
									<a href="http://103.28.173.237/dnsbl/edit?ip=2.2.2.45">Sửa</a>
									&nbsp;
									<a href="http://103.28.173.237/dnsbl/delete?ip=2.2.2.45" onclick="return confirm('Bạn có muốn xóa địa chỉ 2.2.2.45 khỏi danh sách không?')">Xóa</a>
									&nbsp;
								</td>		 -->						
							</tr>
							@endforeach
						</tbody></table>
    </div>
    {{ $allip->links() }}
    @endif
</div>
</div>
@endsection