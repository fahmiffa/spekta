@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/select/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/select/select2-bootstrap-5-theme.rtl.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/select/select2-bootstrap-5-theme.min.css') }}" />
@endpush
@section('main')
    <div class="page-heading">

        <section class="section">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title">{{ $data }}</h5>
                </div>

                <div class="card-body">

                    @isset($verifikasi)
                        <form action="{{ route('verifikasi.update', ['verifikasi' => $verifikasi]) }}" method="post">
                            @method('PATCH')
                        @else
                            <form action="{{ route('verifikasi.store') }}" method="post">
                                @endif
                                @csrf
                                <div class="px-5">
                                    <div class="form-group row mb-3">
                                        <div class="col-md-6">
                                            <label>No Dokumen</label>
                                            <p class="form-control-static mt-1">{{ nomor() }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Jenis</label>
                                            <select class="form-select" name="type" id="type">
                                                <option value="">Pilih Jenis</option>
                                                @php $doc = baseDoc();  @endphp
                                                @foreach ($doc as $item)
                                                    @if (old('type'))
                                                        <option value="{{ $item }}" @selected(old('type') == $item)>
                                                            {{ ucfirst($item) }}</option>
                                                    @else
                                                        <option value="{{ $item }}" @selected(isset($verifikasi) && $verifikasi->type == $item)>
                                                            {{ ucfirst($item) }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class='small text-danger text-left'>{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    @include('document.header')

                                    <div class="form-group row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Tahap</label>
                                                <select class="form-control" name="task" placeholder="tahap" id="task">
                                                    <option value="">Pilih Tahap</option>
                                                    <option value="1" @selected(isset($verifikasi) && $verifikasi->step == 1)>1 (Satu)</option>
                                                    <option value="2" @selected(isset($verifikasi) && $verifikasi->step == 2)>2 (Dua)</option>
                                                </select>
                                                @error('task')
                                                    <div class='small text-danger text-left'>{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <div class="col-md-6 mb-3 {{isset($verifikasi) && $verifikasi->step == 1 ? null : 'd-none' }} step">
                                            <label>Verifikator Tahap 1</label>
                                            <select class="select-field form-select" name="verifikator[]" id="task1">
                                                <option value="">Pilih Verifikator</option>
                                                @if(isset($verifikasi) && $verifikasi->step == 1)                                     
                                                @foreach($user->where('role',$role['VL1']) as $ver)
                                                 <option value="{{$ver->id}}" @selected($ver->id == $verifikasi->verifikator) >{{$ver->name}}</option>
                                                 @endforeach
                                                @endif
                                            </select>
                                            @error('verifikator')
                                                <div class='small text-danger text-left'>{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3 {{isset($verifikasi) && $verifikasi->step == 2 ? null : 'd-none' }} steps">
                                            <label>Verifikator Tahap 1</label>
                                            <select class="select-field form-select" name="verifikator[]" id="task2">
                                                <option value="">Pilih Verifikator</option>
                                                @if(isset($verifikasi) && $verifikasi->step == 2)
                                                @php
                                                $verif = explode(',',$verifikasi->verifikator);
                                                @endphp
                                                @foreach($user->where('role',$role['VL2']) as $ver)
                                                 <option value="{{$ver->id}}" @selected($ver->id == $verif[0]) >{{$ver->name}}</option>
                                                 @endforeach
                                                @endif
                                            </select>
                                            @error('verifikator')
                                                <div class='small text-danger text-left'>{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3 {{isset($verifikasi) && $verifikasi->step == 2 ? null : 'd-none' }} steps">
                                            <label>Verifikator Tahap 2</label>
                                            <select class="select-field form-select" name="verifikator[]" id="task3">
                                                <option value="">Pilih Verifikator</option>
                                                @if(isset($verifikasi) && $verifikasi->step == 2)                                         
                                                @foreach($user->where('role',$role['VL3']) as $ver)
                                                 <option value="{{$ver->id}}" @selected($ver->id == $verif[1]) >{{$ver->name}}</option>
                                                 @endforeach
                                                @endif
                                            </select>
                                            @error('verifikator')
                                                <div class='small text-danger text-left'>{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-primary rounded-pill">Save</button>
                                            <a class="btn btn-danger ms-1 rounded-pill"
                                                href="{{ route('verifikasi.index') }}">Back</a>
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
        <script src="{{ asset('assets/select/select2.min.js') }}"></script>

        <script>

            @if (old('type') == 'menara')
                $('#con').html('Koordinat');
                $('#koor').removeClass('d-none');
                $('#fung').addClass('d-none');
            @endif

            @if (old('type') == 'umum')
                $('#con').html('Fungsi');
                $('#koor').addClass('d-none');
                $('#fung').removeClass('d-none');
            @endif

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
                    url: "{{ route('village') }}",
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

            $('#task').on('change', function(e) {
                var par = $(this).val();
                e.preventDefault();
                $('#task1').empty();
                $('#task2').empty();
                $('#task3').empty();
                if (par == 1) {
                    $('.step').removeClass('d-none');
                    $('.steps').addClass('d-none');
                    $.ajax({
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('task') }}",
                        data: {
                            id: $(this).val()
                        },
                        success: function(data) {
                            $.each(data.satu, function(key, value) {
                                $('#task1').append('<option value="' + key + '">' + value
                                    .toUpperCase() +
                                    '</option>');
                            });
                        }
                    });

                } else {

                    $('.steps').removeClass('d-none');
                    $('.step').addClass('d-none');
                    $.ajax({
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('task') }}",
                        data: {
                            id: $(this).val()
                        },
                        success: function(data) {

                            $.each(data.satu, function(key, value) {
                                $('#task2').append('<option value="' + key + '">' + value
                                    .toUpperCase() +
                                    '</option>');
                            });
                            $.each(data.dua, function(key, value) {
                                $('#task3').append('<option value="' + key + '">' + value
                                    .toUpperCase() +
                                    '</option>');
                            });
                        }
                    });

                }
            });


        </script>
    @endpush
