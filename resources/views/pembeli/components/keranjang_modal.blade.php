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