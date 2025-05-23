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
<hr style="border: 1.5px solid black; margin-bottom: 2px;">
<hr style="border: 1px thin black; margin-top: 0;">

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
        <td style="border:none">LAPORAN RAPAT PLENO PERSETUJUAN BANGUNAN GEDUNG (PBG)  DAN/ATAU SERTIFIKAT LAIK FUNGSI (SLF)</td>
    </tr>
    <tr>
        <td style="border:none">TEMBUSAN</td>
        <td style="border:none">:</td>
        <td style="border:none">ARSIP</td>
    </tr>
    <tr>
        <td style="border:none">LAMPIRAN</td>
        <td style="border:none">:</td>
        <td style="border:none">{!! $doc->note !!}</td>
    </tr>
</table>

<hr style="border: 1.2px solid black; margin-bottom: 1px;">
<hr style="border: 1px thin black; margin-top: 0;">

<div class="clearfix"></div> 
<div class="column">
    <h3>DISPOSISI</h3>
</div>
<div class="columns">
    <h3 style="text-align: center">LAPORAN</h3>
    <h4 style="margin-bottom: 0">a. Waktu & Tempat Pelaksanaan</h4>
    <p style="margin-bottom: 0;margin-top:0;margin-left:1rem;text-align:justify">Rapat Pleno Persetujuan Bangunan Gedung (PBG)  dan/atau Sertifikat Laik Fungsi (SLF) dilaksanakan pada {{$hari}} bertempat di Ruang Rapat Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Tegal.</p>
    <br>   
    <h4 style="margin-bottom: 0;margin-top:0;">b. Peserta Rapat Pleno</h4>
    <p style="margin-bottom: 0;margin-top:0;margin-left:1rem;text-align:justify">
    Peserta Rapat Pleno Persetujuan Bangunan Gedung (PBG)  dan/atau Sertifikat Laik Fungsi (SLF) adalah :
    </p>
    <ol style="margin-bottom: 0;margin-top:0;margin-left:1.8rem;padding:0">
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
        {!! $template[0]->field !!}
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
    <h4 style="margin-bottom: 0;margin-top:0;">e. Hasil Rapat Pleno</h4>
    <p style="margin-bottom: 0;margin-top:0;margin-left:0.8rem">Hasil Rapat Persiapan Survey Persetujuan Bangunan Gedung (PBG) antara lain:</p>
    <ol style="margin-bottom: 0;margin-top:0;margin-left:1.8rem;padding:0">
        <li>Pemohon menyatakan seluruh data, dokumen (administrasi dan teknis), informasi dan keterangan atas permohonan Persetujuan Bangunan Gedung (PBG) dan/atau Sertifikat Laik Fungsi (SLF) adalah benar dan tidak dalam sengketa;
        </li>
        <li>Berdasarkan hasil pengecekan melalui Sistem Informasi Manajemen Bangunan Gedung (SIMBG) dan Peninjauan Lokasi, persyaratan administrasi dan teknis atas nama pemohon-pemohon tersebut diatas dinyatakan lengkap dan memenuhi syarat untuk dilakukan penerbitan Surat Pernyataan Pemenuhan Standar Teknis Persetujuan Bangunan Gedung (PBG) dan/atau Sertifikat Laik Fungsi (SLF);
        </li>
        <li>Pemohon hanya diperkenankan untuk membangun bangunan gedung dan/atau prasarana bangunan gedung sesuai dengan lampiran Surat Pernyataan Pemenuhan Standar Teknis Persetujuan Bangunan Gedung (PBG);
        </li>
        <li>Pemohon wajib mematuhi rekomendasi Tim Penilai Ahli (TPA) dan/atau  Tim Penilai Teknis (TPT) sebagaimana tercantum dalam Lampiran Berita Acara Konsultasi (BAK);
        </li>
        <li> {!! $template[1]->field !!}
            <ol style="margin-bottom: 0;margin-top:0; list-style-type: none;padding-left: 1rem;">
                @foreach($pemohon as $val)
                    <li>
                        <table style="border:none;width: 100%;">
                            <tr>
                                <td style="border:none;" width="2">{{Abjad($loop->index)}}.</td>
                                <td style="border:none;" width="90">Nama</td>
                                <td style="border:none;">:</td>
                                <td style="border:none;">{{$val->pemohon}}</td>
                            </tr>
                            <tr>
                                <td style="border:none;"></td>
                                <td style="border:none;">Nomor Permohonan</td>
                                <td style="border:none;">:</td>
                                <td style="border:none;">{{$val->reg}}</td>
                            </tr>
                            <tr>
                                <td style="border:none;"></td>
                                <td style="border:none;">Nilai Retribusi</td>
                                <td style="border:none;">:</td>
                                <td style="border:none;">
                                    @if ($val->tax)
                                    @php
                                        $tax = (object) json_decode($val->tax->parameter);
                                    @endphp
                                    {{ number_format($tax->totRetri, 0, ',', '.') }}
                                @endif
                                </td>
                            </tr>
                        </table>
                    </li>
                @if (($loop->iteration) % 11 == 0 OR $loop->iteration == 8)
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
        </li>
    </ol>
</div>

<div class="clearfix"></div> 
<div class="column"></div>
<div class="columns">
    <ol start="6" style="margin-bottom: 0;margin-top:0; padding-left: 1rem;">
        <li>Mengusulkan pemohon-pemohon tersebut diatas untuk dilakukan penerbitan Persetujuan Bangunan Gedung (PBG) dan/atau Sertifikat Laik Fungsi (SLF);
        </li>
        <li>Pemohon menyatakan bertanggung jawab terhadap segala sesuatu yang timbul akibat dan/atau disebabkan oleh berdirinya bangunan dan/atau prasarana bangunan gedung tersebut mulai dari proses pra-konstruksi pasca-konstruksi, pemanfaatan, dan perawatan bangunan.
        </li>
    </ol>
    <p style="margin-bottom: 0;margin-top:0;margin-left:0.8rem">
        Demikian laporan ini kami sampaikan, atas perhatian dan arahannya kami ucapkan terima kasih.
    </p>
    
    <table style="border:none;width: 100%;">
        <tr>
            <td style="border:none;" width="80"></td>
            <td style="border:none;text-align:center;float-right">
                <div style="margin-left:2rem">
                    <p>Yang Melaporkan,<br>Kepala Bidang Penataan Bangunan, <br>Lingkungan, dan Tata Ruang<</p>
                    <br>
                    <br>
                    <p><span style="font-weight:bold;text-decoration:underline">{{$doc->pelapor->name}}</span><br>NIP. {{$doc->pelapor->nip}}</p>
                </div>
            </td>
        </tr>
    </table>
</div>
@endsection