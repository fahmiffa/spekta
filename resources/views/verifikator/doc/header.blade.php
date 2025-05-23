<header>
    <table style="width: 100%; border:none">
        <tr>
            <td style="border:none"><img class="img" src="{{ gambar('kab.png') }}" /></td>
            <td width="100%" style="border:none; text-align:center">
                <p><span style="font-weight: bold; font-size:0.8rem;text-wrap:none">{{ $docs->titles }}</span>
                    <br>No.&nbsp;&nbsp;{{ $head->nomor }}
                </p>
            </td>
            <td style="border:none"><img class="img" src="{{ gambar('logo.png') }}" /></td>
        </tr>
    </table>
</header>

@php $header = (array) json_decode($head->header);
@endphp
<table style="width:100%; margin-top: 1rem" align="center">
    <tbody>
        <tr>
            <td width="10%" style="border:none">No. Registrasi </td>
            <td width="1%" style="border:none">:</td>
            <td width="20%" style="border:none">{{ $header[0] }} </td>
            <td width="10%" style="border:none">Pengajuan </td>
            <td width="1%" style="border:none">:</td>
            <td width="20%" style="border:none">{{ strtoupper($header[1]) }}</td>
        </tr>
        <tr>
            <td width="10%" style="border:none">Nama Pemohon </td>
            <td width="1%" style="border:none">:</td>
            <td width="20%" style="border:none">{{ $header[2] }}</td>
            <td width="10%" style="border:none">No. Telp. / HP </td>
            <td width="1%" style="border:none">:</td>
            <td width="20%" style="border:none">{{ $header[3] }}</td>
        </tr>
        <tr>
            <td width="10%"  style="border:none;vertical-align:top">Alamat Pemohon </td>
            <td width="1%"  style="border:none;vertical-align:top">:</td>
            <td colspan="4" style="border:none" style="border:none;vertical-align:top">{{ $header[4] }}</td>
        </tr>
        <tr>
            <td width="10%" style="border:none">Nama Bangunan </td>
            <td width="1%" style="border:none">:</td>
            <td width="20%" style="border:none"> {{ $header[5] }}</td>
            <td width="10%" style="border:none">{{$head->type == 'umum' ? 'Fungsi' : 'Koordinat' }} </td>
            <td width="1%" style="border:none">:</td>
            <td width="20%" style="border:none">
            {{ $head->type == 'umum' ? ucwords(str_replace('_',' ',$header[6])) : $header[8] }}
            </td>
        </tr>
        <tr>
            <td width="10%" style="border:none;vertical:align:top">Alamat Bangunan </td>
            <td width="1%"  style="border:none;vertical-align:top">:</td>
            <td colspan="4" style="border:none;vertical-align:top">
                {{ $header[7] }} Desa/Kel. {{ $head->region->name }}, Kec.
                {{ $head->region->kecamatan->name }},
                Kab. Tegal, Prov. Jawa Tengah
            </td>
        </tr>
    </tbody>
</table>
