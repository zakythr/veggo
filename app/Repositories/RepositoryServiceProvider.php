<?php

namespace App\Repositories;


use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\SampleDatabaseInterface;
use App\Repositories\Interfaces\BaseKategoriInterface;
use App\Repositories\Interfaces\BarangInterface;
use App\Repositories\Interfaces\KategoriInterface;
use App\Repositories\Interfaces\AlamatInterface;
use App\Repositories\Interfaces\IsiPaketInterface;
use App\Repositories\Interfaces\KeranjangInterface;
use App\Repositories\Interfaces\DetailKeranjangInterface;
use App\Repositories\Interfaces\BobotKemasanInterface;
use App\Repositories\Interfaces\ResepInterface;
use App\Repositories\Interfaces\IsiResepInterface;
use App\Repositories\Interfaces\HariPengirimanInterface;
use App\Repositories\Interfaces\DetailTransaksiInterface;
use App\Repositories\Interfaces\EtalaseInterface;
use App\Repositories\Interfaces\FotoProdukInterface;
use App\Repositories\Interfaces\ProdukGroupInterface;
use App\Repositories\Interfaces\ProdukKemasanInterface;
use App\Repositories\Interfaces\KlaimInterface;
use App\Repositories\Interfaces\DetailKlaimInterface;
use App\Repositories\Interfaces\UserInterface;
use App\Repositories\Interfaces\ProdukInterface;
use App\Repositories\Interfaces\InventarisInterface;
use App\Repositories\Interfaces\TanggalInterface;
use App\Repositories\Interfaces\KeranjangResellerInterface;
use App\Repositories\Interfaces\ParentKeranjangResellerInterface;
use App\Repositories\Interfaces\BarangTanggalInterface;

use App\Repositories\SampleDatabaseRepository;
use App\Repositories\BaseKategoriRepository;
use App\Repositories\BarangRepository;
use App\Repositories\KategoriRepository;
use App\Repositories\AlamatRepository;
use App\Repositories\IsiPaketRepository;
use App\Repositories\KeranjangRepository;
use App\Repositories\KlaimRepository;
use App\Repositories\DetailKlaimRepository;
use App\Repositories\DetailKeranjangRepository;
use App\Repositories\BobotKemasanRepository;
use App\Repositories\ResepRepository;
use App\Repositories\IsiResepRepository;
use App\Repositories\HariPengirimanRepository;
use App\Repositories\DetailTransaksiRepository;
use App\Repositories\UserRepository;
use App\Repositories\InventarisRepository;
use App\Repositories\TanggalRepository;
use App\Repositories\KeranjangResellerRepository;
use App\Repositories\ParentKeranjangResellerRepository;
use App\Repositories\BarangTanggalRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(

            SampleDatabaseInterface::class,
            SampleDatabaseRepository::class,
            
            BaseKategoriInterface::class,
            BaseKategoriRepository::class,

            BarangInterface::class,
            BarangRepository::class,

            KategoriInterface::class,
            KategoriRepository::class,

            AlamatInterface::class,
            AlamatRepository::class,

            IsiPaketInterface::class,
            IsiPaketRepository::class,

            KeranjangInterface::class,
            KeranjangRepository::class,

            DetailKeranjangInterface::class,
            DetailKeranjangRepository::class,

            BobotKemasanInterface::class,
            BobotKemasanRepository::class,

            ResepInterface::class,
            ResepRepository::class,

            IsiResepInterface::class,
            IsiResepRepository::class,

            HariPengirimanInterface::class,
            HariPengirimanRepository::class,

            DetailTransaksiInterface::class,
            DetailTransaksiRepository::class,

            InventarisInterface::class,
            InventarisRepository::class,

            KlaimInterface::class,
            KlaimRepository::class,

            DetailKlaimInterface::class,
            DetailKlaimRepository::class,

            TanggalInterface::class,
            TanggalRepository::class,

            KeranjangResellerInterface::class,
            KeranjangResellerRepository::class,

            ParentKeranjangResellerInterface::class,
            ParentKeranjangResellerRepository::class,

            BarangTanggalInterface::class,
            BarangTanggalRepository::class
        );
    }
}

?>