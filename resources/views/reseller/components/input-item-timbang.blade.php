<input id="minimal" type="hidden" value="{{$data['barang']->bobot_minimum_timbang}}">
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
    <span class="badge badge-pill badge-success mb-1">Timbang</span>
</p>
<p class="text-muted text-small mb-2">Minimum Order :</p>
<p class="mb-3">{{$data['barang']->bobot_minimum_timbang}} Gram</p>
<p class="text-muted text-small mb-2">Jumlah yang Dipesan (Satuan Gram):</p>
<p class="mb-3">
    <span class="form-check">
        <input class="form-check-input" type="radio" name="gridRadios" id="total_orderr" name="total_order" value="300" >
        <label class="form-check-label" for="gridRadios2">
            300 Gram
        </label>
    </span>
    <span class="form-check">
        <input class="form-check-input" type="radio" name="gridRadios" id="total_orderr" name="total_order" value="500" >
        <label class="form-check-label" for="gridRadios2">
            500 Gram
        </label>
    </span>
    <span class="form-check">
        <input class="form-check-input" type="radio" name="gridRadios" id="total_orderr" name="total_order" value="1000" >
        <label class="form-check-label" for="gridRadios2">
            1000 Gram
        </label>
    </span>
    <span class="form-check">
        <input class="form-check-input" type="radio" name="gridRadios" id="lainnya" name="total_order" value="" >
        <label class="form-check-label" for="gridRadios2">
            Lainnya: <br/>
            <input type="number" min="100" name="lainnya" id="jumlahnya" value="" onkeyup="addValueToRadioBtn();"/><br/>
        </label>
    </span>
</p>

<br>
<div align="center">
    <button id="tambahh" class="btn btn-success" onclick="submitTambahItem('{{ $data['barang']->id }}')" disabled>Tambah ke Keranjang</button>
</div>

<script>  
    $('input:radio[id="total_orderr"]').on('change', function() {
        volume_orderr = $('#total_orderr:checked').val();
        if(volume_orderr==null){
            document.getElementById("tambahh").disabled = true;
        }
        else{
            document.getElementById("tambahh").disabled = false;
        }
    });

    $('input:radio[id="lainnya"]').on('change', function() {
        volume_orderr = $('#lainnya:checked').val();
        minimal=$('#minimal').val();
        
        if(volume_orderr==null || volume_orderr=="" ||parseInt(volume_orderr) < parseInt(minimal) ){
            document.getElementById("tambahh").disabled = true;
        }
        else{
            document.getElementById("tambahh").disabled = false;
        }
    }); 

    function addValueToRadioBtn() {
        document.getElementById("lainnya").value = document.getElementById("jumlahnya").value;
        volume_orderr = $('#lainnya:checked').val();
        volume_orderr2 = $('#total_orderr:checked').val();
        minimal=$('#minimal').val();
        
        if(volume_orderr==null || volume_orderr=="" || parseInt(volume_orderr) < parseInt(minimal)){
            if(volume_orderr2==null){
                document.getElementById("tambahh").disabled = true;
            }
            else{
                document.getElementById("tambahh").disabled = false;
            }
        }
        else{
            document.getElementById("tambahh").disabled = false;
        }
        
    } 
    
    </script>
    