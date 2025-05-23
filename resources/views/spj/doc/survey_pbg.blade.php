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
                        <br>Alamat : Jalan Cut Nyak Dien Telp. (0283) 6197673 – 6197503
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
        <td style="border:none">KEPALA BIDANG PENATAAN BANGUNAN, LINGKUNGAN, DAN TATA RUANGL</td>
    </tr>
    <tr>
        <td style="border:none">NOMOR</td>
        <td style="border:none">:</td>
        <td style="border:none">NOTA DINAS</td>
    </tr>
    <tr>
        <td style="border:none">TANGGAL</td>
        <td style="border:none">:</td>
        <td style="border:none">{{$tanggal}}</td>
    </tr>
    <tr>
        <td style="border:none">PERIHAL</td>
        <td style="border:none">:</td>
        <td style="border:none">LAPORAN  PENINJAUAN LOKASI PERSETUJUAN BANGUNAN GEDUNG (PBG) DAN/ATAU SERTIFIKAT LAIK FUNGSI (SLF)</td>
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
    <p style="margin-bottom: 0;margin-top:0;margin-left:1rem;text-align:justify">Peninjauan Lokasi Persetujuan Bangunan Gedung (PBG) dan/atau Sertifikat Laik Fungsi (SLF) dilaksanakan pada {{$hari}},  bertempat di Lokasi Bangunan Gedung</p>
    <br>
    <h4 style="margin-bottom: 0;margin-top:0;">b. Peserta Peninjauan Lokasi </h4>
    <p style="margin-bottom: 0;margin-top:0;margin-left:1rem;text-align:justify">
    Peserta  Peninjauan Lokasi Persetujuan Bangunan Gedung (PBG) dan/atau Sertifikat Laik Fungsi (SLF) dari Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Tegal adalah :
    </p>
    <ol style="margin-bottom: 0;margin-top:0;margin-left:2rem;padding:0">
        @if($doc->extend)
            @php
                $extend = json_decode($doc->extend);
            @endphp
            @foreach($extend as $ex)
                <li>{{$ex}}</li>
            @endforeach
        @endif
    </ol>
    <br>      
    <h4 style="margin-bottom: 0;margin-top:0;">c. Dasar</h4>
    <div style="margin-bottom: 0;margin-top:0;margin-left:1rem">
        {!! $template->field !!}
    </div>
    <br>
    <h4 style="margin-bottom: 0;margin-top:0;">d. Data Umum Pemohon PBG</h4>
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
                        <td style="border:none;">Fungsi Bangunan</td>
                        <td style="border:none;">:</td>
                        <td style="border:none;">{{ucfirst(str_replace("_"," ",$val->tipe))}}</td>
                    </tr>
                    <tr>
                        <td style="border:none;"></td>
                        <td style="border:none;">Luas Bangunan</td>
                        <td style="border:none;">:</td>
                        <td style="border:none;">@if($val->attach) {{$val->attach->luas}} @endif</td>
                    </tr>
                </table>
            </li>

            @if (($loop->iteration) % 11 == 0 OR $loop->iteration == 4)
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
    <h4 style="margin-bottom: 0;margin-top:0;">e. Hasil Peninjauan Lokasi </h4>
    <ol style="margin-bottom: 0;margin-top:0;margin-left:1.8rem;padding:0">
        <li>Pemohon menyatakan seluruh data, dokumen (administrasi dan teknis), informasi dan keterangan atas permohonan Persetujuan Bangunan Gedung (PBG) dan/atau Sertifikat Laik Fungsi (SLF) adalah benar dan tidak dalam sengketa;
        </li>
        <li>Luas bangunan yang disetujui adalah luasan bangunan yang sesuai / tidak bertentangan dengan ketentuan tata bangunan yang berlaku dan/atau direkomendasikan (GSB, KDB, KDH, KLB, dan sebagainya).
        </li>
        <li>Terhadap bangunan yang tidak disetujui / tidak sesuai dengan ketentuan yang berlaku, maka Pemohon / Pemilik Bangunan bersedia melakukan penyesuaian bangunan sesuai peraturan perundang-undangan yang berlaku,
        </li>
        <li>Dalam hal Pemohon / Pemilik Bangunan tidak melakukan penyesuaian bangunan sesuai ketentuan, maka Pemohon / Pemilik Bangunan bersedia menerima sanksi sesuai peraturan perundang-undangan yang berlaku.
        </li>
        <li>Pemohon / Pemilik Bangunan bersedia melaksanakan pembangunan dan perawatan bangunan sesuai standar teknis.  
        </li>
        <li>Apabila dikemudian hari terdapat ketidaksesuaian terhadap dokumen administrasi dan/atau dokumen teknis yang diajukan maka PBG dan/atau SLF akan dievaluasi kembali dan Pemohon bersedia menerima konsekuensi dan/atau sanksi sesuai peraturan perundang-undangan yang berlaku serta retribusi yang telah dibayarkan tidak dapat diminta kembali
        </li>
        <li>Apabila dikemudian hari terdapat ketidaksesuaian terhadap dokumen administrasi dan/atau dokumen teknis yang diajukan maka PBG dan/atau SLF akan dievaluasi kembali dan Pemohon bersedia menerima konsekuensi dan/atau sanksi sesuai peraturan perundang-undangan yang berlaku serta retribusi yang telah dibayarkan tidak dapat diminta kembali
        </li>
        <li>Adapun pemohon-pemohon dan/atau perwakilan pemohon yang tidak dapat ditemui di lokasi bangunan gedung dilakukan penjadwalan ulang terhadap Peninjauan Lokasi Persetujuan Bangunan Gedung (PBG) dan/atau Sertifikat Laik Fungsi (SLF)  
        </li>
    </ol>
    <p style="margin-bottom: 0;margin-top:0;margin-left:0.8rem">Demikian laporan ini Kami sampaikan, atas perhatian dan arahannya kami ucapkan terima kasih.
    </p>
    
    <br>
    <table style="border:none;width: 100%;">
        <tr>
            <td style="border:none;" width="80"></td>
            <td style="border:none;text-align:center;float-right">
                <div style="margin-left:2rem">
                    <p>Yang Melaporkan</p>
                    <br>
                    <br>
                    <p><b>{{$doc->pelapor->name}}</b></p>
                </div>
            </td>
        </tr>
    </table>
</div>
@endsection