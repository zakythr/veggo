<div class="form-row pakets" id="paket_item_{{$data['jumlah']}}">
    <div class="form-group col-md-4">
    <select id="select_ke_{{$data['jumlah']}}" name="isi_paket[]" class="form-control" onchange="getTotal({{$data['jumlah']}},0)">
                <option selected disabled>Pilih Produk</option>
            @foreach($data['produk'] as $produk)
                <option value="{{$produk->id}}">
                    {{$produk->nama}}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">
        <input id="volume_ke_{{$data['jumlah']}}" type="number" placeholder="Volume Gram" name="volume_isi_paket[]" onkeyup="getTotal({{$data['jumlah']}},1)" class="form-control volume_akhir">
    </div>
    <div class="form-group col-md-4">
        <input id="harga_ke_{{$data['jumlah']}}" type="number" readonly placeholder="Harga" name="harga_isi_paket[]" class="form-control harga_akhir">
    </div>
</div>