@extends('penjual.layouts.app')
@section('title','Home')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                <form id="form_filter" action="{{url('Penjual/PreOrder/Filter')}}" method="POST">
                @csrf
                    <div class="row p-3">
                        <div class="col-7">
                            <h1 class="card-title"><strong>Report Reseller Tanggal | {{Carbon\Carbon::parse($data['filter_tanggal'])->format('D, d M Y')}}</strong></h1>
                        </div>
                        <div class="col-5">
                            <select name="tanggal" class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                <option selected disabled>Tanggal Order Barang</option>
                                @foreach ($data['tanggal'] as $tanggal)
                                    @if ($tanggal->tanggal_pre_order == $data['filter_tanggal'])
                                        <option value="{{$tanggal->tanggal_pre_order}}" selected >{{Carbon\Carbon::parse($tanggal->tanggal_pre_order)->format('D, d M Y')}} ({{$tanggal->total}})</option>
                                    @else            
                                        <option value="{{url('/Penjual/ReportReseller/'.$tanggal->tanggal_pre_order)}}" >{{Carbon\Carbon::parse($tanggal->tanggal_pre_order)->format('D, d M Y')}} ({{$tanggal->total}})</option>
                                    @endif
                                @endforeach
                            </select>
                        
                       
                    </div>
                </div>
                </form>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                @foreach ($data['reseller'] as $key => $item)
                <div class="border">
                    <a class="btn btn-link" href="{{url('Penjual/ReportResellerDetail/'.$data['filter_tanggal'].'/'.$item['id_user'])}}">
                        <h5>{{$item->user->name}}</h5>
                    </a>
                </div>    
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function applyFilter(){
        $('#form_filter').submit()
    }
</script>
    
@endsection
