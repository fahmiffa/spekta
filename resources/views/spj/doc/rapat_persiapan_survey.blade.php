@extends('spj.doc.base')

@section('main')
<header>
    <center>
        <table style="width: 100%; border:none;">
            <tr>
                <td style="border:none"><img width="80" src="{{ gambar('color.png') }}" /></td>
                <td style="border:none" width="10"></td>
                <td width="100%" style="border:none;">
                    <p style="text-align: center">
                        <span style="font-weight: bold; font-size:1.5rem;text-wrap:none;">PEMERINTAH KABUPATEN TEGAL<br>
                            DINAS PEKERJAAN UMUM DAN PENATAAN RUANG
                            </span>
                        <br>Alamat : Jalan Cut Nyak Dien Telp. (0283) 6197673 – 6197503<br>
                        Kode Pos Slawi  52416                    
                    </p>
                </td>
            </tr>
        </table>
    </center>
</header>
<hr style="border: 1.5px solid black; margin-bottom: 2px;width: 100%">
<hr style="border: 1px thin black; margin-top: 0;width: 100%">

<table style="width: 100%; border:none">
    <tr>
        <td style="border:none" width="75">KEPADA YTH</td>
        <td style="border:none">:</td>
        <td style="border:none">KEPALA  DINAS PEKERJAAN UMUM DAN PENATAAN RUANG KAB. TEGAL</td>
    </tr>
    <tr>
        <td style="border:none">DARI</td>
        <td style="border:none">:</td>
        <td style="border:none">KEPALA BIDANG PENATAAN BANGUNAN, LINGKUNGAN, DAN TATA RUANG</td>
    </tr>
    <tr>
        <td style="border:none">NOMOR</td>
        <td style="border:none">:</td>
        <td style="border:none">NOTA DINAS</td>
    </tr>
    <tr>
        <td style="border:none">TANGGAL</td>
        <td style="border:none">:</td>
        <td style="border:none">{{strtoupper($tanggal)}}</td>
    </tr>
    <tr>
        <td style="border:none">PERIHAL</td>
        <td style="border:none">:</td>
        <td style="border:none">LAPORAN RAPAT PERSIAPAN SURVEY PERSETUJUAN BANGUNAN GEDUNG (PBG) DAN/ATAU SERTIFIKAT LAIK FUNGSI (SLF)</td>
    </tr>
    <tr>
        <td style="border:none">TEMBUSAN</td>
        <td style="border:none">:</td>
        <td style="border:none">ARSIP</td>
    </tr>
    <tr>
        <td style="border:none">LAMPIRAN</td>
        <td style="border:none">:</td>
        <td style="border:none">{{$doc->note}}</td>
    </tr>
</table>

<hr style="border: 1.2px solid black; margin-bottom: 1px;width: 100%">
<hr style="border: 1px thin black; margin-top: 0;width: 100%">

<div class="clearfix"></div> 
<div class="column">
    <h3>DISPOSISI</h3>
</div>
<div class="columns">
    <h3 style="text-align: center">LAPORAN</h3>
    <h4 style="margin-bottom: 0">a. Waktu & Tempat Pelaksanaan</h4>
    <p style="margin-bottom: 0;margin-top:0;margin-left:1rem;text-align:justify">Rapat Persiapan Survey Persetujuan Bangunan Gedung (PBG) dan/atau Sertifikat Laik Fungsi (SLF) dilaksanakan pada hari {{$hari}} bertempat di Ruang Rapat Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Tegal.</p>
    <br>
    <h4 style="margin-bottom: 0;margin-top:0;">b. Peserta Rapat Persiapan Survey </h4>
    <p style="margin-bottom: 0;margin-top:0;margin-left:1rem">
        Peserta Rapat Persiapan Survey adalah :
    </p>
    <ol style="margin-bottom: 0;margin-top:0;margin-left:2rem;padding:0">
        <li>Tim Profesi Ahli (TPA) DPUPR Kab. Tegal</li>
        <li>Tim Penilai Teknis (TPT) DPUPR Kab. Tegal</li>
        <li>Pemohon Persetujuan Bangunan Gedung (PBG) dan/atau Sertifikat Laik Fungsi (SLF)</li>
    </ol>
    <br>                         
    <h4 style="margin-bottom: 0;margin-top:0;">c. Dasar</h4>
    <div style="margin-bottom: 0;margin-top:0;margin-left:1rem;">
        {!! $template->field !!}
    </div>
    <br>
    <h4 style="margin-bottom: 0;margin-top:0;">d. Data Umum Pemohon</h4>
</div>
<div class="clearfix"></div> 
<div class="column">
</div>
<div class="columns">
    <ol style="margin-bottom: 0;margin-top:0; list-style-type: none;padding-left: 1rem;">
        @foreach($pemohon as $val)
            <li>
                <table style="border:none;width: 100%;">
                    <tr>
                        <td style="border:none;" width="2">{{$loop->iteration}}.</td>
                        <td style="border:none;" width="90">Nama</td>
                        <td style="border:none;">:</td>
                        <td style="border:none;">{{$val->pemohon}}</td>
                    </tr>
                    <tr>
                        <td style="border:none;"></td>
                        <td style="border:none;">Alamat</td>
                        <td style="border:none;">:</td>
                        <td style="border:none;">{{$val->alamat}}</td>
                    </tr>
                    <tr>
                        <td style="border:none;"></td>
                        <td style="border:none;">Nomor Permohonan</td>
                        <td style="border:none;">:</td>
                        <td style="border:none;">{{$val->reg}}</td>
                    </tr>
                    <tr>
                        <td style="border:none;"></td>
                        <td style="border:none;">Nama Bangunan</td>
                        <td style="border:none;">:</td>
                        <td style="border:none;">{{$val->proyek}}</td>
                    </tr>
                    <tr>
                        <td style="border:none;"></td>
                        <td style="border:none;">Lokasi Bangunan</td>
                        <td style="border:none;">:</td>
                        <td style="border:none;">{{$val->lokasi}}, 
                            Desa/Kel. {{$val->doc->region->name}}, 
                            Kec. {{ $val->doc->region->kecamatan->name }}
                            Kab. Tegal, Prov. Jawa Tengah
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none;"></td>
                        <td style="border:none;">Fungsi Bangunan</td>
                        <td style="border:none;">:</td>
                        <td style="border:none;">{{ucfirst(str_replace("_"," ",$val->tipe))}}</td>
                    </tr>
                </table>
            </li>

            @if (($loop->iteration) % 11 == 0 OR $loop->iteration == 3)
                </ol>
            </div>
            <div class="page-break"></div>
            <div class="clearfix"></div> 
            <div class="column">
            </div>
            <div class="columns">
                <ol style="margin-bottom: 0;margin-top:0; list-style-type: none;padding-left: 1rem;">
            @endif
        @endforeach
    </ol>
</div> 
<div class="clearfix"></div> 
@if($pemohon->count() % 11 == 0)
    <div class="page-break"></div>
@endif
<div class="column">
</div>

<div class="columns">    
    <h4 style="margin-bottom: 0;margin-top:0;">e. Hasil Rapat Persiapan Survey </h4>
    <p style="margin-bottom: 0;margin-top:0;margin-left:0.8rem;text-align:justify">Hasil Rapat Persiapan Survey antara lain:</p>
    <ol style="margin-bottom: 0;margin-top:0;margin-left:2rem;padding:0;text-align:justify">
        <li style="text-align:justify">Berdasarkan hasil pengecekan melalui Sistem Informasi Manajemen Bangunan Gedung (SIMBG), persyaratan administrasi dan teknis atas nama pemohon-pemohon tersebut diatas dinyatakan lengkap dan dapat dipertimbangkan untuk dilakukan konsultasi/peninjauan lokasi;
        </li>
        <li>Pemohon agar menyiapkan dokumen asli berkas permohonan (dokumen administrasi dan teknis) untuk dilakukan verifikasi pada saat konsultasi/peninjauan lokasi;
        </li>
        <li>Pemohon menyiapkan 1 (satu) buah meterai sebagai kelengkapan persyaratan surat pernyataan terkait proses Persetujuan Bangunan Gedung (PBG) dan/atau Sertifikat Laik Fungsi (SLF) ;
        </li>
        <li>Pemohon diharapkan untuk menghadiri konsultasi/peninjauan lokasi sesuai jadwal yang telah ditentukan. Adapun jika pemohon berhalangan bisa diwakilkan dengan dilengkapi surat kuasa bermeterai;
        </li>
        <li>Peninjauan lokasi untuk pemohon Persetujuan Bangunan Gedung (PBG) dan/atau Sertifikat Laik Fungsi (SLF) tersebut diatas direncanakan  pada hari {{hari($doc->survey)}}.    
        </li>
    </ol>
    
    <br>
    <table style="border:none;width: 100%;">
        <tr>
            <td style="border:none;" width="80"></td>
            <td style="border:none;text-align:center;float-right">
                <div style="margin-left:2rem">
                    <p>Yang Melaporkan,<br>Kepala Bidang Penataan Bangunan, <br>Lingkungan, dan Tata Ruang</></p>
                    <br>
                    <br>
                    <br>
                    <p><span style="font-weight:bold;text-decoration:underline">{{$doc->pelapor->name}}</span><br>NIP. {{$doc->pelapor->nip}}</p>
                </div>
            </td>
        </tr>
    </table>
</div>
@endsection