@extends('admin.layoutmaster')
@section('active')
@include('Email::active2')
@endsection
@section('content')
<div class="panel panel-primary">
<div class="panel-heading">
    <span>Danh sách luật phân loại thư rác</span>
    <a href="{{ route('addtype') }}" class="add">Thêm luật phân loại thư rác</a>
    <div class="clear"></div>
</div>
<div class="panel-body">
    @include('errors.showerr')
    <div class="wtable">
    @if(isset($types) && count($types) > 0)
		<table class="table table-bordered">
		<thead class="danger">
		<tr class="danger">
		<th class="text-center" >Tên nhóm</th>
		<th class="text-center" >Luật nhận dạng</th>
		<th class="text-center" >Tình trạng</th>
		<th class="text-center">Hành động</th>
		</tr>
		</thead>
		<tbody>
		@foreach($types as $type)
		<tr class="even ">
		<td align="center">{{  $type['name'] }}</td>
		<td align="left">
		@if( is_array($type['rude']))
		<code>
		@foreach($type['rude'] as $rude)
		Body =~ {{ $rude }}<br>
		@endforeach
		@else
		<code>
		Body =~ {{ $type['rude'] }}<br>
		</code>
		@endif
		</code>
		</td>
		<td align="center"> 
		@if($type['public'] == 0)
			<a class="glyphicon glyphicon-ok" style="color:green" href="{{ route('setpublictype',['id'=>$type['type_id']])}}">
			</a>
		@endif	
		</td>
		<td align="center">
		<a href="{{ route('edittype',['id'=>$type['type_id']])}}">Sửa</a>
		<a href="{{ route('delltype',['id'=>$type['type_id']])}}"> / Xóa</a>
		</td>
		</tr>
		@endforeach
		</tbody>
		</table>
		@else
		<h3>Luật phân loại trống</h3>
		@endif
    </div>
</div>
</div>
@endsection