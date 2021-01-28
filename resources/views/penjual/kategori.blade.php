@extends('penjual.layouts.app')
@section('title','Home')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="row p-2">
                        <div class="col-6">
                            <h1 class="card-title"><strong>Kategori Produk</strong></h1>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-sm btn-success float-right" data-toggle="modal"
                            data-target="#exampleModal">
                                Tambah
                            </button>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{url('/Penjual/Kategori/Tambah')}}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{Uuid::generate(4)}}">
                                        <div class="form-group">
                                            <label for="">Kategori</label>
                                            <select class="form-control select2-single" name="kategori" required>
                                                    <option label="&nbsp;" disabled selected>&nbsp;</option>
                                                    @foreach ($data['baseKategoris'] as $key => $baseKategori)
                                                        <option value="{{$baseKategori->id}}">{{$baseKategori->kategori}}</option>
                                                    @endforeach
                                            </select>                                            
                                        </div>
                                        <div class="form-group">
                                            <label for="">Sub Kategori</label>
                                            <input type="text" placeholder="Nama Produk" name="sub_kategori" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table  id="customers">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kategori</th>
                                    <th>Sub Kategori</th>
                                    <th>Proses</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['kategoris'] as $key => $kategori)
                                    <tr >
                                        <td>{{$key+1}}</td>
                                        <td>{{$kategori->baseKategori['kategori']}}</td>
                                        <td>{{$kategori->sub_kategori}}</td>
                                        <td style="width: 20%">
                                            <div class="text-center">
                                            <div class="btn-group" >
                                                <div>
                                                    <button type="button" class="btn btn-xs btn-success m-1" data-toggle="modal"
                                                    data-target="#updateModal_{{$key}}">
                                                        Ubah
                                                    </button>  
                                                </div>
                                                <form action="{{url('/Penjual/Kategori/Hapus')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$kategori->id}}">
                                                    <button type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus ?');" class="btn btn-xs btn-danger m-1">Hapus</button>
                                                </form>                                  
                                            </div>    
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="updateModal_{{$key}}" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Update Kategori</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{url('/Penjual/Kategori/Ubah')}}">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{$kategori->id}}">
                                                        <div class="form-group">
                                                            <label for="">Kategori</label>
                                                            <select class="form-control select2-single" name="kategori" required>
                                                                <option label="&nbsp;" disabled selected>&nbsp;</option>
                                                                @foreach ($data['baseKategoris'] as $baseKategori)
                                                                    @if($baseKategori->id == $kategori->kategori)
                                                                        <option selected value="{{$baseKategori->id}}">{{$baseKategori->kategori}}</option>
                                                                    @else
                                                                        <option value="{{$baseKategori->id}}">{{$baseKategori->kategori}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>                                            
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Sub Kategori</label>
                                                            <input type="text" placeholder="Nama Produk" name="sub_kategori" value="{{$kategori->sub_kategori}}" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    #customers {
      font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }
    
    #customers td, #customers th {
      border: 1px solid #ddd;
      padding: 8px;
    }
    
    #customers tr:nth-child(even){background-color: #f2f2f2;}
    
    #customers tr:hover {background-color: #ddd;}
    
    #customers th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #4CAF50;
      color: white;
    }
    </style>
@endsection
