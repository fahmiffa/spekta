    <table style="width: 100%; border:none">
        <tr>
            <td style="border:none"><img class="img" src="{{ gambar('kab.png') }}" /></td>
            <td width="100%" style="border:none; text-align:center">
                <p>
                    <span style="font-weight: bold; font-size:0.8rem;text-wrap:none">SIMULASI PERHITUNGAN RETRIBUSI
                        <br>PERSETUJUAN BANGUNAN GEDUNG (PBG) DAN/ATAU SERTIFIKAT LAIK FUNGSI (SLF)
                    </span>
                </p>
            </td>

            <td style="border:none"><img class="img" src="{{ gambar('logo.png') }}" /></td>
        </tr>
    </table>
    @php
        $header = (array) json_decode($head->header);
    @endphp
    <h4>A. INFORMASI UMUM</h4>
    <table style="width:95%;" align="center">
        <tr>
            <td width="30%" style="border:none">No. Registrasi</td>
            <td width="1%" style="border:none">:</td>
            <td width="60%" style="border:none">{{ $header[0] }} </td>
            <td width="20%" style="border:none">Tanggal</td>
            <td width="1%" style="border:none">:</td>
            <td width="60%" style="border:none">{{ dateID($head->tax->tanggal) }}</td>
        </tr>
        <tr>
            <td width="20%" style="border:none">Nama Pemohon </td>
            <td width="1%" style="border:none">:</td>
            <td colspan="3" style="border:none">{{ $header[2] }}</td>
        </tr>
        <tr>
            <td width="20%" style="border:none;vertical-align:top">Alamat Pemohon </td>
            <td width="1%" style="border:none;vertical-align:top">:</td>
            <td colspan="4" style="border:none;vertical-align:top">{{ $header[4] }}</td>
        </tr>
        <tr>
            <td width="25%" style="border:none">Nama Bangunan </td>
            <td width="1%" style="border:none">:</td>
            <td colspan="3" style="border:none">{{ $header[5] }}</td>          
        </tr>
        <tr>
            <td width="20%" style="border:none;vertical:align:top">Alamat Bangunan </td>
            <td width="1%" style="border:none;vertical-align:top">:</td>
            <td colspan="4" style="border:none;vertical-align:top">
                {{ $header[7] }}, Desa/Kel. {{ $head->region->name }}, Kec. {{ $head->region->kecamatan->name }}, Kab. Tegal, Prov. Jawa Tengah
            </td>
        </tr>
    </table>
    @php
        $par = json_decode($head->tax->parameter);
    @endphp
    <h4>B. PARAMETER</h4>
    <table style="width: 98%">
        <tr align="center" style="background-color:lightgrey">
            <td>No.</td>
            <td>Uraian</td>
            <td>Parameter</td>
        </tr>
        <tr>
            <td align="center">1</td>
            <td style="padding:0.2rem">Fungsi Bangunan <i>(If)</i></td>
            <td style="padding:0.2rem">{{ $par->if[0] }}</td>
        </tr>
        <tr>
            <td align="center">2</td>
            <td style="padding:0.2rem">Kompleksitas <i>(Ik)</i></td>
            <td style="padding:0.2rem">{{ $par->ik[0] }}</td>
        </tr>
        <tr>
            <td align="center">3</td>
            <td style="padding:0.2rem">Tingkat Permanensi <i>(Ip)</i></td>
            <td style="padding:0.2rem">{{ $par->ip[0] }}</td>
        </tr>
        <tr>
            <td align="center">4</td>
            <td style="padding:0.2rem">Jumlah Lantai <i>(Il)</i></td>
            <td style="padding:0.2rem">{{ $par->il[0] }}</td>
        </tr>
        <tr>
            <td align="center">5</td>
            <td style="padding:0.2rem">Status Kepemilikan <i>(Fm)</i></td>
            <td style="padding:0.2rem">{{ $par->fm[0] }}</td>
        </tr>
        <tr>
            <td align="center">6</td>
            <td style="padding:0.2rem">Indeks Terintegrasi <i>(It)</i></td>
            <td style="padding:0.2rem">{{ $par->it }}</td>
        </tr>
        <tr>
            <td align="center">7</td>
            <td style="padding:0.2rem">Indeks BG Terbangun <i>(Ibg)</i></td>
            <td style="padding:0.2rem">{{ $par->ibg[0] }}</td>
        </tr>
        <tr>
            <td align="center">8</td>
            <td style="padding:0.2rem">Indeks Lokalitas <i>(Ilo)</i></td>
            <td style="padding:0.2rem">{{ $par->ilo }}</td>
        </tr>
        <tr>
            <td align="center">9</td>
            <td style="padding:0.2rem">Standar Harga Satuan Tertinggi (SHST)</td>
            <td style="padding:0.2rem">{{ $par->shst }}</td>
        </tr>
    </table>
    @php
        $nb = (array) $par->par;
    @endphp
    <h4>C. PERHITUNGAN NILAI RETRIBUSI BANGUNAN GEDUNG</h4>
    <table style="width: 98%">
        <tr align="center" style="background-color:lightgrey">
            <td>No.</td>
            <td>Uraian</td>
            <td>Luas (m<sup>2</sup>)</td>
        </tr>
        @for ($i = 1; $i < count($nb); $i++)
            @isset($nb[$i][0])
                <tr>
                    <td align="center" width="3%">{{ $i }}</td>
                    <td style="padding:0.2rem">{{ $nb[$i][0] }}</td>
                    <td style="padding:0.2rem" align="right">{{ $nb[$i][1] }}</td>
                </tr>
            @endif
            @endfor
            <tr style="background-color:lightgrey">
                <td colspan="2" style="text-align:right">Luas Total Bangunan <i>(LLt)</i>&nbsp;</td>
                <td align="right">{{ $par->llt }}</td>
            </tr>
            <tr style="background-color:ivory">
                <td colspan="2" style="text-align:right">NILAI RETRIBUSI BANGUNAN GEDUNG&nbsp;<br>&nbsp;<i>(It x Ibg x
                        Ilo x SHST x LLt)</i>&nbsp;</td>
                <td align="right">{{ number_format($par->retri, 0, ',', '.') }}</td>
            </tr>
        </table>
        @php
            $pra = (array) $par->pra;
        @endphp
        <h4>D. PERHITUNGAN NILAI RETRIBUSI PRASARANA</h4>
        <table style="width: 98%">
            <tr align="center" style="background-color:lightgrey">
                <td>No.</td>
                <td>Uraian</td>
                <td>Volume</td>
                <td>Satuan</td>
                <td>Harga Satuan</td>
                <td>Jumlah Harga</td>
            </tr>
            @for ($i = 1; $i < count($pra); $i++)
                @isset($pra[$i][0])
                    <tr>
                        <td align="center">{{ $i }}</td>
                        <td style="padding:0.2rem">{{ $pra[$i][0] }}</td>
                        <td align="center">{{ $pra[$i][1] }}</td>
                        <td align="center">
                            @php
                             $sup = substr($pra[$i][2], -1) == 1 ? null : substr($pra[$i][2], -1);
                             $val = substr($pra[$i][2], 0, -1);
                            @endphp
                            {{ $val }}<sup>{{$sup}}</sup>
                        </td>
                        <td style="padding:0.2rem" align="right">
                            {{ number_format($pra[$i][3], 0, ',', '.') }}
                        </td>
                        <td style="padding:0.2rem" align="right">
                            {{ number_format($pra[$i][4], 0, ',', '.') }}
                        </td>
                    </tr>
                @endif
                @endfor
                <tr style="background-color:ivory">
                    <td colspan="5" style="text-align:right">NILAI RETRIBUSI PRASARANA&nbsp;</td>
                    <td align="right">{{ number_format($par->sumPra, 0, ',', '.') }}</td>
                </tr>
                <tr style="background-color:ivory">
                    <td colspan="5" style="text-align:right">
                        <strong>TOTAL NILAI RETRIBUSI</strong><br>
                        (NILAI RETRIBUSI BANGUNAN GEDUNG + NILAI RETRIBUSI PRASARANA)
                    </td>
                    <td align="right"><b>{{ number_format($par->totRetri, 0, ',', '.') }}</b></td>
                </tr>
            </table>
            <p>Catatan :</p>
            <ol>
                <li>
                    Perhitungan Retribusi ini merupakan simulasi dengan mengacu pada Peraturan Pemerintah (PP) No. 16 Tahun 2021 tentang Peraturan Pelaksanaan Undang-Undang Nomor 28 Tahun 2002 tentang Bangunan Gedung dan Perda Kab. Tegal Nomor 11 Tahun 2023 tentang Pajak Daerah dan Retribusi Daerah
                </li>
                <li>Dokumen ini BUKAN merupakan PBG / Bukti Penagihan / Bukti Pembayaran yang sah, proses penagihan dan
                    pembayaran tetap mengacu pada SKRD yang dikeluarkan oleh DPMPTSP
                </li>
                <li>Hasil perhitungan ini dimungkinan terdapat perbedaan dengan hasil perhitungan retribusi akhir karena faktor
                    sistem dan hasil verifikasi akhir terhadap dokumen teknis secara menyeluruh
                </li>
            </ol>
            @php  $header = (array) json_decode($head->header); @endphp
            <script type="text/php"> 
            if (isset($pdf)) { 
                $x = 290;  //X-axis vertical position 
                $y = 990; //Y-axis horizontal position
                $text = "Simulasi Perhitungan Retribusi No. Registrasi {{$header[0]}} | Halaman {PAGE_NUM} dari {PAGE_COUNT}";             
                $font =  $fontMetrics->get_font("helvetica", "bold");
                $size = 7;
                $color = array(0,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }
        </script>
