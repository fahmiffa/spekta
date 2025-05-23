@if ($head->do == 1 && $head->bak->primary == 'TPA')
    <table style="width:30%" class="footer">
        <tr>
            <td style="padding:0.2rem;font-size:7;font-weight:bold">
                Mengetahui Kepala Bidang PBLTaru
            </td>
            <td align="center" style="padding:0.2rem;font-size:7;font-weight:bold">
                OK
            </td>
        </tr>
    </table>
@endif
<header>
    <table style="width: 100%; border:none">
        <tr>
            <td style="border:none"><img class="img" src="{{ gambar('kab.png') }}" /></td>
            <td width="100%" style="border:none; text-align:center">
                <p>
                    <span style="font-weight: bold; font-size:rem;text-wrap:none">BERITA ACARA KONSULTASI (BAK)</span>
                    <br>No.&nbsp;&nbsp;{{ $head->numbDoc('bak') }}
                </p>
            </td>
            <td style="border:none"><img class="img" src="{{ gambar('logo.png') }}" /></td>
        </tr>
    </table>
</header>
@php
    $header = (array) json_decode($head->header);
    $item = (array) json_decode($head->bak->item);
    $umum = $item['informasi_umum'];
    $bangunan = $item['informasi_bangunan_gedung'];
@endphp
<table style="width:100%" align="center">
    <tbody>
        <tr>
            <td width="40%" style="border:none">No. Registrasi </td>
            <td width="1%" style="border:none">:</td>
            <td width="60%" style="border:none">{{ $header[0] }} </td>
            <td width="40%" style="border:none">Pengajuan </td>
            <td width="1%" style="border:none;vertical-align:top">:</td>
            <td width="60%" style="border:none">{{ strtoupper($header[1]) }}</td>
        </tr>
        <tr>
            <td width="40%" style="border:none">Nama Pemohon </td>
            <td width="1%" style="border:none">:</td>
            <td width="60%" style="border:none">{{ $header[2] }}</td>
            <td width="40%" style="border:none">No. Telp. / HP </td>
            <td width="1%" style="border:none;vertical-align:top">:</td>
            <td width="60%" style="border:none">{{ $header[3] }}</td>
        </tr>
        <tr>
            <td width="40%" style="border:none">Alamat Pemohon </td>
            <td width="1%" style="border:none">:</td>
            <td colspan="4" style="border:none">{{ $header[4] }}</td>
        </tr>
        <tr>
            <td width="40%" style="border:none;">Nama Bangunan </td>
            <td width="1%" style="border:none">:</td>
            <td width="60%" style="border:none">{{ $header[5] }}</td>
            <td width="40%" style="border:none">{{ $head->type == 'umum' ? 'Fungsi' : 'Koordinat' }} </td>
            <td width="1%" style="border:none;vertical-align:top">:</td>
            <td style="border:none;">
                {{ $head->type == 'umum' ? ucfirst($header[6]) : $header[8] }}
            </td>
        </tr>
        <tr>
            <td width="40%" style="border:none;vertical-align:top">Alamat Bangunan </td>
            <td width="1%" style="border:none;vertical-align:top">:</td>
            <td colspan="4" style="border:none;vertical-align:top">
                {{ $header[7] }}, Desa/Kel. {{ $head->region->name }}, Kec. {{ $head->region->kecamatan->name }}
                Kab. Tegal, Prov. Jawa Tengah             
            </td>
        </tr>
    </tbody>
</table>
<table style="width:100%;" align="center">
    <tbody>
        @php
            $area = json_decode($head->bak->header);
        @endphp
        <tr>
            <td width="40%" style="border:none">Batas Lahan / Lokasi </td>
            <td width="20%" style="border:none">: Utara</td>
            <td width="140%" style="border:none">: {{strlen($area->north) > 85 ? $area->north : mb_strimwidth($area->north,0,85,'...')}}</td>
        </tr>
        <tr>
            <td width="40%" style="border:none"></td>
            <td width="20%" style="border:none">&nbsp; Timur</td>
            <td width="140%" style="border:none">: {{strlen($area->east) > 85 ? $area->east : mb_strimwidth($area->east,0,85,'...')}}</td>
        </tr>
        <tr>
            <td width="40%" style="border:none"></td>
            <td width="20%" style="border:none">&nbsp; Selatan</td>
            <td width="140%" style="border:none">: {{strlen($area->south) > 85 ? $area->south : mb_strimwidth($area->south,0,85,'...')}}</td>
        </tr>
        <tr>
            <td width="40%" style="border:none"></td>
            <td width="20%" style="border:none">&nbsp; Barat</td>
            <td width="140%" style="border:none">: {{strlen($area->west) > 85 ? $area->west : mb_strimwidth($area->west,0,85,'...')}}</td>
        </tr>
    </tbody>
</table>
<table style="width:100%;" align="center">
    <tr>
        <td width="40%" style="border:none">Kondisi </td>
        <td width="60%" style="border:none">: {{ ucwords(str_replace('_', ' ', $area->kondisi)) }} </td>
        <td width="40%" style="border:none">Tahun Pembangunan</td>
        <td width="60%" style="border:none">: {{ $head->bak->plan }} </td>
    </tr>
    <tr>
        <td width="40%" style="border:none">Tingkat Permanen </td>
        <td width="60%" style="border:none">: {{ ucwords(str_replace('_', ' ', $area->permanensi)) }} </td>
    </tr>
</table>
<table style="width:100%;margin-top:1rem;">
<tr>
<td colspan="3" style="border: none">Informasi Umum :</td>
<td colspan="3" style="border: none">Informasi Bangunan Gedung :</td>
</tr>
<tr>
<td align="center">Uraian</td>
<td align="center">Dimensi</td>
<td style="border: none"></td>
<td align="center">Uraian</td>
<td align="center">Dimensi</td>
<td align="center">Catatan</td>
</tr>
@for ($i = 0; $i < count($bangunan); $i++)
<tr>
    @isset($umum[$i])
        <td>&nbsp;&nbsp;{{ $umum[$i]->uraian }}</td>
        <td align="center">&nbsp;&nbsp;{{ $umum[$i]->value }}</td>
    @else
        <td></td>
        <td></td>
@endif
<td style="border: none"></td>
<td>&nbsp;&nbsp;{{ ucwords(str_replace('_', ' ', $bangunan[$i]->uraian)) }}</td>
<td align="center">&nbsp;&nbsp;{{ $bangunan[$i]->dimensi }}</td>
<td align="center">&nbsp;&nbsp;{!! $bangunan[$i]->note !!}</td>
</tr>
@endfor
@if ($head->bak->ibg)
@foreach (json_decode($head->bak->ibg) as $fa)
    <tr>
        <td></td>
        <td></td>
        <td style="border: none"></td>
        <td>&nbsp;&nbsp;{{ $fa[0] }}</td>
        <td align="center">&nbsp;&nbsp;{{ $fa[1] }}</td>
        <td align="center" class="warp">&nbsp;&nbsp;{!! $fa[2] !!}</td>
    </tr>
@endforeach
@endif
</table>

<table style="width:100%;">
    <tr>
        <td style="border: none">Informasi Dimensi Bangunan dan Prasarana :</td>
        <td style="border: none">Informasi Dimensi Prasarana :</td>
    </tr>
    <tr>
        <td align="center">Bangunan Gedung</td>
        <td align="center">Prasarana</td>
    </tr>
    <tr>
        <td width="50%">{!! $item['idb'][1] !!}</td>
        <td width="50%">{!! $item['idp'][1] !!}</td>
    </tr>
</table>
<br>
<table style="width:100%;margin-bottom:0.5rem">
    <tr>
        <td style="border: none" colspan="2">Dengan ditandatangani Berita Acara ini, maka Pemohon telah memahami
            bahwa :</td>
        </tr>
    <tr>
        <td width="3%" class="code" style="border: none;vertical-align:top">1</td>
        <td style="border: none;vertical-align:top">
            Luas bangunan yang disetujui adalah luasan bangunan yang sesuai / tidak bertentangan dengan ketentuan tata
            bangunan yang berlaku dan/atau direkomendasikan (GSB, KDB, KDH, KLB, dan sebagainya).
        </td>
    </tr>
    <tr>
        <td class="code" style="border: none;vertical-align:top">2</td>
        <td style="border: none;vertical-align:top">
            Pemohon bersedia mematuhi dan memenuhi persyaratan administrasi dan teknis sesuai persyaratan yang berlaku
        </td>
    </tr>
    <tr>
        <td class="code" style="border: none;vertical-align:top">3</td>
        <td style="border: none;vertical-align:top">
            Terhadap bangunan yang tidak disetujui / tidak sesuai dengan ketentuan yang berlaku, maka Pemohon / Pemilik
            Bangunan bersedia melakukan penyesuaian bangunan sesuai peraturan perundang-undangan yang berlaku,
        </td>
    </tr>
    <tr>
        <td class="code" style="border: none;vertical-align:top">4</td>
        <td style="border: none;vertical-align:top">
            Dalam hal Pemohon / Pemilik Bangunan tidak melakukan penyesuaian bangunan sesuai ketentuan, maka Pemohon /
            Pemilik Bangunan bersedia menerima sanksi sesuai peraturan perundang-undangan yang berlaku.
        </td>
    </tr>
    <tr>
        <td class="code" style="border: none;vertical-align:top">5</td>
        <td style="border: none;vertical-align:top">
            Seluruh kebenaran dan keabsahan dokumen baik administrasi dan teknis merupakan tanggungjawab Pemohon /
            Pemilik Bangunan dan Penyedia Jasa / Konsultan Perencana.
        </td>
    </tr>
    <tr>
        <td class="code" style="border: none;vertical-align:top">6</td>
        <td style="border: none;vertical-align:top">
            Pemohon / Pemilik Bangunan bersedia melaksanakan pembangunan dan perawatan bangunan sesuai standar teknis.
        </td>
    </tr>
    <tr>
        <td class="code" style="border: none;vertical-align:top">7</td>
        <td style="border: none;vertical-align:top">
            Pemohon bersedia melakukan pembayaran retribusi sesuai perhitungan nilai retribusi yang dikenakan.
        </td>
    </tr>
    <tr>
        <td class="code" style="border: none;vertical-align:top">8</td>
        <td style="border: none;vertical-align:top">
            Apabila dikemudian hari terdapat ketidaksesuaian terhadap dokumen administrasi dan/atau dokumen teknis yang
            diajukan maka PBG dan/atau SLF akan dievaluasi kembali dan Pemohon bersedia menerima konsekuensi dan/atau
            sanksi sesuai peraturan perundang-undangan yang berlaku serta retribusi yang telah dibayarkan tidak dapat
            diminta kembali
        </td>
    </tr>
</table>

Catatan Lain :<br>
@if ($head->bak->note)
    <div class="warp">
        {!! $head->bak->note !!}
    </div>
@endif

<p>Demikian hasil konsultasi TPT/TPA yang dihadiri oleh:</p>
@if ($head->sign)
    <table>   
        @foreach (collect($head->sign)->sortBy('type') as $val)
            <tr>
                <td width="2%" style="border: none;vertical-align:middle">
                    {{ $loop->iteration }}.
                </td>
                <td style="border: none;vertical-align:middle">
                    {{ $val->users->name }}
                </td>
                <td style="border: none;vertical-align:middle">
                    @if ($val->bak)
                        <img src="{{ $val->bak }}" class="ttd">
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
@endif

<center>
    <table style="width:100%;">
        <tr>
            <td width="50%" style="border: none" align="center">
                <p>Mengetahui,<br>
                    Ketua TPT/TPA Kab. Tegal
                </p>
                @if ($head->bak)
                    @if ($head->bak->primary == 'TPT')
                        <img src="{{ $head->bak->sign }}" width="50%" style="margin: auto">
                    @endif
    
                    @if ($head->bak->primary == 'TPA')    
                        <img src="{{ $head->sign->where('type','lead')->first()->bak }}" width="50%" style="margin: auto">
                    @endif
                    <br>
                @else
                    <br><br><br>
                @endif
                <u><b>{{ $head->bak->primary == 'TPT' ? $head->bak->kabid->name : $head->kons->not->name }}</b></u>                
                {!! $head->bak->primary == 'TPT' ? '<br>'.$head->bak->kabid->nip : null !!}
            </td>
            <td style="border: none" align="center">
    
            @php
                $time = explode('#', $head->surat->waktu);
            @endphp
            {{$head->bak->place}}, {{ dateID($time[2]) }}<br>
            Setuju hasil pemeriksaan<br>
            {{$head->bak->in_name ? 'a.n. Pemohon' : 'Pemohon'}}
            <br>
                @if ($head->bak->signs)
                    <img src="{{ $head->bak->signs }}" width="50%">
                    <br>
                @else
                    <br>
                @endif
                <u><b>{{ $head->bak->in_name ? $head->bak->in_name : $header[2] }}</b></u>
            </td>
        </tr>
    </table>
</center>


@if ($head->grant == 1)
    @php  $header = (array) json_decode($head->header) @endphp
    <script type="text/php"> 
        if (isset($pdf)) { 
            //Shows number center-bottom of A4 page with $x,$y values
            $x = 330;  //X-axis vertical position 
            $y = 990; //Y-axis horizontal position
            $text = "Lembar BAK No. {{ $head->numbDoc('bak') }} | Halaman {PAGE_NUM} dari {PAGE_COUNT}";             
            $font =  $fontMetrics->get_font("helvetica", "bold");
            $size = 7;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>
@endif
