    <table style="width: 100%; border:none">
        <tr>
            <td style="border:none"><img class="img" src="{{ gambar('kab.png') }}" /></td>
            <td width="100%" style="border:none; text-align:center">
                <p>
                    <span style="font-weight: bold; font-size:0.8rem;text-wrap:none">LAMPIRAN DOKUMEN PBG DAN/ATAU SLF</span>
                    <br>No.&nbsp;&nbsp;{{ $head->numbDoc('lampiran') }}
                </p>
            </td>
            <td style="border:none"><img class="img" src="{{ gambar('logo.png') }}" /></td>
        </tr>
    </table>
    @php  $header = (array) json_decode($head->header); @endphp
    <table style="width:100%; margin-top: 1rem" align="center">
        <tbody>
            <tr>
                <td width="20%" style="border:none">No. Registrasi </td>
                <td width="1%" style="border:none">:</td>
                <td colspan="5" style="border:none">{{ $header[0] }} </td>
            </tr>
            <tr>
                <td width="15%" style="border:none">Nama Pemohon </td>
                <td width="1%" style="border:none">:</td>
                <td colspan="5" style="border:none">{{ $header[2] }}</td>
            </tr>
            <tr>
                <td width="15%" style="border:none">Alamat Pemohon </td>
                <td width="1%" style="border:none">:</td>
                <td colspan="6" style="border:none">{{ $header[4] }}</td>
            </tr>
            <tr>
                <td width="15%" style="border:none">Nama Bangunan </td>
                <td width="1%" style="border:none">:</td>
                <td colspan="5" style="border:none">{{ $header[5] }}</td>
            </tr>
            <tr>
                <td width="15%" style="border:none;vertical:align:top">Alamat Bangunan </td>
                <td width="1%" style="border:none;vertical-align:top">:</td>
                <td colspan="5" style="border:none;vertical-align:top">
                    {{ $header[7] }}, Desa/Kel. {{ $head->region->name }}, Kec. {{ $head->region->kecamatan->name }},
                    Kab. Tegal, Prov. Jawa Tengah
                </td>
            </tr>
            <tr>
                <td width="15%" style="border:none">Luas Tanah</td>
                <td width="1%" style="border:none">:</td>
                <td width="20%" style="border:none">{{ $head->attach->luas }}</td>
                @if ($head->attach->persil)
                    <td width="15%" style="border:none">Luas Persil</td>
                    <td width="1%" style="border:none">:</td>
                    <td width="20%" style="border:none">{{ $head->attach->persil }}</td>
                @endif        
            </tr>
            <tr>
                <td width="15%" style="border:none">Bukti Kepemilikan Tanah </td>
                <td width="1%" style="border:none">:</td>
                <td colspan="6" style="border:none">{{ $head->attach->bukti }}</td>
            </tr>
        </tbody>
    </table>
    <table style="width: 100%; border:none;margin-top:1rem">
        <tr>
            <td colspan="2" style="padding: 0.5rem;font-weight:bold;text-align:center">Gambar Denah / Situasi</td>
        </tr>
        @if ($head->attach->pile_map)
            @php
                $var = json_decode($head->attach->pile_map);
            @endphp
            @for($i = 0; $i < count($var); $i++)
                <tr>
                    <td colspan="2" style="padding: 0.5rem;">
                        <center>
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents('storage/' . $var[$i])) }}"
                                style="width:100%;object-fit:cover;object-position:center;margin:auto;display:block;padding:0.3rem">
                        </center>
                    </td>
                </tr>
            @endfor
        @endif
        <tr>
            <td style="padding: 0.5rem;font-weight:bold;text-align:center" width="50%">
                Lokasi Bangunan
            </td>
            <td style="padding: 0.5rem;font-weight:bold;text-align:center" width="50%">
                Kondisi Lahan / Bangunan
            </td>
        </tr>
        <tr>
            <td style="padding: 0.5rem;font-weight:bold;text-align:center" width="50%">
                @if ($head->attach->pile_loc)
                    <center>
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents('storage/' . $head->attach->pile_loc)) }}"
                            style="width:100%;object-fit:cover;object-position:center;margin:auto;display:block">
                    </center>
                @endif
            </td>
            <td style="padding: 0.5rem;font-weight:bold;text-align:center" width="50%">
                @if ($head->attach->pile_land)
                    <center>
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents('storage/' . $head->attach->pile_land)) }}"
                            style="width:100%;object-fit:cover;object-position:center;margin:auto;display:block">
                    </center>
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 0.5rem;font-weight:bold">Koordinat : {{ $head->attach->koordinat }}</td>
        </tr>
    </table>
    <table style="width: 100%; border:none;margin-top:1rem">
        <tr>
            <td style="border:none">
                <p>Catatan :</p>
                <ol>
                    <li>Lampiran ini merupakan bagian yang tidak terpisahkan dari Berita Acara Konsultasi (BAK) <br> No.
                        {{ $head->numbDoc('bak') }}
                    </li>
                    <li>Pemilik bangunan tidak diperkenankan mengembangkan bangunan diluar ketentuan yang berlaku.
                    </li>
                    <li>Terhadap bangunan yang telah berdiri (existing) agar dilakukan pemeriksaan kelaikan fungsi sebelum
                        bangunan
                        dimanfaatkan.
                    </li>
                </ol>
            </td>
            <td style="border:none;vertical-align-middle">
                <center>
                    <img src="data:image/png;base64, {{ $qrCode }}" width="75%">
                </center>
            </td>
        </tr>
    </table>
    
    <script type="text/php"> 
        @php  $header = (array) json_decode($head->header); @endphp
        if (isset($pdf)) { 
            //Shows number center-bottom of A4 page with $x,$y values
            $x = 330;  //X-axis vertical position 
            $y = 990; //Y-axis horizontal position
            $text = "Lembar Lampiran No. Registrasi {{$header[0]}}  | Halaman {PAGE_NUM} dari {PAGE_COUNT}";             
            $font =  $fontMetrics->get_font("helvetica", "bold");
            $size = 7;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>

