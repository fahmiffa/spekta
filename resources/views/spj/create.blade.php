@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/select/tom-select.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/summernote/summernote-lite.css') }}">
    <style>

        .suggestions {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            background: white;
            width: 100%;
            z-index: 999;
        }
        .suggestion-item {
            padding: 8px;
            cursor: pointer;
        }
        .suggestion-item:hover {
            background-color: #f0f0f0;
        }

    </style>
@endpush
@section('main')
    <div class="page-heading">

        <section class="section">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title">Dokumen SPJ</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('spj.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @isset($doc)
                            <input type="hidden" name="doc" value="{{ md5($doc->id) }}">
                            @endif
                            <div class="form-group row mb-3">

                                <div class="col-md-6 col-sm-12">
                                    <label>Tanggal Surat :</label>
                                    <input type="date" name="tanggal" class="form-control"
                                        value="{{ isset($doc) ? date('Y-m-d', strtotime($doc->time)) : old('tanggal') }}"
                                        required>
                                    @error('tanggal')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <label>Tanggal Acara :</label>
                                    <input type="date" name="program" class="form-control"
                                        value="{{ isset($doc) ? date('Y-m-d', strtotime($doc->program)) : old('program') }}"
                                        required>
                                    @error('program')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label>Pilih Tipe</label>
                                    <select class="form-control" name="tipe" id="tipe" onchange="onVal()"
                                        placeholder="Pilih tipe" required>
                                        <option value="">Pilih</option>
                                        @php
                                            $val = spjDoc();
                                        @endphp
                                        @foreach ($val as $row)
                                            <option value="{{ $row }}" @selected(isset($doc) && $doc->type == $row)>
                                                {{ ucwords(str_replace('pbg', 'PBG', str_replace('_', ' ', $row))) }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipe')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-sm-12 {{isset($doc) && $doc->survey ? '' : 'd-none'}} mb-3" id="survey">
                                    <label>Tanggal Survey :</label>
                                    <input type="date" name="survey" class="form-control"
                                        value="{{ isset($doc) ? date('Y-m-d', strtotime($doc->survey)) : old('program') }}">
                                    @error('survey')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 my-3">
                                    <label>Pilih Pemohon</label>
                                    <select class="form-control sel" name="pemohon[]" onchange="getVal()" id="sel"
                                        multiple required>
                                        <option value="">Pilih</option>
                                        @foreach ($pemohon as $item)
                                            <option value="{{ $item->head }}" @selected(isset($doc) && in_array($item->head, $head))>
                                                {{ $item->pemohon }} {{ $item->reg }}</option>
                                        @endforeach
                                    </select>
                                    @error('pemohon')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3 {{ isset($doc) && $doc->type != 'rapat_persiapan_survey' ? null : 'd-none' }} position-relative"
                                    id="peserta">
                                    <label class="mb-3">Peserta</label>
                                    <div id="extend">
                                        @isset($doc)
                                            @php
                                                $extend = $doc->extend ? json_decode($doc->extend) : [];
                                            @endphp
                                            @foreach ($extend as $item)
                                                <div class="row mb-2">
                                                    <div class="col-8">
                                                        <input type="hidden" name="plus[]"
                                                            value="{{ $item }}">
                                                        <span>{{$item}}</span>
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm my-auto" style="width:fit-content;height:fit-content"  onclick="remove(this)"><i
                                                            class="bi bi-trash"></i></button>
                                                </div>
                                            @endforeach
                                            @endif
                                    </div>
                                    <div class="row my-3">
                                        <div class="col-8">
                                            <input type="text" id="plus" class="form-control" placeholder="peserta" autocomplete="off">
                                            <div id="suggestions" class="suggestions" style="display: none;"></div>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm my-auto" style="width:fit-content;height:fit-content" onclick="extended()">Tambah</button>
                                    </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-md-12">
                                        <label>Lampiran</label><br>
                                        <div class="form-check d-flex-inline">
                                            <input class="form-check-input" type="checkbox" onchange="upload()" id="in"
                                                name="in">
                                            <span class="d-block mx-1"></span>
                                            <label>File</label>
                                        </div>
                                        <small class="text-danger fw-bold">Format ekstensi upload PDF</small>
                                        <input class="form-control" name="pile" id="pile" type="file" accept=".pdf"
                                            disabled>
                                        @error('pile')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                        <br>
                                        <textarea class="form-control summernote" name="content" rows="2">  {!! isset($doc) ? $doc->note : old('content') !!}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-12 mb-3">
                                        <label>Pilih Pelapor</label>
                                        <select class="form-control sel" id="pelapor" name="pelapor" placeholder="Pilih pelapor"
                                            required>
                                            @isset($doc)
                                                @foreach ($user as $row)
                                                    <option value="{{ $row->id }}" @selected($row->id == $doc->report)>
                                                        {{ $row->name }}</option>
                                                @endforeach
                                                @endif
                                                <option value="">Pilih</option>
                                            </select>
                                            @error('pelapor')
                                                <div class='small text-danger text-left'>{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-primary rounded-pill">Save</button>
                                            <a class="btn btn-danger ms-1 rounded-pill" href="{{ route('spj.index') }}">Back</a>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>

                    </section>

                </div>
            @endsection

            @push('js')
                <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
                <script src="{{ asset('assets/select/tom-select.complete.min.js') }}"></script>
                <script src="{{ asset('assets/extensions/summernote/summernote-lite.min.js') }}"></script>
                <script src="{{ asset('assets/editor.js') }}"></script>
                <script>
                    let pes = [];

                    @isset($doc)
                        pes = @json($da)
                    @endif

                    function getVal() {
                        var sel = $('#sel').val();
                        var tipe = $('#tipe').val();


                        $.ajax({
                            type: 'POST',
                            data: {
                                da: tipe,
                                sel: sel
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('spj.data') }}",
                            success: function(data) {
                                pes = data.da;
                                $("#pelapor").empty();
                                $.each(data.pelapor, function(i, val) {
                                    $("#pelapor").append('<option value="' + val.id + '">' + val.name +
                                    '</option>');
                                });

                            }
                        });
                    }

                    const input = document.getElementById('plus');
                    const suggestionBox = document.getElementById('suggestions');

                    input.addEventListener('input', () => {
                    const value = input.value.toLowerCase();
                    suggestionBox.innerHTML = '';
                    if (value) {
                        const filtered = pes.filter(item => item.toLowerCase().includes(value));
                        filtered.forEach(item => {
                        const div = document.createElement('div');
                        div.classList.add('suggestion-item');
                        div.textContent = item;
                        div.addEventListener('click', () => {
                            input.value = item;
                            suggestionBox.style.display = 'none';
                        });
                        suggestionBox.appendChild(div);
                        });
                        suggestionBox.style.display = filtered.length ? 'block' : 'none';
                    } else {
                        suggestionBox.style.display = 'none';
                    }
                    });

                    document.addEventListener('click', (e) => {
                    if (!e.target.closest('#plus')) {
                        suggestionBox.style.display = 'none';
                    }
                    });

                    var select = new TomSelect(".sel", {
                        plugins: ['remove_button'],
                        persist: false,
                        create: true
                    });

                    function upload() {
                        const input = document.getElementById('pile');
                        const val = document.getElementById('in');
                        input.disabled = !val.checked;
                    }


                    function remove(e) {
                        e.parentNode.remove();
                    }


                    function extended() {
                        var val = $('#plus').val();
                        $("#extend").append('<div class="row mb-2">\
                                    <div class="col-8"><input type="hidden" name="plus[]" value="' + val + '"><span>'+val+'</span></div>\
                                    <button type="button" class="btn btn-danger btn-sm my-auto" style="width:fit-content;height:fit-content" onclick="remove(this)"><i class="bi bi-trash"></i></button>\
                                    </div>');
                        $('#plus').val(null);
                    }


                    function onVal() {
                        var tipe = $('#tipe').val();
                        if (tipe === "rapat_persiapan_survey") {
                            $("#peserta").addClass("d-none");
                            $("#survey").removeClass("d-none");
                        } else {
                            $("#survey").addClass("d-none");
                            $("#peserta").removeClass("d-none");
                        }

                        $('#pelapor').val(null);
                        select.clear();
                    }
                </script>
            @endpush
