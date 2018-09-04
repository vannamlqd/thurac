@extends('admin.layoutmaster')
@section('active')
<form action="" class="form">
    {{ csrf_field() }}
    <div class="form-group">
        <input placeholder="dd/mm/yy" type="text" name="datefrom" class="form-control date"  value="{{ $data['datefrom'] }}">
    </div>
    <div class="form-group">
        <input placeholder="dd/mm/yy" type="text" name="dateto" class="form-control date"  value="{{ $data['dateto'] }}">
    </div>
    <div class="form-group">
        <select class="form-control" name="collect">
            <option value="0">Nguồn thu thập</option>
            @foreach($origins as $key => $value)
            <option value="{{ $value->origin_id }}"
            @if($data['collect'] == $value->origin_id)
                selected
            @endif 
            >{{ $value->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <input placeholder="Email người gửi" type="text" name="from" class="form-control"  value="{{ $data['from'] }}">
    </div>
    <div class="form-group">
        <input placeholder="Ip" type="text" name="ip" class="form-control" value="{{ $data['ip'] }}">
    </div>
    <div class="form-group">
        <select class="form-control" name="status">
            <option value="0">Loại email</option>
            @foreach(config('filesystems.status') as $key => $value)
            <option value="{{ $key }}"
            @if($data['status'] == $key)
                selected
            @endif  
            >{{ $value }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary btn-sm">Truy Vấn</button>
</form>
@endsection
@section('content')
<div class="panel panel-primary">
<div class="panel-heading">
    <span>Quản lí địa chỉ phát tán thư rác</span>
    <a href="{{route('csvchoose')}}" class="add">Thêm mới</a>
    <div class="clear"></div>
</div>
<div class="panel-body">
    @include('errors.showerr')
    <div class="row form">
        <div class="col-md-6 ">
            <span style="vertical-align: -webkit-baseline-middle;">Kết quả hiện thị trang {{ $allemail->currentPage() }} trong tổng số {{ $allemail->lastpage() }} trang</span>
            <a href="{{route('exportxls',['model'=>'Email'])}}" class="btn btn-success btn-sm" style="vertical-align: -webkit-baseline-middle;margin-left:20px">Xuất file</a>
        </div>
    </div>
    <div class="wtable table-responsive">
        @if(isset($allemail) && count($allemail) > 0 )
        <table class=" table table-bordered ">
            <thead>
                <tr class="danger">
                    <!-- <th class="text-center">STT</th> -->
                    <!-- <th  class="text-center">Ip</th> -->
                    <!-- <th  class="text-center">Domain</th> -->
                    <!-- <th class="text-center">File đính kèm</th> -->
                    <th  class="text-center">Nguồn</th>
                    <th  class="text-center">Người gửi</th>
                    <!-- <th  class="text-center">Người nhận</th> -->
                    <th  class="text-center">Tiêu đề</th>
                    <th class="text-center">Nội dung</th>
                    <th class="text-center">Địa chỉ Ip</th>
                    <th class="text-center">Ngày thêm</th>
                    <th class="text-center">Loại</th>
                    <th  class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allemail as $email)
                <tr class="even">
                    <!-- <td align="center"><strong>{{ $email->id }}</strong></td> -->
                    
                    <td align="center">
                    <div style="width:110px;word-wrap:break-word;">    
                    {{config('filesystems.collect')[$email->collect_id]}}
                    </div>
                    </td>
                    <td align="center"><div style="width:200px;word-wrap:break-word;"><strong>{{ $email->email }}</strong></div></td>
                    <!-- <td align="center">{{ $email->ip }}</td> -->
                    <!-- <td align="center">{{ $email->domain }}</td> -->
                    <!-- <td align="center">{{ $email->to }}</td> -->
                    <td align="center">
                    <div style="width:150px;word-wrap:break-word;"><a href="{{ route('detailemail',['id' => $email->id])}}">{{ empty($email->subject)?'Title':$email->subject }}</a></div></td>
                    <td align="center">
                    <div class="modal fade  bs-example-modal-lg" id="myModal{{ $email->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Nội dung email</h4>
                    </div>
                    <div class="modal-body">
                    {{ $email->description }}
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                    </div>
                    </div>    
        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal{{ $email->id }}">Hiển thị</button>
                    </div>
                    </td>
                    <!-- <td align="center">{{ $email->attachment }}</td> -->
                    <td align="center">{{ $email->ip }}</td>
                    <td align="center">
                    <div style="width:80px;word-wrap:break-word;">
                    {{ date('d/m/y', strtotime($email->created_at)) }}
                    </div>
                    </td>
                    <td align="center">
                        {{config('filesystems.status')[$email->status]}}
                    </td>

                    <td align="center">
                        <!-- <a href="{{route('editemail',['id'=>$email->id])}}">Sửa</a> &nbsp; -->
                        <a href="{{route('dellemail',['id'=>$email->id])}}" onclick="return confirm('Bạn có muốn xóa email này không?')">Xóa</a> &nbsp;
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
                @else
                <h3>Không tìm thấy email nào</h3>
            @endif
    </div>
    {{ $allemail->links() }}
</div>
</div>
@endsection