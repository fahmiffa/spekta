@extends('layout.base')
@push('css')
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
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h6>{{ $data }}</h6>
                </div>
            </div>

            <section class="section">
                <div class="card">

                    <div class="card-header">
                        <h6 class="card-title text-center">{{ $doc->titles }}</h6>
                        <p class="text-center">{{ $head->nomor }}</p>
                        @include('document.pemohon')
                    </div>

                    <div class="card-body px-3">

                        @if ($head->step == 1)
                            <form action="{{ route('next.verifikasi', ['id' => md5($head->id)]) }}" method="post"
                                id="pub">
                                @csrf
                                @include('document.verifikasi.step')

                                <div class="d-flex justify-content-between" id="button">
                                    <button class="btn btn-primary rounded-pill">Draft</button>
                                    <button type="button" onclick="pub()" class="btn btn-success rounded-pill">Save &
                                        Publish</button>
                                </div>
                            </form>
                        @else

                            @php
                                $no = 1;
                                $level = auth()->user()->roles->kode;
                            @endphp
                            <form action="{{ route('nexts.verifikasi', ['id' => md5($head->id)]) }}" method="post" id="pub">
                                @csrf
                                @include('document.verifikasi.steps')
                                <div class="d-flex justify-content-between" id="button">
                                    <button class="btn btn-primary rounded-pill">Draft</button>
                                    @if($head->steps->where('kode', 'VL3')->first() && $level == 'VL2')
                                    <button type="button" onclick="pub()" class="btn btn-success rounded-pill">Save &
                                        Publish</button>
                                    @endif
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

            </section>

        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/extensions/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('assets/editor.js') }}"></script>
    <script>
   
        function remove(e) {
            e.parentNode.remove();
        }

        $('#add-item').on('click', function() {
            var n = $(':radio').length / 3 + 1;
            var clonedDiv = '<div class="row mb-3">\
                                                     <div class="col-md-3">\
                                                        <input type="text" class="form-control" name="nameOther[' + n + ']" placeholder="Lain-lain"></div>\
                                                     <div class="col-md-5">\
                                                        <div class="d-flex justify-content-center">\
                                                            <div class="form-check d-inline-block">\
                                                                <input class="form-check-input" type="radio" name="item[' +
                n + ']" value="1">\
                                                                <label class="form-check-label">Ada</label>\
                                                            </div>\
                                                            <div class="form-check d-inline-block mx-3">             \
                                                                <input class="form-check-input" type="radio" name="item[' +
                n + ']" value="0" checked>\
                                                                <label class="form-check-label">Tidak Ada</label>\
                                                            </div>\
                                                            <div class="form-check d-inline-block">\
                                                                <input class="form-check-input" type="radio" name="item[' +
                n + ']" value="2">\
                                                                <label class="form-check-label">Tidak Perlu</label>\
                                                            </div>\
                                                        </div>\
                                                     </div>\
                                                     <div class="col-md-3">\
                                                        <textarea class="form-control" name="saranOther[' + n + ']" rows="2"></textarea>\
                                                     </div>\
                                                     <button class="btn btn-danger btn-sm my-auto" style="width:fit-content;height:fit-content" onclick="remove(this)"  type="button"><i class="bi bi-trash"></i></button>\
                                                     </div>\
                                                    ';
            $('#input').append(clonedDiv);
        });

        function pub() {
            let uri = $('#pub').attr('action');
            @if($head->step ==  2)
                uri = uri.replace('/next-', '/pubs-');
            @else
                uri = uri.replace('/next-', '/pub-');
            @endif
            $('#pub').attr('action', uri)
            $('#pub').submit();
        }
    </script>
@endpush
