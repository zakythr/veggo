@if(Auth::user())
    <a style="display:none;" id="buttonBerhasilModal" href="#berhasilModal" class="trigger-btn" data-toggle="modal">Click to Open Success Modal</a>
    <div id="berhasilModal" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h4>Berhasil!</h4>	
                    <p>Berhasil menambah item ke Keranjang.</p>
                    <button class="btn btn-success" data-dismiss="modal"><span>Lanjutkan Belanja</span> <i class="material-icons">&#xE5C8;</i></button>
                </div>
            </div>
        </div>
    </div>     
    <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah ke Keranjang</h5>
                    <button id="tutupItemModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="loader" align="center">
                        <br><br><br><br>
                        <div class="loading"></div>
                        <a>Harap Tunggu</a>
                    </div>
                    <div id="tambah_ubah_item_body">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="itemUbahModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Keranjang</h5>
                    <button id="tutupItemUbahModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="loader" align="center">
                        <br><br><br><br>
                        <div class="loading"></div>
                        <a>Harap Tunggu</a>
                    </div>
                    <div id="ubah_item_body">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade shopping-cart-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Keranjang Belanja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="tutup_lihat_keranjang">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="loader" align="center">
                        <br><br><br><br>
                        <div class="loading"></div>
                        <a>Harap Tunggu</a>
                    </div>
                    <div id="lihat_keranjang">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function tambahItem(id)
        {
            $('#tambah_ubah_item_body').empty();
            $('.loader').show();
            $.ajax({
                type: 'GET',
                url: 'Etalase/Tambah/'+id,
                success: function (data) {
                    $('#tambah_ubah_item_body').append(data);
                    $('.loader').hide();
                }
            });
        }
        function submitTambahItem(id)
        {
            var total_order = $('#total_order').val();
            var volume_order = $('#volume_order').val();

            $('#tambah_ubah_item_body').empty();
            $('.loader').show();
            $.ajax({
                type:'POST',
                url:'Etalase/Tambah/'+id,
                data:{
                    total_order     : total_order,
                    volume_order    : volume_order,
                    _token: '{{ csrf_token() }}',
                },
                success:function(data)
                {
                    if(data == 1)
                    {
                        $('#tutupItemModal').click()
                        $('.loader').hide();
                        $('#buttonBerhasilModal').click()
                    }
                }
            });
        }
        function ubahItem(id)
        {
            $('#tutup_lihat_keranjang').click();
            $('#itemUbahModal').modal('show');
            $('#ubah_item_body').empty();
            $('.loader').show();
            $.ajax({
                type: 'GET',
                url: 'Etalase/Ubah/'+id,
                success: function (data) {
                    $('#ubah_item_body').append(data);
                    $('.loader').hide();
                }
            });
        }
        function submitUbahItem(id)
        {
            var total_order = $('#total_order').val();
            var volume_order = $('#volume_order').val();

            $('#ubah_item_body').empty();
            $('.loader').show();
            $.ajax({
                type:'POST',
                url:'Etalase/Ubah/'+id,
                data:{
                    total_order     : total_order,
                    volume_order    : volume_order,
                    _token: '{{ csrf_token() }}',
                },
                success:function(data)
                {
                    if(data == 1)
                    {
                        $('#tutupItemUbahModal').click()
                        $('.loader').hide();
                        $('#buttonBerhasilModal').click()
                    }
                }
            });
        }
        function hapusItem(id)
        {
            $('#tambah_ubah_item_body').empty(function(){
                $('.loader').show();
            });
            $.ajax({
                type: 'GET',
                url: 'Etalase/Hapus/'+id,
                success: function (data) {
                    $('.loader').hide();
                    $('#tutupItemModal').click()
                    lihatItem()
                }
            });
        }
        function lihatItem()
        {
            $('#lihat_keranjang').empty();
            $('.loader').show();
            $.ajax({
                type: 'GET',
                url: 'Etalase/Lihat/',
                success: function (data) {
                    $('#lihat_keranjang').append(data);
                    $('.loader').hide();
                }
            });
        }
    </script>
@endif