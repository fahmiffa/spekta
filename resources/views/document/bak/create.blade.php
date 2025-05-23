@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/summernote/summernote-lite.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/select/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/select/select2-bootstrap-5-theme.rtl.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/select/select2-bootstrap-5-theme.min.css') }}" />
    <style>
        .symbol-btn {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
    </style>
@endpush
@section('main')
    <div class="page-heading">

        <section class="section">
            <div class="card">

                <div class="card-header">
                    <div class="divider">
                        <div class="divider-text">{{ $data }}</div>
                    </div>
                    @include('document.pemohon')
                </div>

                <div class="card-body">

                    <form action="{{ route('news.store') }}" method="post" id="publish" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="doc" value="{{ md5($head->id) }}">
                        @isset($news)
                            @php
                                $header = json_decode($news->header);
                                $item = json_decode($news->item);
                                $iu = $item->informasi_umum;
                                $ibg = $item->informasi_bangunan_gedung;
                            @endphp
                        @endisset
                        <div class="row">
                            <h6>Batas Lahan / Lokasi</h6>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Utara</label>
                                    <input type="text" name="north"
                                        value="{{ isset($news) ? $header->north : old('north') }}" class="form-control">
                                    @error('north')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Selatan</label>
                                    <input type="text" name="south"
                                        value="{{ isset($news) ? $header->south : old('south') }}" class="form-control">
                                    @error('south')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Timur</label>
                                    <input type="text" name="east"
                                        value="{{ isset($news) ? $header->east : old('east') }}" class="form-control">
                                    @error('east')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Barat</label>
                                    <input type="text" name="west"
                                        value="{{ isset($news) ? $header->west : old('west') }}" class="form-control">
                                    @error('west')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Kondisi</label>
                                    <select class="form-control" name="kondisi" placeholder="kondisi">
                                        <option value="">Pilih kondisi</option>
                                        <option value="belum_dibangun" @selected(old('kondisi') == 'belum_dibangun')
                                            @selected(isset($news) && $header->kondisi == 'belum_dibangun')>
                                            Belum Dibangun</option>
                                        <option value="sedang_dibangun" @selected(old('kondisi') == 'sedang_dibangun')
                                            @selected(isset($news) && $header->kondisi == 'sedang_dibangun')>
                                            Sedang Dibangun</option>
                                        <option value="sudah_dibangun" @selected(old('kondisi') == 'sudah_dibangun')
                                            @selected(isset($news) && $header->kondisi == 'sudah_dibangun')>Sudah Dibangun</option>
                                    </select>
                                    @error('kondisi')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Tingkat Permanensi</label>
                                    <select class="form-control" name="permanensi">
                                        <option value="">Pilih permanensi</option>
                                        <option value="permanen" @selected(old('permanensi') == 'permanen') @selected(isset($news) && $header->permanensi == 'permanen')>
                                            Permanen</option>
                                        <option value="non_permanen" @selected(old('permanensi') == 'non_permanen')
                                            @selected(isset($news) && $header->permanensi == 'non_permanen')>Non Permanen</option>
                                    </select>
                                    @error('permanensi')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Tahun Pembangunan</label>
                                    <input type="number" name="build"
                                        value="{{ isset($news) && $news->plan ? $news->plan : null }}"
                                        class="form-control">
                                    @error('build')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <h6>Informasi Umum :</h6>
                            @error('width')
                                <div class='small text-danger text-left'>{{ $message }}</div>
                            @enderror
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Lebar Jalan</label>
                                    <input type="hidden" name="val[]" value="Lebar Jalan" class="form-control">
                                    <textarea class="form-control" name="width[]" rows="2">{{ isset($news) ? $iu[0]->value : null }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Lebar Sungai</label>
                                    <input type="hidden" name="val[]" value="Lebar Sungai" class="form-control">
                                    <textarea class="form-control" name="width[]" rows="2">{{ isset($news) ? $iu[1]->value : null }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Lebar Saluran/Irigasi</label>
                                    <input type="hidden" name="val[]" value="Lebar Saluran/Irigasi" class="form-control">
                                    <textarea class="form-control" name="width[]" rows="2">{{ isset($news) ? $iu[2]->value : null }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Luas Lahan</label>
                                    <input type="hidden" name="val[]" value="Luas Lahan" class="form-control">
                                    <textarea class="form-control" name="width[]" rows="2">{{ isset($news) ? $iu[3]->value : null }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Jumlah Basement</label>
                                    <input type="hidden" name="val[]" value="Jumlah Basement" class="form-control">
                                    <textarea class="form-control" name="width[]" rows="2">{{ isset($news) ? $iu[4]->value : null }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Jumlah Lantai</label>
                                    <input type="hidden" name="val[]" value="Jumlah Lantai" class="form-control">
                                    <textarea class="form-control" name="width[]" rows="2">{{ isset($news) ? $iu[5]->value : null }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-md-4">
                                <input type="text" value="{{ isset($news) && isset($iu[6]->uraian) ? $iu[6]->uraian : null }}"  class="form-control" name="val[]" placeholder="Parameter">
                            </div>
                            <div class="col-md-4">
                                <input type="text" value="{{ isset($news) && isset($iu[6]->value) ? $iu[6]->value : null }}"  class="form-control" name="width[]" placeholder="Nilai">
                            </div>
                        </div> 
                        
                        <div class="form-group row mb-3">
                            <div class="col-md-4">
                                <input type="text"  value="{{ isset($news) && isset($iu[7]->uraian) ? $iu[7]->uraian : null }}" class="form-control" name="val[]" placeholder="Parameter">
                            </div>
                            <div class="col-md-4">
                                <input type="text" value="{{ isset($news) && isset($iu[7]->value) ? $iu[7]->value : null }}" class="form-control" name="width[]" placeholder="Nilai">
                            </div>
                        </div> 

                        <div class="form-group row mb-3">
                            <div class="col-md-4">
                                <input type="text" value="{{ isset($news) && isset($iu[8]->uraian) ? $iu[8]->uraian : null }}" class="form-control" name="val[]" placeholder="Parameter">
                            </div>
                            <div class="col-md-4">
                                <input type="text" value="{{ isset($news) && isset($iu[8]->value) ? $iu[8]->value : null }}" class="form-control" name="width[]" placeholder="Nilai">
                            </div>
                        </div> 

                        <div class="row">
                            <h6>Informasi Bangunan Gedung :</h6>
                            <div class="col-md-4 my-auto">
                                <div class="form-group mb-3">
                                    <label>Garis Sempadan Bangunan</label>
                                    <input type="hidden" name="gsn[]" value="garis_sempadan_bangunan"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <input type="text" name="gsn[]"
                                        value="{{ isset($news) ? $ibg[0]->dimensi : null }}" class="form-control"
                                        placeholder="Dimensi">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <textarea class="form-control" name="gsn[]" rows="1" placeholder="Catatan">{{ isset($news) ? $ibg[0]->note : null }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-4 my-auto">
                                <div class="form-group mb-3">
                                    <label>Garis Sempadan Sungai</label>
                                    <input type="hidden" name="gsi[]" value="garis_sempadan_sungai"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <input type="text" name="gsi[]" class="form-control"
                                        value="{{ isset($news) ? $ibg[1]->dimensi : null }}" placeholder="Dimensi">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <textarea class="form-control" name="gsi[]" rows="1" placeholder="Catatan">{{ isset($news) ? $ibg[1]->note : null }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-4 my-auto">
                                <div class="form-group mb-3">
                                    <label>Garis Sempadan Saluran</label>
                                    <input type="hidden" name="gsl[]" value="garis_sempadan_saluran"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <input type="text" name="gsl[]" class="form-control"
                                        value="{{ isset($news) ? $ibg[2]->dimensi : null }}" placeholder="Dimensi">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <textarea class="form-control" name="gsl[]" rows="1" placeholder="Catatan">{{ isset($news) ? $ibg[2]->note : null }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-4 my-auto">
                                <div class="form-group mb-3">
                                    <label>Garis Sempadan Danau/Pantai</label>
                                    <input type="hidden" name="gsu[]" value="garis_sempadan_danau/pantai"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <input type="text" name="gsu[]" class="form-control" placeholder="Dimensi"
                                        value="{{ isset($news) ? $ibg[3]->dimensi : null }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <textarea class="form-control" name="gsu[]" rows="1" placeholder="Catatan">{{ isset($news) ? $ibg[3]->note : null }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-4 my-auto">
                                <div class="form-group mb-3">
                                    <label>Garis Sempadan Pagar</label>
                                    <input type="hidden" name="gsr[]" value="garis_sempadan_pagar"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <input type="text" name="gsr[]" class="form-control" placeholder="Dimensi"
                                        value="{{ isset($news) ? $ibg[4]->dimensi : null }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <textarea class="form-control" name="gsr[]" rows="1" placeholder="Catatan">{{ isset($news) ? $ibg[4]->note : null }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-4 my-auto">
                                <div class="form-group mb-3">
                                    <label>Garis Sempadan Rel Kereta Api</label>
                                    <input type="hidden" name="gsra[]" value="garis_sempadan_rel_kereta_api"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <input type="text" name="gsra[]" class="form-control" placeholder="Dimensi"
                                        value="{{ isset($news) ? $ibg[5]->dimensi : null }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <textarea class="form-control" name="gsra[]" rows="1" placeholder="Catatan">{{ isset($news) ? $ibg[5]->note : null }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-4 my-auto">
                                <div class="form-group mb-3">
                                    <label>Koefisien Dasar Bangunan</label>
                                    <input type="hidden" name="kdb[]" value="koefiesien_dasar_bangunan"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <input type="text" name="kdb[]" class="form-control" placeholder="Dimensi"
                                        value="{{ isset($news) ? $ibg[6]->dimensi : null }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <textarea class="form-control" name="kdb[]" rows="1" placeholder="Catatan">{{ isset($news) ? $ibg[6]->note : null }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-4 my-auto">
                                <div class="form-group mb-3">
                                    <label>Koefisien Dasar Hijau</label>
                                    <input type="hidden" name="kdh[]" value="koefiesien_dasar_hijau"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <input type="text" name="kdh[]" class="form-control" placeholder="Dimensi"
                                        value="{{ isset($news) ? $ibg[7]->dimensi : null }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <textarea class="form-control" name="kdh[]" rows="1" placeholder="Catatan">{{ isset($news) ? $ibg[7]->note : null }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-4 my-auto">
                                <div class="form-group mb-3">
                                    <label>Koefisien Lantai Bangunan</label>
                                    <input type="hidden" name="kl[]" value="koefiesien_lantai_bangunan"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <input type="text" name="kl[]" class="form-control" placeholder="Dimensi"
                                        value="{{ isset($news) ? $ibg[8]->dimensi : null }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <textarea class="form-control" name="kl[]" rows="1" placeholder="Catatan">{{ isset($news) ? $ibg[8]->note : null }}</textarea>
                                </div>
                            </div>

                        </div>

                        @if (isset($news) && $news->ibg)
                            @foreach (json_decode($news->ibg) as $par)
                                <div class="form-group row mb-3">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" value="{{ $par[0] }}"
                                            name="par[]" placeholder="Parameter">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" value="{{ $par[1] }}"
                                            name="par_d[]" placeholder="Dimensi">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" value="{{ $par[2] }}"
                                            name="par_c[]" placeholder="Catatan">
                                    </div>
                                    <button class="btn btn-danger btn-sm my-auto"
                                        style="width:fit-content;height:fit-content" onclick="remove(this)"
                                        type="button"><i class="bi bi-trash"></i></button>
                                </div>
                            @endforeach
                        @endif
                        <button type="button" id="add" class="btn btn-sm btn-primary rounded-pill">Tambah</button>
                        <div id="input" class="my-3"></div>

                        <div class="row">
                            <h6>Informasi Dimensi Bangunan dan Prasarana :</h6>
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label>Bangunan Gedung</label>
                                    <input type="hidden" name="idb[]" value="Informasi Dimensi Bangunan dan Prasarana"
                                        class="form-control">
                                    <textarea class="form-control summernote" name="idb[]" rows="2">{{ isset($news) ? $item->idb[1] : null }}</textarea>
                                </div>
                            </div>
                            <h6>Informasi Dimensi Prasarana :</h6>
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label>Prasarana :</label>
                                    <input type="hidden" name="idp[]" value="Informasi Dimensi Prasarana"
                                        class="form-control">
                                    <textarea class="form-control summernote" name="idp[]" rows="2">{{ isset($news) ? $item->idp[1] : null }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <h6>Catatan :</h6>
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <textarea class="form-control summernote" name="note" rows="2">{{ isset($news) ? $news->note : null }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-md-8">
                            <label>Lampiran</label><br>
                            <small class="text-danger fw-bold">Format ekstensi upload PDF</small>
                            <input class="form-control" name="pile" type="file" accept=".pdf">
                            @error('pile')
                            <div class='small text-danger text-left'>{{ $message }}</div>
                            @enderror
                            @isset($news)
                                @if($news && $news->files)
                                    <br>
                                    <div class="form-check d-flex-inline">
                                        <input class="form-check-input" type="checkbox" name="in">
                                        <span class="d-block mx-1"></span>
                                        <label>Reset Lampiran</label>
                                    </div>
                                @endif
                            @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Ditetapkan</label>
                                <input type="text" name="place"
                                    value="{{ isset($news) ? $news->place : old('place') }}" class="form-control">
                                @error('place')
                                    <div class='small text-danger text-left'>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between" id="load">
                            <button type="submit" class="btn btn-dark rounded-pill"><i class="bi bi-archive"></i>
                                Draft</button>
                        </div>
                </div>
            </div>
   
        </section>

    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/select/select2.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('assets/editor.js') }}"></script>

    <script>

        $('.select-field').select2({
            theme: 'bootstrap-5'
        });

        $('#dis').on('change', function(e) {
            e.preventDefault();
            $('#des').empty();
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('village.news') }}",
                data: {
                    id: $(this).val()
                },
                success: function(data) {
                    $.each(data, function(key, value) {
                        $('#des').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        });

        function remove(e) {
            e.parentNode.remove();
        }

        $('#add').on('click', function() {
            var
                clonedDiv = '<div class="form-group row mb-3">\
                                                                            <div class="col-md-4">\
                                                                                <input type="text" class="form-control" name="par[]" placeholder="Parameter">\
                                                                            </div>\
                                                                            <div class="col-md-4">\
                                                                               <input type="text" class="form-control" name="par_d[]" placeholder="Dimensi">\
                                                                            </div>\
                                                                            <div class="col-md-3">\
                                                                               <input type="text" class="form-control" name="par_c[]" placeholder="Catatan">\
                                                                            </div>\
                                                                            <button class="btn btn-danger btn-sm my-auto" style="width:fit-content;height:fit-content" onclick="remove(this)"  type="button"><i class="bi bi-trash"></i></button>\
                                                                        </div>\
                                                                        ';
            $('#input').append(clonedDiv);
        });

    </script>
@endpush
