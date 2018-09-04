@extends('admin.layoutmaster')
@section('active')
@include('User::active')
@endsection
@section('content')
<div class="panel panel-primary">

<div class="panel-heading">
<h4>
Danh sách thư rác do người dùng phản hồi
</h4>
</div>

<div class="panel-body">
@include('errors.showerr')
<!-- <a href="{{route('getmailimap')}}" class="btn btn-success" >Lấy mail</a> -->
@if(isset($emails) && count($emails) > 0)
<!-- BEGIN: CONTENT -->
<table class="table table-responsive table-bordered mb">

<thead>
<tr class="danger">
<th  class="text-center">Nguồn</th>
<th  class="text-center">Tiêu đề</th>
<th  class="text-center">Thời gian</th>
<th  class="text-center">Xác nhận</th>
</tr>
</thead>

<tbody>
@foreach($emails as $key=> $value)
<tr>
<td align="left">{{config('filesystems.collect')[$value->collect_id]}}</td>
<td align="center" style="word-break:break-all;">
<a href="{{ route('detailemail',['id'=>$value->id])}}">
<strong>{{empty($value->subject)?"Title":$value->subject}}
</strong>
</a>
</td>
<td align="left">{{ $value->created_at }}</td>
<td align="center" style="word-break:break-all;">
<a onclick="return confirm('Bạn có muốn xác định đây là thư rác không? ');" href="{{route('publicmail',['id'=>$value->id])}}"><i class="glyphicon glyphicon-ok" style="color:#51C44B"></i></a>

<a onclick="return confirm('Bạn có muốn xác nhận đây không phải thư rác không? ');" href="{{route('dellemail',['id'=>$value->id])}}"><i class="glyphicon glyphicon-remove" style="color:#DD2828"></i></a>
</td>							
</tr>
</tbody>
@endforeach
</table>
{{ $emails->links() }}
@else
<h3>Danh sách xác thực trống</h3>
@endif
</div>
</div>
@endsection