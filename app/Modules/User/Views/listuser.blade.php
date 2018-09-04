@extends('admin.layoutmaster')
@section('active')
@include('User::active')
@endsection
@section('content')
<div class="panel panel-primary">
<div class="panel-heading">
    <span>Danh sách người dùng</span>
    <a href="{{route('addUser')}}" class="add">Thêm mới</a>
    <div class="clear"></div>
</div>
<div class="panel-body">
    @include('errors.showerr')
    @if(isset($users) && count($users) > 0 )
    <div class="wtable">
			<table id="example" class=" table table-bordered">
							<thead>
								<tr class="danger">
									<th class="text-center">Id</th>
									<th class="text-center">Tên</th>
									<th class="text-center">Email</th>
									<th class="text-center">Quyền</th>
									<th class="text-center">Hành động</th>
								</tr>
							</thead>
							<tbody>
							@foreach($users as $user)
								<tr class="even">
								<td align="center"> <strong>{{$user->id}}</strong> </td>
								<td align="">{{$user->name}}</td>
								<td>{{$user->email}}</td>
								<td>@if($user->type == 1) Quản trị hệ thống @else Quản trị viên @endif</td>
								<td align="center">
									<!-- <a href="http://103.28.173.237/account/edit?id=1">Sửa</a>
									&nbsp; -->
									@if(($user->id == Session::get('user')['id']) || (Session::get('user')['type'] == 1 && $user->type != 1) )
									<a href="{{route('delUser',['id'=>$user->id])}}" onclick="return confirm('Bạn có muốn xóa tài khoản này không? ');">Xóa</a>
									&nbsp;
									@else
<!-- 									<span class="glyphicon glyphicon-remove" style="color:red"></span> -->
									@endif
								</td>
							</tr>
						@endforeach		
				</tbody>
			</table>
    </div>
    @endif
</div>
</div>
@endsection