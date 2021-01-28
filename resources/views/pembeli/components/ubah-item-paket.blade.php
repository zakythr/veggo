<p class="text-muted text-small mb-2">Nama Produk :</p>
<p class="mb-3">{{ $data['barang']->nama }}</p>
<p class="text-muted text-small mb-2">Harga :</p>
<p class="mb-3">Rp. {{ number_format($data['barang']->harga_jual,0,',','.') }},-</p>
<p class="text-muted text-small mb-2">Jenis Produk :</p>
<p class="mb-3">
    <span class="badge badge-pill badge-success mb-1">Paket</span>
</p>
<p class="text-muted text-small mb-2">Isi Paket :</p>
<p class="mb-3">
    @foreach($data['isiPaket'] as $isiPaket)
        <span class="badge badge-pill badge-success mb-1">{{$isiPaket->volume}} {{$isiPaket->satuan}} x {{$isiPaket->nama_barang}} </span><br>
    @endforeach
</p>
<p class="text-muted text-small mb-2">Jumlah yang Dipesan :</p>
<p class="mb-3">
    <input type="number" class="form-control" min="1" max="50" id="total_order" value="{{$data['isi_keranjang']->volume}}" hidden="true">
    <div class="btn-group" role="group" aria-label="Basic example">
        <button type="button" class="btn  btn-sm btn-danger" id="kurang_total_order" onclick="kurangButton()">-</button>
        <button type="button" class="btn  btn-sm btn-light" id="show_total_order">{{$data['isi_keranjang']->volume}}</button>
        <button type="button" class="btn  btn-sm btn-success" id="tambah_total_order" onclick="tambahButton()">+</button>
    </div>
</p>
<br>
<div align="center">
    <button class="btn btn-success" onclick="submitUbahItem('{{ $data['barang']->id }}')">Ubah Keranjang</button>
</div>