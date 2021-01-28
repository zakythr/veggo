
<p class="text-muted text-small mb-2">Nama Produk :</p>
<p class="mb-3">{{ $data['barang']->nama }}</p>
<p class="text-muted text-small mb-2">Harga :</p>
@if($data['barang']->diskon>0)
    @if($data['barang']->diskon>100)
    <p class="mb-3">
        <span style="font-size: 0.7rem;background-color:red;color: white;padding: 0px 2px;font-weight: bold;">{{ number_format(($data['barang']->diskon/$data['barang']->harga_jual)*100) }}%</span>
        <span style="font-size: 0.7rem;" class="mr-2 price-dc"><strike>Rp. {{ number_format($data['barang']->harga_jual/10,0,',','.') }},-</strike></span><br>
        <b>Rp. {{ number_format(($data['barang']->harga_jual/10-$data['barang']->diskon/10),0,',','.') }},-</b> / {{ $data['barang']->bobot/10 }} Gram
    </p>    
    @else
    <p class="mb-3">
        <span style="font-size: 0.7rem;background-color:red;color: white;padding: 0px 2px;font-weight: bold;">{{$data['barang']->diskon}}%</span>
        <span style="font-size: 0.7rem;" class="mr-2 price-dc"><strike>Rp. {{ number_format($data['barang']->harga_jual/10,0,',','.') }},-</strike></span><br>
        <b>Rp. {{ number_format(($data['barang']->harga_jual-($data['barang']->harga_jual*($data['barang']->diskon/100)))/10,0,',','.') }},-</b> / {{ $data['barang']->bobot/10 }} Gram
    </p>    
    @endif
@else
<p class="mb-3">Rp. {{ number_format($data['barang']->harga_jual/10,0,',','.') }},-</b> / {{ $data['barang']->bobot/10 }} Gram</p>
@endif
<p class="text-muted text-small mb-2">Jenis Produk :</p>
<p class="mb-3">
    <span class="badge badge-pill badge-success mb-1">Kemas</span>
</p>
<p class="text-muted text-small mb-2">Bobot yang Dipesan :</p>
<p class="mb-3">
    @foreach($data['bobot_kemasan'] as $kemas)
        <span class="form-check">
            <input class="form-check-input" type="radio" name="gridRadios" id="volume_order" name="volume_order" value="{{ $kemas->bobot_kemasan }}" >
            <label class="form-check-label" for="gridRadios2">
                {{ $kemas->bobot_kemasan }} Gram
            </label>
        </span>
    @endforeach
</p>
<p class="text-muted text-small mb-2">Jumlah :</p>
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
    <button id="tambahh" class="btn btn-success" onclick="submitTambahItem('{{ $data['barang']->id }}')">Tambah ke Keranjang</button>
</div>

<script>
    var volume_orderr = $('#volume_order:checked').val();

    console.log(volume_orderr);
    if(volume_orderr==null){
        document.getElementById("tambahh").disabled = true;
    }
    else{
        document.getElementById("tambahh").disabled = false;
    }
    $('input:radio[id="volume_order"]').on('change', function() {
        volume_orderr = $('#volume_order:checked').val();
        if(volume_orderr==null){
            document.getElementById("tambahh").disabled = true;
        }
        else{
            document.getElementById("tambahh").disabled = false;
        }
    });    
</script>