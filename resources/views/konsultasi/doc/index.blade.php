<!DOCTYPE html>
<html>

<head>
    <title>{{ env('APP_NAME') }} | {{ env('APP_TAG') }}</title>

    <meta content="{{ env('APP_DES') }}" name="description">
    <meta content="{{ env('APP_NAME') }}" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}" type="image/x-icon">
</head>
<style>
    body {
        font-size: 16px;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
    }

    td {
        border: 1px solid black;
    }

    ol {
        margin-top: 0rem;
        margin-left: 0rem;
    }

    .watermark {
        position: fixed;
        top: 50%;
        left: 45%;
        transform: translate(-50%, -50%);
        opacity: 0.07;
        z-index: -1;
    }
</style>

<body>
    <img class="watermark" src="{{ gambar('watermak.png') }}" width="75%" />
    <header>
        <table style="width: 100%; border:none">
            <tr>
                <td style="border:none"><img style="width: 60px" class="img" src="{{ gambar('kab.png') }}" /></td>
                <td width="100%" style="border:none; text-align:center">
                    <p><span style="font-weight: bold;text-wrap:none;font-size:1rem">SURAT PEMBERITAHUAN / UNDANGAN
                            <br>KONSULTASI PBG DAN/ATAU SLF</span>
                        <br>No.&nbsp;&nbsp;{{ $schedule->number }}
                    </p>
                </td>
                <td style="border:none"><img style="width: 60px" class="img" src="{{ gambar('logo.png') }}" /></td>
            </tr>
        </table>
    </header>
    <div style="border-bottom: 3px solid black;width:100%;margin-bottom:0.1rem;margin-top:0.2rem"></div>
    <div style="border-bottom: 1px solid black;width:100%"></div>
    <div style="margin: auto; display:block;">
        <table style="width:100%; margin-top: 0.5rem">
            <tr>
                <td width="10%" style="border:none;vertical-align:top">Perihal</td>
                <td width="1%" style="border:none;vertical-align:top">:</td>
                <td style="border:none;">Surat Pemberitahuan / Undangan <br>Konsultasi PBG dan/atau SLF</td>
                <td style="border:none;text-align:right;vertical-align:top">Slawi, {{ dateID($schedule->tanggal) }}</td>
            </tr>
        </table>

        @php
            $time = explode('#', $schedule->waktu);
            $place = explode('#', $schedule->tempat);
            $header = json_decode($schedule->doc->header);
        @endphp

        <p style="margin-left:24rem">
            Kepada Yth.<br>
            Bapak/Ibu<br>
            <b>{{ $header[2] }}</b><br>
            di -<br>
            <span style="margin-left:2.5rem">Tempat</span>
        </p>

        <p style="text-align: justify"><span style="margin-right: 1rem">&nbsp;</span>
            Sehubungan dengan Permohonan Persetujuan Bangunan Gedung (PBG) dan/atau Sertifikat Laik (SLF) yang diunggah
            melalui Sistem Informasi Manajemen Bangunan Gedung (SIMBG) :
        </p>

        <table style="width:95%; margin:auto" align="center">
            <tr>
                <td width="30%" style="border:none">Nomor Registrasi</td>
                <td width="1%" style="border:none">: </td>
                <td style="border:none">{{ $schedule->doc->reg }}</td>
            </tr>
            <tr>
                <td width="30%" style="border:none">Nama Pemohon/Pemilik</td>
                <td width="1%" style="border:none">: </td>
                <td style="border:none">{{ $header[2] }}</td>
            </tr>
            <tr>
                <td width="30%" style="border:none;vertical-align:top">Alamat Pemohon/Pemilik</td>
                <td width="1%" style="border:none;vertical-align:top">: </td>
                <td style="border:none;vertical-align:top">{{ $header[4] }}</td>
            </tr>
            <tr>
                <td width="30%" style="border:none">Nama Bangunan</td>
                <td width="1%" style="border:none">: </td>
                <td style="border:none">{{ $header[5] }}</td>
            </tr>
            <tr>
                <td width="30%" style="border:none;vertical-align:top">Alamat Bangunan</td>
                <td width="1%" style="border:none;vertical-align:top">: </td>
                <td style="border:none;vertical-align:top">{{ $header[7] }} Desa/Kel.
                    {{ $schedule->doc->region->name }} Kec. {{ $schedule->doc->region->kecamatan->name }} Kab. Tegal
                </td>
            </tr>
        </table>

        <p style="text-align: justify">
            Dapat kami informasikan bahwa permohonan PBG dan/atau SLF tersebut dilanjutkan ketahap Konsultasi yang akan
            dilaksanakan pada :
        </p>

        <table style="width:95%; margin:auto" align="center">
            <tr>
                <td width="30%" style="border:none">Hari, Tanggal</td>
                <td width="1%" style="border:none">: </td>
                <td style="border:none">{{ hari($time[2]) }}</td>
            </tr>
            <tr>
                <td width="30%" style="border:none">Pukul</td>
                <td width="1%" style="border:none">: </td>
                <td style="border:none">{{ $time[0] }} - {{ $time[1] }} WIB </td>
            </tr>
            <tr>
                <td width="30%" style="border:none">Jenis Konsultasi</td>
                <td width="1%" style="border:none">: </td>
                <td style="border:none">{{ ucwords(str_replace('_', ' ', $schedule->jenis)) }}</td>
            </tr>
            <tr>
                <td width="30%" style="border:none;vertical-align:top">Tempat</td>
                <td width="1%" style="border:none;vertical-align:top">: </td>
                @if ($place[0] == 'alamat_bangunan')
                    <td style="border:none;vertical-align:top">
                        {{ $header[7] }} Desa/Kel.
                        {{ $schedule->doc->region->name }} Kec. {{ $schedule->doc->region->kecamatan->name }} Kab.
                        Tegal
                        <br>
                        {{ ucwords(str_replace('_', ' ', $place[1])) }}
                    </td>
                @else
                    <td style="border:none;vertical-align:top">
                        {!! ucwords(str_replace('Teleconference','<i>Teleconference</i>',str_replace('_', ' ', $place[0]))) !!}<br>
                        {{ ucwords(str_replace('_', ' ', $place[1])) }}
                    </td>
                @endif
            </tr>
            <tr>
                <td width="30%" style="border:none;vertical-align:top">Keterangan</td>
                <td width="1%" style="border:none;vertical-align:top">: </td>
                <td style="border:none;vertical-align:top;text-align:justify" class="warp">
                    @php
                        $pass = ['<p>', '<ul>'];
                        $new = ['<p style="margin-top:0rem">', '<ul style="margin-top:0rem">'];
                        $text = str_replace($pass, $new, $schedule->keterangan);
                    @endphp
                    {!! $text !!}
                </td>
            </tr>
        </table>

        <p style="text-align: justify">
            Demikian surat pemberitahuan / undangan ini dibuat untuk dipergunakan sebagaimana mestinya. Terima kasih.
        </p>


        <table style="width:100%; margin:auto" align="center">
            <tr>
                <td width="60%" style="border:none">&nbsp;</td>
                <td style="border:none;">
                    <p style="text-align: center">
                        {{-- Slawi, {{ dateID($schedule->created_at) }}<br> --}}
                        <img src="data:image/png;base64, {{ $qrCode }}" width="40%">
                        <br>
                        DPUPR Kabupaten Tegal
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
