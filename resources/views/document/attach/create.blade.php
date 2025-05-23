@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
    <style>
        .image-preview {
            display: flex;
            flex-wrap: wrap;
        }

        .image-preview img {
            width: 100px;
            margin: 10px;
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
                    <form action="{{ route('attach.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="px-5">
                            <input type="hidden" name="doc" value="{{ md5($head->id) }}">
                            @php
                                $header = json_decode($head->header);
                                $koordinat = isset($header[8]) ? $header[8] : old('koordinat');
                                $land = isset($header[9]) ? $header[9] : old('bukti');
                            @endphp

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Luas Tanah</label>
                                        <input type="text" name="luas"
                                            value="{{ isset($attach) ? $attach->luas : old('luas') }}" class="form-control">
                                        @error('luas')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Luas Persil</label>
                                        <input type="text" name="persil"
                                            value="{{ isset($attach) ? $attach->persil : old('persil') }}" class="form-control">
                                        @error('persil')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Bukti Kepemilikan Tanah</label>
                                        <input type="text" name="bukti"
                                            value="{{ isset($attach) ? $attach->bukti : $land }}"
                                            class="form-control">
                                        @error('bukti')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <label>Gambar Denah Lokasi</label>
                                    <div class="form-group mb-3">

                                        @isset($attach->pile_map)
                                            <div class="d-flex justify-content-center mb-3">
                                                @php
                                                    $var = json_decode($head->attach->pile_map);
                                                @endphp
                                                @foreach ($var as $key)
                                                    <img src="{{ asset('storage/' . $key) }}" class="w-25 ms-1">
                                                @endforeach
                                            </div>
                                        @endisset
                                        <small class="text-danger fw-bold">Format ekstensi upload JPG, JPEG, PNG</small>
                                        <input class="form-control" type="file" id="imageInput"
                                            accept=".jpg, .jpeg, .png" name="pile_map[]" multiple>

                                        @error('pile_map[]')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label>Lokasi Bangunan</label>
                                    <div class="form-group mb-3">
                                        @isset($attach->pile_loc)
                                            <div class="d-flex justify-content-center mb-3">
                                                <img src="{{ asset('storage/' . $attach->pile_loc) }}" class="w-50">
                                            </div>
                                        @endisset
                                        <small class="text-danger fw-bold">Format ekstensi upload JPG, JPEG, PNG</small>
                                        <input class="form-control" type="file" name="pile_loc"
                                            accept=".jpg, .jpeg, .png">

                                        @error('pile_loc')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label>Kondisi Lahan / Bangunan</label>
                                    <div class="form-group mb-3">
                                        @isset($attach->pile_land)
                                            <div class="d-flex justify-content-center mb-3">
                                                <img src="{{ asset('storage/' . $attach->pile_land) }}" class="w-50">
                                            </div>
                                        @endisset
                                        <small class="text-danger fw-bold">Format ekstensi upload JPG, JPEG, PNG</small>
                                        <input class="form-control" type="file" name="pile_land"
                                            accept=".jpg, .jpeg, .png">

                                        @error('pile_land')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Koordinat</label>
                                        <input type="text" name="koordinat"
                                            value="{{ isset($attach) ? $attach->koordinat : $koordinat }}"
                                            class="form-control">
                                        @error('koordinat')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button class="btn btn-primary rounded-pill">Save</button>
                                <a class="btn btn-danger ms-1 rounded-pill" href="{{ route('attach.index') }}">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </section>

    </div>
@endsection
