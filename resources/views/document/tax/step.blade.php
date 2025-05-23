@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/select/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/select/select2-bootstrap-5-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/select/select2-bootstrap-5-theme.rtl.min.css') }}">
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
            text-align: right;
        }
    </style>
@endpush
@section('main')
    <div class="page-heading">

        <section class="section">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-3">Simulasi Perhitungan Retribusi</h5>  
                    @include('document.pemohon')
                </div>

                <div class="card-body">

                    <form action="{{ route('tax.store', ['id' => md5($head->id)]) }}" method="post">
                        @php
                            if ($tax) {
                                $tax = (object) json_decode($tax->parameter);
                                $par = (array) $tax->par;
                                $pra = (array) $tax->pra;
                            }
                        @endphp
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Tanggal</label>
                                    <input type="date" name="tanggal" value="{{ $tax ? $tax->tanggal : old('tanggal') }}"
                                        class="form-control">
                                    @error('tanggal')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <h6>PARAMETER</h6>
                        <table class="table table-bordered">
                            <thead class="h6">
                                <tr>
                                    <th class="text-center" width="3%">No.</th>
                                    <th colspan="2" class="text-center">Uraian</th>
                                    <th class="text-center">Index</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Fungsi Bangunan <i>(If)</i></td>
                                    <td width="50%">
                                        <input type="hidden" value="{{ $tax ? $tax->if[0] : 0 }}" name="if[]"
                                            id="name-if">
                                        <select class="form-control select-field w-100" onchange="pilihNilaine(this)"
                                            name="if[]" id="if">
                                            @if ($tax)
                                                <option value="{{ $tax->if[1] }}">{{ $tax->if[0] }}</option>
                                            @else
                                                <option value="">Pilih</option>
                                            @endif
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <p id="view-if">{{ $tax ? $tax->if[2] : 0 }}</p>
                                        <input type="hidden" value="{{ $tax ? $tax->if[2] : 0 }}" name="if[]"
                                            id="index-if">
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Kompleksitas<i>(Ik)</i></td>
                                    <td width="50%">
                                        <input type="hidden" value="{{ $tax ? $tax->ik[0] : 0 }}" name="ik[]"
                                            id="name-ik">
                                        <select class="form-control select-field w-100" onchange="pilihNilaine(this)"
                                            name="ik[]" id="ik">
                                            @if ($tax)
                                                <option value="{{ $tax->ik[1] }}">{{ $tax->ik[0] }}</option>
                                            @else
                                                <option value="">Pilih</option>
                                            @endif
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <p id="view-ik">{{ $tax ? $tax->ik[2] : 0 }}</p>
                                        <input type="hidden" value="{{ $tax ? $tax->ik[2] : 0 }}" name="ik[]"
                                            id="index-ik">
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Tingkat Permanensi<i>(Ip)</i></td>
                                    <td width="50%">
                                        <input type="hidden" value="{{ $tax ? $tax->ip[0] : 0 }}" name="ip[]" id="name-ip">
                                        <select class="form-control select-field w-100" onchange="pilihNilaine(this)"
                                            name="ip[]" id="ip">
                                            @if ($tax)
                                                <option value="{{ $tax->ip[1] }}">{{ $tax->ip[0] }}</option>
                                            @else
                                                <option value="">Pilih</option>
                                            @endif
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <p id="view-ip">{{ $tax ? $tax->ip[2] : 0 }}</p>
                                        <input type="hidden" value="{{ $tax ? $tax->ip[2] : 0 }}" name="ip[]" id="index-ip">
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Jumlah Lantai<i>(Il)</i></td>
                                    <td width="50%">
                                        <input type="hidden" value="{{ $tax ? $tax->il[0] : 0 }}" name="il[]" id="name-il">
                                        <select class="form-control select-field w-100" onchange="pilihNilaine(this)"
                                            name="il[]" id="il">
                                            @if ($tax)
                                            <option value="{{ $tax->il[1] }}">{{ $tax->il[0] }}</option>
                                        @else
                                            <option value="">Pilih</option>
                                        @endif
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <p id="view-il">{{ $tax ? $tax->il[2] : 0 }}</p>
                                        <input type="hidden" value="{{ $tax ? $tax->il[2] : 0 }}" name="il[]" id="index-il">
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Status Kepemilikan<i>(Fm)</i></td>
                                    <td width="50%">
                                        <input type="hidden" value="{{ $tax ? $tax->fm[0] : 0 }}" name="fm[]" id="name-fm">
                                        <select class="form-control select-field w-100" onchange="pilihNilaine(this)"
                                            name="fm[]" id="fm">
                                            @if ($tax)
                                            <option value="{{ $tax->fm[1] }}">{{ $tax->fm[0] }}</option>
                                        @else
                                            <option value="">Pilih</option>
                                        @endif
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <p id="view-fm">{{ $tax ? $tax->fm[2] : 0 }}</p>
                                        <input type="hidden" value="{{ $tax ? $tax->fm[2] : 0 }}" name="fm[]" id="index-fm">
                                    </td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Indeks Terintegrasi<i>(It)</i></td>
                                    <td width="50%">
                                        <input type="text" value="{{ $tax ? $tax->it : 0 }}" class="form-control" name="it"
                                            id="it" readonly>
                                    </td>
                                    <td class="text-center">
                                        <p id="view-it"></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Indeks BG Terbangun<i>(Ibg)</i></td>
                                    <td width="50%">
                                        <input type="hidden" value="{{ $tax ? $tax->ibg[0] : 0 }}" name="ibg[]" id="name-ibg">
                                        <select class="form-control select-field w-100" onchange="pilihNilaine(this)"
                                            name="ibg[]" id="ibg">
                                            @if ($tax)
                                            <option value="{{ $tax->ibg[1] }}">{{ $tax->ibg[0] }}</option>
                                        @else
                                            <option value="">Pilih</option>
                                        @endif
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <p id="view-ibg">{{ $tax ? $tax->ibg[2] : 0 }}</p>
                                        <input type="hidden" value="{{ $tax ? $tax->ibg[2] : 0 }}" name="ibg[]" id="index-ibg">
                                    </td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Indeks Lokalitas<i>(Ilo)</i></td>
                                    <td width="50%">
                                        <input type="number" value="{{ $tax ? $tax->ilo : 0 }}" name="ilo" class="form-control w-75"
                                            id="ilo" readonly>
                                    </td>
                                    <td class="text-center">
                                        <p id="view-ilo">{{ $tax ? $tax->ilo : 0 }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>Standar Harga Satuan Tertinggi (SHST)</td>
                                    <td width="50%">
                                        <input type="text" name="shst" id="shst" class="form-control">
                                    </td>
                                    <td class="text-center">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <h6 class="my-3">PERHITUNGAN NILAI RETRIBUSI BANGUNAN GEDUNG</h6>
                        <table class="table table-bordered">
                            <thead class="h6">
                                <tr>
                                    <th class="text-center" width="3%">No.</th>
                                    <th class="text-center" width="60%">Uraian</th>
                                    <th class="text-center">Luas (m<sup>2</sup>)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 1; $i < 11; $i++)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>
                                            <input type="text" class="form-control"
                                                name="par[{{ $i }}][]" value="{{$tax ? $par[$i][0] : null}}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" value="{{$tax ? $par[$i][1] : 0}}" onkeyup="sumWide()"
                                                class="form-control float-input" name="par[{{ $i }}][]">
                                        </td>
                                    </tr>
                                @endfor
                                <tr class="text-end">
                                    <td colspan="2"><strong>Luas Total Bangunan <i>(LLt)</i></strong></td>
                                    <td><input type="number" id="llt" name="llt" value="{{$tax ? $tax->llt : null}}" class="form-control"
                                            readonly></td>
                                </tr>
                                <tr class="text-end">
                                    <td colspan="2">
                                        <strong>NILAI RETRIBUSI BANGUNAN GEDUNG</strong><br>
                                        <span style="font-style:italic">(It x Ibg x Ilo x SHST x LLt)</span>
                                    </td>
                                    <td class="text-end">
                                        <p id="view-retri" class="my-auto">{{$tax ? number_format($tax->retri, 0, ',','.') : null}}</p>
                                        <input type="hidden" id="retri" name="retri" value="{{$tax ? $tax->retri : null}}" class="form-control">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <h6 class="my-3">PERHITUNGAN NILAI RETRIBUSI PRASARANA</h6>
                        <table class="table table-bordered">
                            <thead class="h6">
                                <tr>
                                    <th class="text-center" width="3%">No.</th>
                                    <th class="text-center" width="50%">Uraian</th>
                                    <th class="text-center">Volume</th>
                                    <th class="text-center">Satuan</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 1; $i < 11; $i++)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>

                                            <select class="form-control select-field ur" onchange="pilihUraian(this)"
                                                name="pra[{{ $i }}][]" data-id="{{ $i }}">
                                                @if($tax)
                                                    <option value="{{$pra[$i][0]}}">{{$pra[$i][0]}}</option>
                                                @else
                                                    <option value="">Pilih</option>
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number"  onkeyup="praSum(this)"
                                                data-id="{{ $i }}" class="form-control vol" value="{{$tax ? $pra[$i][1] : 0}}"
                                                name="pra[{{ $i }}][]">
                                        </td>
                                        <td>
                                        @php
                                        if($tax)
                                        {
                                            $sup = substr($pra[$i][2], -1) == 1 ? null : substr($pra[$i][2], -1);
                                            $var = substr($pra[$i][2], 0, -1);
                                        }
                                        @endphp
                                            <p class="text-center my-auto" id="view-sat{{ $i }}">{{$tax ? $var : null}}<sup>{{$tax ? $sup : null}}</sup></p>
                                            <input type="hidden" class="form-control" id="sat{{ $i }}" value="{{$tax ? $pra[$i][2] : null}}"
                                                name="pra[{{ $i }}][]">
                                        </td>
                                        <td>
                                            <p class="text-center my-auto" id="view-price{{ $i }}">
                                                {{$tax ? number_format($pra[$i][3],0,',','.') : 0}}
                                            </p>
                                            <input type="hidden" class="form-control" id="price{{ $i }}"
                                              name="pra[{{ $i }}][]" value="{{$tax ? $pra[$i][3] : 0}}">
                                        </td>
                                        <td>
                                            <p class="text-end my-auto" id="view-sum{{ $i }}">
                                                {{$tax ? number_format($pra[$i][4],0,',','.') : 0}}
                                            </p>
                                            <input type="hidden" value="{{$tax ? $pra[$i][4] : 0}}" class="form-control sum"
                                                id="sum{{ $i }}" name="pra[{{ $i }}][]" readonly>
                                        </td>
                                    </tr>
                                @endfor
                                <tr class="text-end">
                                    <td colspan="5"><strong>NILAI RETRIBUSI PRASARANA</strong></td>
                                    <td>
                                        <p id="view-sumPra" class="my-auto text-end">{{$tax ? number_format($tax->sumPra, 0, ',','.') : 0}}</p>
                                        <input type="hidden" name="sumPra" id="sumPra" value="{{$tax ? $tax->sumPra : 0}}"  class="form-control"
                                            readonly>
                                    </td>
                                </tr>
                                <tr class="text-end">
                                    <td colspan="5">
                                        <strong>TOTAL NILAI RETRIBUSI</strong><br>
                                        (NILAI RETRIBUSI BANGUNAN GEDUNG + NILAI RETRIBUSI PRASARANA)
                                    </td>
                                    <td>
                                        <p id="view-totRetri" class="my-auto text-end">{{$tax ? number_format($tax->totRetri, 0, ',','.') : 0}}</p>
                                        <input type="hidden" name="totRetri" id="totRetri" value="{{$tax ? $tax->totRetri : 0}}" class="form-control"
                                            readonly>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="col-md-12">
                            <button class="btn btn-primary rounded-pill">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </section>

    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/select/select2.min.js') }}"></script>

    <script type="text/javascript">
        const settings = {
            kompleksitas: 0.3,
            permanensi: 0.2,
            ketinggian: 0.5,
            shst: {{ $val->shst }},
            indeks_lokalitas: 0.5,
        };

        const indeks = {
            fungsi: [{
                    name: "Hunian (< 100 m2 dan < 2 lantai)",
                    index: "0.15",
                    ilo: "0.4"
                },
                {
                    name: "Hunian (> 100 m2 dan > 2 lantai)",
                    index: "0.17",
                    ilo: "0.4"
                },
                {
                    name: "Keagamaan",
                    index: "0",
                    ilo: "0.4"
                },
                {
                    name: "Usaha",
                    index: "0.7",
                    ilo: "0.4"
                },
                {
                    name: "Usaha UMKM",
                    index: "0.5",
                    ilo: "0.4"
                },
                {
                    name: "Sosial Budaya (Nirlaba/Non-Profit)",
                    index: "0.3",
                    ilo: "0.2"
                },
                {
                    name: "Sosial Budaya",
                    index: "0.3",
                    ilo: "0.4"
                },
                {
                    name: "Fungsi Khusus",
                    index: "1",
                    ilo: "0.4"
                },
                {
                    name: "Campuran < 500 m2 dan/atau <2 Lantai",
                    index: "0.6",
                    ilo: "0.4",
                },
                {
                    name: "Campuran > 500 m2 dan/atau >= 2 Lantai",
                    index: "0.8",
                    ilo: "0.4"
                },
            ],
            kepemilikan: [{
                    name: "Perorangan\/Badan Usaha",
                    index: "1"
                },
                {
                    name: "Negara",
                    index: "0"
                },
            ],
            kompleksitas: [{
                    name: "Sederhana",
                    index: "1"
                },
                {
                    name: "Tidak Sederhana",
                    index: "2"
                },
            ],
            permanensi: [{
                    name: "Non Permanen",
                    index: "1"
                },
                {
                    name: "Permanen",
                    index: "2"
                },
            ],
            ketinggian: [{
                    name: "Tidak Ada",
                    index: "0"
                },
                {
                    name: "1 Lapis",
                    index: "1.197"
                },
                {
                    name: "2 Lapis",
                    index: "1.299"
                },
                {
                    name: "3 Lapis",
                    index: "1.393"
                },
                {
                    name: "1 Lantai",
                    index: "1"
                },
                {
                    name: "2 Lantai",
                    index: "1.09"
                },
                {
                    name: "3 Lantai",
                    index: "1.12"
                },
                {
                    name: "4 Lantai",
                    index: "1.135"
                },
                {
                    name: "5 Lantai",
                    index: "1.162"
                },
                {
                    name: "6 Lantai",
                    index: "1.197"
                },
                {
                    name: "7 Lantai",
                    index: "1.236"
                },
                {
                    name: "8 Lantai",
                    index: "1.265"
                },
                {
                    name: "9 Lantai",
                    index: "1.299"
                },
                {
                    name: "10 Lantai",
                    index: "1.333"
                },
            ],
            kegiatan: [{
                    name: "Bangunan Gedung Baru",
                    index: "1"
                },
                {
                    name: "Rehabilitasi/Renovasi - Sedang",
                    index: "0.225"
                },
                {
                    name: "Rehabilitasi/Renovasi - Berat",
                    index: "0.325"
                },
                {
                    name: "Pelestarian/Pemugaran Pratama",
                    index: "0.325"
                },
                {
                    name: "Pelestarian/Pemugaran - Madya",
                    index: "0.225"
                },
                {
                    name: "Pelestarian/Pemugaran - Utama",
                    index: "0.15"
                },
            ]
        };

        const prasarana = 
        [
            {
                nama: "Pagar",
                hargaSatuan: 1500,
                satuan: "m1"
            },
            {
                nama: "Tanggul/retaining wall",
                hargaSatuan: 1400,
                satuan: "m1"
            },
            {
                nama: "Turap batas kavling/persil",
                hargaSatuan: 2500,
                satuan: "m1"
            },
            {
                nama: "Gapura",
                hargaSatuan: 12000,
                satuan: "m2"
            },
            {
                nama: "Gerbang",
                hargaSatuan: 14000,
                satuan: "m2"
            },
            {
                nama: "Jalan/Parkir/Conblock",
                hargaSatuan: 2000,
                satuan: "m2"
            },
            {
                nama: "Lapangan Upacara",
                hargaSatuan: 1500,
                satuan: "m2"
            },
            {
                nama: "Lapangan Olahraga Terbuka",
                hargaSatuan: 3000,
                satuan: "m2"
            },
            {
                nama: "Konstruksi perkerasan aspal / beton",
                hargaSatuan: 6000,
                satuan: "m2"
            },
            {
                nama: "Konstruksi perkerasan grassblock",
                hargaSatuan: 5000,
                satuan: "m2"
            },
            {
                nama: "Jembatan",
                hargaSatuan: 6000,
                satuan: "m2"
            },
            {
                nama: "Box culvert",
                hargaSatuan: 6000,
                satuan: "m2"
            },
            {
                nama: "Jembatan Antar Gedung",
                hargaSatuan: 50000,
                satuan: "m2"
            },
            {
                nama: "Jembatan Penyeberangan Orang/Barang",
                hargaSatuan: 300000,
                satuan: "m2"
            },
            {
                nama: "Jembatan Bawah Tanah / Underpass",
                hargaSatuan: 200000,
                satuan: "m2"
            },
            {
                nama: "Konstruksi Kolam Renang",
                hargaSatuan: 25000,
                satuan: "m2"
            },
            {
                nama: "Kolam Reservoir Bawah Tanah",
                hargaSatuan: 20000,
                satuan: "m2"
            },
            {
                nama: "Konstruksi septic tank / sumur resapan",
                hargaSatuan: 150000,
                satuan: "m2"
            },
            {
                nama: "Kontruksi Menara Reservoir (per 5 m2)",
                hargaSatuan: 50000,
                satuan: "Unit"
            },
            {
                nama: "Konstruksi Menara Cerobong (per 5 m2)",
                hargaSatuan: 180000,
                satuan: "Unit"
            },
            {
                nama: "Konstruksi Menara Air (per 5 m2)",
                hargaSatuan: 500000,
                satuan: "Unit"
            },
            {
                nama: "Konstruksi Monumen Tugu",
                hargaSatuan: 1000000,
                satuan: "Unit"
            },
            {
                nama: "Konstruksi Monumen Patung",
                hargaSatuan: 100000,
                satuan: "Unit"
            },
            {
                nama: "Monumen di Dalam Persil",
                hargaSatuan: 500000,
                satuan: "Unit"
            },
            {
                nama: "Monumen di Luar Persil (Nilai RAB Fisik)",
                hargaSatuan: 1.75,
                satuan: "Rp"
            },
            {
                nama: "Instalasi Listrik (maks. 10 m2)",
                hargaSatuan: 650000,
                satuan: "Unit"
            },
            {
                nama: "Instalasi Listrik (tambahan > 10 m2)",
                hargaSatuan: 6500,
                satuan: "m2"
            },
            {
                nama: "Instalasi Telepon (maks. 10 m2)",
                hargaSatuan: 500000,
                satuan: "Unit"
            },
            {
                nama: "Instalasi Telepon (tambahan > 10 m2)",
                hargaSatuan: 5000,
                satuan: "m2"
            },
            {
                nama: "Instalasi Pengolahan (maks. 10 m2)",
                hargaSatuan: 500000,
                satuan: "Unit"
            },
            {
                nama: "Instalasi Pengolahan (tambahan > 10 m2)",
                hargaSatuan: 5000,
                satuan: "m2"
            },
            {
                nama: "Reklame/Papan nama (maks. 30 m2)",
                hargaSatuan: 7000000,
                satuan: "Unit"
            },
            {
                nama: "Reklame/Papan nama (tambahan > 30 m2)",
                hargaSatuan: 500000,
                satuan: "m2"
            },
            {
                nama: "Konstruksi Pondasi Mesin",
                hargaSatuan: 500000,
                satuan: "Unit"
            },
            {
                nama: "Konstruksi Menara Televisi (maks. 100 m)",
                hargaSatuan: 75000000,
                satuan: "Unit"
            },
            {
                nama: "Antena Radio Standing 25-50 m",
                hargaSatuan: 5000000,
                satuan: "Unit"
            },
            {
                nama: "Antena Radio Standing 51-75 m",
                hargaSatuan: 7500000,
                satuan: "Unit"
            },
            {
                nama: "Antena Radio Standing 76-100 m",
                hargaSatuan: 10000000,
                satuan: "Unit"
            },
            {
                nama: "Antena Radio Standing 101-125 m",
                hargaSatuan: 12500000,
                satuan: "Unit"
            },
            {
                nama: "Antena Radio Standing 126-150 m",
                hargaSatuan: 15000000,
                satuan: "Unit"
            },
            {
                nama: "Antena Radio Standing > 150 m",
                hargaSatuan: 25000000,
                satuan: "Unit"
            },
            {
                nama: "Antena Radio Guywire 0-50 m",
                hargaSatuan: 2500000,
                satuan: "Unit"
            },
            {
                nama: "Antena Radio Guywire 51-75 m",
                hargaSatuan: 4000000,
                satuan: "Unit"
            },
            {
                nama: "Antena Radio Guywire 76-100 m",
                hargaSatuan: 5000000,
                satuan: "Unit"
            },
            {
                nama: "Antena Radio Guywire > 100 m",
                hargaSatuan: 10000000,
                satuan: "Unit"
            },
            {
                nama: "Tower Telekomunikasi Bersama < 25 m",
                hargaSatuan: 25000000,
                satuan: "Unit"
            },
            {
                nama: "Tower Telekomunikasi Bersama 25 - 50 m",
                hargaSatuan: 45000000,
                satuan: "Unit"
            },
            {
                nama: "Tower Telekomunikasi Bersama > 50 m",
                hargaSatuan: 60000000,
                satuan: "Unit"
            },
            {
                nama: "Tower Telekomunikasi Mandiri < 25 m",
                hargaSatuan: 35000000,
                satuan: "Unit"
            },
            {
                nama: "Tower Telekomunikasi Mandiri 25 - 50 m",
                hargaSatuan: 75000000,
                satuan: "Unit"
            },
            {
                nama: "Tower Telekomunikasi Mandiri > 50 m",
                hargaSatuan: 125000000,
                satuan: "Unit"
            },
            {
                nama: "Tangki Tanam Bahan Bakar",
                hargaSatuan: 10000000,
                satuan: "Unit"
            },
            {
                nama: "Pekerjaan Drainase (dalam persil)",
                hargaSatuan: 1000,
                satuan: "m1"
            },
            {
                nama: "Konstruksi Penyimpanan / Silo",
                hargaSatuan: 1000,
                satuan: "m2"
            }
        ];   
    </script>

    <script src="{{ asset('assets/tax.js') }}"></script>
@endpush
