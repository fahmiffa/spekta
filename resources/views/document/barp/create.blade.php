@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/summernote/summernote-lite.css') }}">
@endpush
@section('main')
    <div class="page-heading">

        <section class="section">
            <div class="card">

                <div class="card-header">
                    <div class="divider">
                        <div class="divider-text">{!! $data !!}</div>
                    </div>
                    @include('document.pemohon')
                </div>

                <div class="card-body">

                    <form action="{{ route('meet.store') }}" method="post" id="publish">
                        @csrf
                        <input type="hidden" name="doc" value="{{ md5($head->id) }}">
                        @isset($meet)
                            @php
                                $header = json_decode($meet->header);
                                $da = json_decode($meet->item);
                                $val = $da->val;
                                $text = $da->text;
                            @endphp
                        @endisset
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>NIB</label>
                                    <input type="text" name="nib"
                                        value="{{ isset($meet) ? $header->nib : old('nib') }}" class="form-control">
                                    @error('nib')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Tanggal Konsultasi</label>
                                    <input type="date" name="date"
                                        value="{{ isset($meet) ? $meet->tanggal : old('date') }}" class="form-control">
                                    @error('date')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Status Kepemilikan</label>
                                    <select class="form-control" name="status" placeholder="status">
                                        <option value="">Pilih Status</option>
                                        <option value="perorangan" @selected(old('status') == 'perorangan')
                                            @selected(isset($meet) && $header->status == 'perorangan')>Perorangan / Badan Usaha / Badan Hukum</option>
                                        <option value="pemerintah" @selected(old('status') == 'pemerintah')
                                            @selected(isset($meet) && $header->status == 'pemerintah')>Pemerintah / Negara</option>
                                    </select>
                                    @error('status')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Jenis Permohonan</label>
                                    <select class="form-control" name="jenis">
                                        <option value="">Pilih jenis</option>
                                        @php
                                            $jenis = [
                                                'baru',
                                                'perubahan',
                                                'kolektif',
                                                'prasarana',
                                                'cagar_budaya',
                                                'existing',
                                            ];
                                        @endphp
                                        @foreach ($jenis as $item)
                                            <option value="{{ $item }}" @selected(old('jenis') == $item)
                                                @selected(isset($meet) && $header->jenis == $item)>{{ ucwords(str_replace('_', ' ', $item)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Fungsi Bangunan</label>
                                    <select class="form-control" name="fungsi">
                                        <option value="">Pilih fungsi</option>
                                        @php
                                            $var = [
                                                'hunian',
                                                'keagamaan',
                                                'usaha',
                                                'sosial_budaya',
                                                'khusus',
                                                'campuran',
                                            ];
                                        @endphp
                                        @foreach ($var as $item)
                                            <option value="{{ $item }}" @selected(old('fungsi') == $item)
                                                @selected(isset($meet) && $header->fungsi == $item)>{{ ucwords(str_replace('_', ' ', $item)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('fungsi')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <p>Sebagaimana terlampir pada Lembar Berita Acara Konsultasi
                                        No.
                                        {{ str_replace('SPm', 'BAK', str_replace('600.1.15', '600.1.15/PBLT', $head->nomor)) }}
                                        yang
                                        merupakan bagian tidak terpisahkan dari Berita Acara Rapat Pleno ini,
                                        TPT/TPA memberikan masukkan:
                                    </p>
                                    <textarea class="form-control summernote" name="item[]" rows="2">{{ isset($meet) ? $da->item[0] : null }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label>Dan dengan pertimbangan bahwa :</label>
                                    <textarea class="form-control summernote" name="item[]" rows="2">{{ isset($meet) ? $da->item[1] : null }}</textarea>
                                </div>
                            </div>
                            @error('val')
                                <div class='small text-danger text-left'>{{ $message }}</div>
                            @enderror
                            @php
                                $default = $meet == null ?? null;
                            @endphp
                            <div class="col-md-12 my-3">
                                <div class="form-check mb-3">
                                    <input type="radio" class="form-check-input form-check-success form-check-glow"
                                        name="val" {{ isset($meet) && $val == 1 ? 'checked' : $default }} value="1">
                                    <label class="form-check-label">Merekomendasikan penerbitan Surat Pernyataan
                                        Pemenuhan Standar Teknis PBG dan/atau SLF dengan :
                                    </label>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-md-4 mb-3">
                                        <h6>Uraian</h6>
                                        Luas Total Bangunan termasuk <br>Luas Total Basement (LLt)
                                    </div>
                                    <div class="col-md-3">
                                        <h6>Pengajuan</h6>
                                        <input type="text" name="luas[]"
                                            value="{{ isset($meet) ? $da->luas[0] : null }}" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <h6>Disetujui</h6>
                                        <input type="text" name="luas[]"
                                            value="{{ isset($meet) ? $da->luas[1] : null }}" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <h6>Keterangan</h6>
                                        <input type="text" name="luas[]"
                                            value="{{ isset($meet) ? $da->luas[2] : null }}" class="form-control">
                                    </div>
                                </div>                            
                                @if ($meet && $meet->other)
                                    @php $other = json_decode($meet->other) @endphp

                                    @for ($i = 0; $i < count($other); $i++)
                                        <div class="form-group row mb-3">
                                            <div class="col-md-3">
                                                <input type="text" class="form-control"
                                                    value="{{ $other[$i]->uraian }}" name="uraian[]"
                                                    placeholder="Uraian">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control"
                                                    value="{{ $other[$i]->pengajuan }}" name="pengajuan[]"
                                                    placeholder="Pengajuan">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control"
                                                    value="{{ $other[$i]->disetujui }}" name="disetujui[]"
                                                    placeholder="Disetujui">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control"
                                                    value="{{ $other[$i]->keterangan }}" name="keterangan[]"
                                                    placeholder="Keterangan">
                                            </div>
                                            <button class="btn btn-danger btn-sm my-auto"
                                                style="width:fit-content;height:fit-content" onclick="remove(this)"
                                                type="button"><i class="bi bi-trash"></i></button>
                                        </div>
                                    @endfor
                                @endif
                                <button type="button" id="add"
                                    class="btn btn-sm btn-primary rounded-pill">Tambah</button>
                                <div id="input" class="my-3"></div>
                            </div>
                            <div class="col-md-12">                     
                                <div class="form-check">
                                    <input type="radio" class="form-check-input form-check-warning form-check-glow"
                                        name="val" {{ isset($meet) && $val == 2 ? 'checked' : $default }} value="2">
                                    <label class="form-check-label">Merekomendasikan pemohon untuk melakukan
                                        pendaftaran ulang PBG dan/atau SLF melalui SIMBG
                                    </label>
                                </div>                 
                                <textarea class="form-control summernote" name="text2" rows="2">{!! isset($meet) && $val == 2 ? $text : null !!}</textarea>
                            </div>
                            <div class="col-md-12 my-3">                                
                                <div class="form-check">
                                    <input type="radio" class="form-check-input form-check-danger form-check-glow"
                                        name="val" {{ isset($meet) && $val == 3 ? 'checked' : $default }} value="3">
                                    <label class="form-check-label">Proses PBG dan/atau SLF tidak dapat dilanjutkan
                                        / ditolak
                                    </label>
                                </div>
                                <textarea class="form-control summernote" name="text3" rows="2">{!! isset($meet) && $val == 3 ? $text : null !!}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Ditetapkan</label>
                                <input type="text" name="place"
                                    value="{{ isset($meet) ? $meet->place : old('place') }}" class="form-control">
                                @error('place')
                                    <div class='small text-danger text-left'>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label>Tanggal</label>
                                <input type="date" name="tang"
                                    value="{{ isset($meet) ? $meet->date : old('tang') }}" class="form-control">
                                @error('tang')
                                    <div class='small text-danger text-left'>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between my-3" id="load">
                            <button type="submit" class="btn btn-warning rounded-pill"><i class="bi bi-archive"></i>
                                Draft</button>
                            <div class="d-flex justify-content-start">
                                <a class="btn btn-danger rounded-pill" href="{{ route('meet.index') }}">Back</a>
                            </div>
                        </div>
                </div>
            </div>

        </section>

    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/extensions/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('assets/editor.js') }}"></script>
    <script>
        function remove(e) {
            e.parentNode.remove();
        }

        $('#add').on('click', function() {
            var clonedDiv = '<div class="form-group row mb-3">\
                                    <div class="col-md-3">\
                                        <input type="text" class="form-control" name="uraian[]" placeholder="Uraian">\
                                    </div>\
                                    <div class="col-md-2">\
                                       <input type="text" class="form-control" name="pengajuan[]" placeholder="Pengajuan">\
                                    </div>\
                                    <div class="col-md-2">\
                                       <input type="text" class="form-control" name="disetujui[]" placeholder="Disetujui">\
                                    </div>\
                                    <div class="col-md-3">\
                                        <input type="text" class="form-control" name="keterangan[]" placeholder="Keterangan">\
                                    </div>\
                                    <button class="btn btn-danger btn-sm my-auto" style="width:fit-content;height:fit-content" onclick="remove(this)"  type="button"><i class="bi bi-trash"></i></button>\
                                </div>\
                                ';
            $('#input').append(clonedDiv);
        });
    </script>
@endpush
