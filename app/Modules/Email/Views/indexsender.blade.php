@extends('admin.layoutmaster')

@section('active')
@include('Email::active')
@endsection
@section('content')
<div class="panel panel-primary">
<div class="panel-heading">
    <span>Quản lí địa chỉ Người Gửi phát tán thư rác</span>
    <!-- <a href="{{route('addemail')}}" class="add">Thêm mới</a> -->
    <div class="clear"></div>
</div>
<div class="panel-body">
    @include('errors.showerr')
  <div class="row form">
        <div class="col-md-6">
            <form class="form-inline" action="" method="">
                <div class="form-group">
                    <span>Truy vấn địa chỉ người gửi</span>
                    <input type="text" name="search" class="form-control" placeholder="Search" value="{{ $search }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Truy Vấn</button>
            </form>
        </div>
        <div class="col-md-6 text-right">
            <span style="vertical-align: -webkit-baseline-middle;">Kết quả hiện thị trang {{ $allsender->currentPage() }} trong tổng số {{ $allsender->lastpage() }} trang</span>
<!--             <a href="{{route('exportxls',['model'=>'Sender'])}}" class="btn btn-success btn-sm" style="vertical-align: -webkit-baseline-middle;margin-left:20px">Xuất file</a> -->
        </div>
    </div>
    @if(isset($allsender) && count($allsender) > 0)
    <div class="wtable">
        <table id="example" class="table-responsive table table-bordered ">
							<thead>
								<tr class="danger">
									<!-- <th class="text-center">Id</th> -->
									<th class="text-center">Địa chỉ người gửi</th>
									<th class="text-center">Số lần spam </th>
									<th class="text-center">Ngày thêm</th>
									<!-- <th class="text-center">Ngày update gần nhất</th> -->
									<!-- <th class="text-center">Hành động</th> -->
								</tr>
							</thead>
													<tbody>
								@foreach($allsender as $sender)
							<tr class="even">
								<!-- <td align="center"><strong>{{ $sender->sender_id }}</strong></td> -->
								<td align="center"><strong>{{ $sender->from }}</strong></td>
								<td align="center">{{ $sender->time }}</td>
								<td align="center">
                                <div style="width:100px;word-wrap:break-word;">
                                {{ $sender->created_at }}
                                </div>
                                </td>
								<!-- <td align="center">{{ $sender->updated_at }}</td> -->
							</tr>
							@endforeach
						</tbody></table>
    </div>
    {{ $allsender->links() }}
    @endif
</div>
</div>
@endsection