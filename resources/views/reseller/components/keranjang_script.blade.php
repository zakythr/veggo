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