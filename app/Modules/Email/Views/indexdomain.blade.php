@extends('admin.layoutmaster')

@section('active')
@include('Email::active')
@endsection
@section('content')
<div class="panel panel-primary">
<div class="panel-heading">
    <span>Quản lí địa chỉ DOmain phát tán thư rác</span>
    <!-- <a href="" class="add">Thêm mới</a> -->
    <div class="clear"></div>
</div>
<div class="panel-body">
    @include('errors.showerr')
  <div class="row form">
        <div class="col-md-6">
            <form class="form-inline" action="" method="">
                <div class="form-group">
                    <span>Truy vấn địa chỉ domain</span>
                    <input type="text" name="search" class="form-control" placeholder="Search" value="{{ $search }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Truy Vấn</button>
            </form>
        </div>
        <div class="col-md-6 text-right">
            <span style="vertical-align: -webkit-baseline-middle;">Kết quả hiện thị trang {{ $alldomain->currentPage() }} trong tổng số {{ $alldomain->lastpage() }} trang</span>
<!--             <a href="{{route('exportxls',['model'=>'Domain'])}}" class="btn btn-success btn-sm" style="vertical-align: -webkit-baseline-middle;margin-left:20px">Xuất file</a> -->
        </div>
    </div>
    @if(isset($alldomain) && count($alldomain) > 0)
    <div class="wtable">
        <table id="example" class="table-responsive table table-bordered ">
							<thead>
								<tr class="danger">
									<!-- <th class="text-center">Id</th> -->
									<th class="text-center">Địa chỉ domain</th>
									<th class="text-center">Số lần spam</th>
									<th class="text-center">Ngày thêm</th>
									<!-- <th class="text-center">Ngày update gần nhất</th> -->
								</tr>
							</thead>
							@foreach($alldomain as $domain)
								<tr class="even">
								<!-- <td align=""><strong>{{$domain->domain_id}}</strong></td> -->
								<td align="center"><strong>{{$domain->domain}}</strong></td>
								<td align="center">{{$domain->time}}</td>
								<td align="center">
                                <div style="width:100px;word-wrap:break-word;">
                                {{$domain->created_at}}
                                </div>
                                </td>
								<!-- <td align="center">{{$domain->updated_at}}</td> -->
							</tr>
							@endforeach
												
	</tbody>
    </table>
    </div>
    {{ $alldomain->links() }}
    @endif
</div>
</div>
@endsection