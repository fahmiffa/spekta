@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/summernote/summernote-lite.css') }}">
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
                    <h5 class="card-title">Penugasan Konsultasi</h5>
                </div>

                <div class="card-body">

                    @isset($consultation)
                        <form action="{{ route('consultation.update', ['consultation' => $consultation]) }}" method="post"
                            enctype="multipart/form-data">
                            @method('PATCH')
                            @php
                            $time = explode('#',$schedule->waktu);
                            $place = explode('#',$schedule->tempat);
                        @endphp
                        @else
                            <form action="{{ route('consultation.store') }}" method="post" enctype="multipart/form-data">
                                @endif
                                @csrf
                                <div class="px-5">

                                    <div class="form-group row mb-3">
                                        <div class="col-12 mb-3">
                                            <label>Pilih Dokumen</label>
                                            <select class="choices form-select" name="doc" id="doc">
                                                <option value="">Pilih Dokumen</option>
                                                @foreach ($doc as $item)
                                                @php
                                                    $header = (array) json_decode($item->header);
                                                @endphp
                                                    <option value="{{ $item->id }}" @selected(isset($consultation) && $consultation->head == $item->id) 
                                                        @selected(old('doc') == $item->id)>
                                                        {{ $item->reg }} ({{ $item->nomor }}) {{ $header ? $header[2] : null }}</option>
                                                @endforeach
                                            </select>
                                            @error('doc')
                                                <div class='small text-danger text-left'>{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>                              

                                    <div class="form-group row mb-3">
                                        <div class="col-md-8 mb-3">
                                            <label>Ketua dan Notulen Konsultasi</label>                                   
                                            <select class="choices form-select multiple-remove" name="notulen[]"
                                                multiple="multiple">
                                                <option value="">Pilih Personel</option>
                                                @foreach ($user as $item)

                                                    @isset($consultation)                                                    
                                                        @php 
                                                            $var = explode(',', $consultation->notulen);
                                                        @endphp
                                                        <optgroup label="{{ $item->name }}">
                                                            @foreach ($item->user as $val)
                                                                @if($val->status == 1)
                                                                    <option value="{{ $val->id }}" @selected(in_array($val->id, $var))>
                                                                        {{ ucfirst($val->name) }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </optgroup>
                                                    @else
                                                        @php
                                                            $arr = old('notulen') ? old('notulen') : [];
                                                        @endphp
                                                        <optgroup label="{{ $item->name }}">
                                                            @foreach ($item->user as $val)
                                                                @if($val->status == 1)
                                                                    <option value="{{ $val->id }}"  @selected(in_array($val->id, $arr))>{{ ucfirst($val->name) }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </optgroup>
                                                    @endisset

                                                @endforeach
                                            </select>
                                            @error('notulen')
                                                <div class='small text-danger text-left'>{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <div class="col-md-8 mb-3">
                                            <label>Anggota Konsultasi</label>
                                            <select class="choices form-select multiple-remove" name="konsultan[]"
                                                multiple="multiple">
                                                <option value="">Pilih Personel</option>
                                                @foreach ($user as $item)
                                                    @isset($consultation)
                                                        
                                                        @php
                                                        $var = explode(',', $consultation->konsultan);
                                                        @endphp
                                                        <optgroup label="{{ $item->name }}">
                                                            @foreach ($item->user as $val)
                                                                <option value="{{ $val->id }}" @selected(in_array($val->id, $var))>
                                                                    {{ ucfirst($val->name) }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @else
                                                        @php
                                                        $arr = old('konsultan') ? old('konsultan') : [];
                                                        @endphp
                                                        <optgroup label="{{ $item->name }}">
                                                            @foreach ($item->user as $val)
                                                                <option value="{{ $val->id }}" @selected(in_array($val->id, $arr))>{{ ucfirst($val->name) }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endisset
                                                @endforeach
                                            </select>
                                            @error('konsultan')
                                                <div class='small text-danger text-left'>{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <h5 class="card-title">Penjadwalan Konsultasi</h5>

                                    <div class="form-group row mb-3">
                                        <div class="col-md-6">
                                            <label>Jenis</label>
                                            <select class="form-control" name="jenis" required>
                                                <option value="">Pilih Jenis</option>
                                                @php
                                                    $var = ['peninjuan_lokasi', 'rapat_pembahasan', 'online'];
                                                @endphp
                                                @foreach ($var as $item)
                                                    <option value="{{ $item }}" @selected(isset($schedule) && $schedule->jenis == $item)  @selected( old('jenis') == $item)>
                                                        {{ ucwords(str_replace('_', ' ', $item)) }}</option>
                                                @endforeach
                                            </select>
                                            @error('jenis')
                                                <div class='small text-danger text-left'>{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <div class="row">                                
                                            <div class="col-md-6">
                                                <label>Tanggal Surat</label>
                                                <input type="date" name="tanggal"
                                                    value="{{ isset($schedule) ? $schedule->tanggal : old('tanggal') }}"
                                                    class="form-control">
                                                @error('tanggal')
                                                    <div class='small text-danger text-left'>{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Tanggal Acara</label>
                                                <input type="date" name="date"
                                                    value="{{ isset($schedule) ? $time[2] : old('date') }}"
                                                    class="form-control" lang="id-ID">
                                                @error('tanggal')
                                                    <div class='small text-danger text-left'>{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label>Waktu Mulai</label>
                                                <input type="time" name="timeStart"
                                                    value="{{ isset($schedule) ? $time[0] : old('timeStart') }}"
                                                    class="form-control">
                                                @error('timeStart')
                                                    <div class='small text-danger text-left'>{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label>Waktu Akhir</label>
                                                <input type="time" name="timeEnd"
                                                    value="{{ isset($schedule) ? $time[1] : old('timeEnd') }}"
                                                    class="form-control">
                                                @error('timeEnd')
                                                    <div class='small text-danger text-left'>{{ $message }}</div>
                                                @enderror
                                            </div>                                    
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <div class="col-md-4">
                                            <label>Tempat</label>

                                            <select class="form-control" name="place" required>
                                                <option value="">Pilih Tempat</option>
                                                @php
                                                    $var = ['alamat_bangunan', 'ruang_rapat_DPUPR_Kabupaten_Tegal', 'melalui_Daring/Teleconference'];
                                                @endphp
                                                @foreach ($var as $item)
                                                    <option value="{{ $item }}" @selected(isset($schedule) && $place[0] == $item) @selected( old('place') == $item)>
                                                        {{ ucwords(str_replace('_', ' ', $item)) }}</option>
                                                @endforeach
                                            </select>
                                            @error('place')
                                                <div class='small text-danger text-left'>{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-8">
                                            <label>Alamat</label>
                                            <input type="text" name="place_des"
                                                value="{{ isset($schedule) ? $place[1] : old('place_des') }}"
                                                class="form-control">
                                            @error('place_des')
                                                <div class='small text-danger text-left'>{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <div class="col-md-12">
                                            <label>Keterangan</label>
                                            <textarea class="form-control summernote" name="content" rows="2">  {!! isset($schedule) ? $schedule->keterangan : old('content') !!}</textarea>                                           
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
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-primary rounded-pill">Save</button>
                                            <a class="btn btn-danger ms-1 rounded-pill"
                                                href="{{ route('consultation.index') }}">Back</a>
                                        </div>
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
        <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
        <script src="{{ asset('assets/static/js/pages/form-element-select.js') }}"></script>
        <script src="{{ asset('assets/extensions/summernote/summernote-lite.min.js') }}"></script>
       <script src="{{ asset('assets/editor.js') }}"></script>
    @endpush
