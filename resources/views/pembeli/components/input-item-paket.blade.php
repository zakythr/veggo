<p class="text-muted text-small mb-2">Nama Produk :</p>
<p class="mb-3">{{ $data['barang']->nama }}</p>
<p class="text-muted text-small mb-2">Harga :</p>
@if($data['barang']->diskon>0)
    @if($data['barang']->diskon>100)
    <p class="mb-3">
        <span style="font-size: 0.7rem;background-color:red;color: white;padding: 0px 2px;font-weight: bold;">{{ number_format(($data['barang']->diskon/$data['barang']->harga_jual)*100) }}%</span>
                                    <span style="font-size: 0.7rem;" class="mr-2 price-dc"><strike>Rp. {{ number_format($data['barang']->harga_jual,0,',','.') }},-</strike></span><br>
                                    <b>Rp. {{ number_format(($data['barang']->harga_jual-$data['barang']->diskon),0,',','.') }},-</b>
                                    / {{ $data['barang']->bobot }} {{ $data['barang']->satuan }}
    </p>
    @else
    <p class="mb-3">
        <span style="font-size: 0.7rem;background-color:red;color: white;padding: 0px 2px;font-weight: bold;">{{$data['barang']->diskon}}%</span>
        <span style="font-size: 0.7rem;" class="mr-2 price-dc"><strike>Rp. {{ number_format($data['barang']->harga_jual,0,',','.') }},-</strike></span><br>
        <b>Rp. {{ number_format(($data['barang']->harga_jual-($data['barang']->harga_jual*($data['barang']->diskon/100))),0,',','.') }},-</b> / {{ $data['barang']->bobot }} {{ $data['barang']->satuan }}
    </p>
    @endif
@else
<p class="mb-3">Rp. {{ number_format($data['barang']->harga_jual,0,',','.') }},-</b> / {{ $data['barang']->bobot }} {{ $data['barang']->satuan }}</p>
@endif
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
    <input type="number" min="1" max="50" id="total_order" value=1 hidden="true">
    <div class="btn-group" role="group" aria-label="Basic example">
        <button type="button" class="btn  btn-sm btn-light" id="kurang_total_order" onclick="kurangButton()">-</button>
        <button type="button" class="btn  btn-sm btn-light" id="show_total_order">1</button>
        <button type="button" class="btn  btn-sm btn-light" id="tambah_total_order" onclick="tambahButton()">+</button>
    </div>
</p>
<br>
<div align="center">
    <button class="btn btn-success" onclick="submitTambahItem('{{ $data['barang']->id }}')">Tambah ke Keranjang</button>
</div>