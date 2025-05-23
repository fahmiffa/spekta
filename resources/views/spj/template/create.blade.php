@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/select/tom-select.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/summernote/summernote-lite.css') }}">
    <style>
    </style>
@endpush
@section('main')
    <div class="page-heading">

        <section class="section">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title">Template Dokumen SPJ</h5>
                </div>

                <div class="card-body">
                            <form action="{{ route('template.store') }}" method="post">
                                @csrf
                                @isset($template)
                                <input type="hidden" value="{{md5($template->id)}}" name="con">
                                @endif
                                <div class="form-group row mb-3">
                                    <div class="col-12 mb-3">
                                        <label>Pilih Tipe</label>
                                        <select class="form-control"  name="tipe" id="tipe"  placeholder="Pilih tipe" required>
                                            @php
                                            $val = spjDoc();
                                            $sel = false;
                                            if(isset($template))
                                            {
                                                $sel = true;
                                            }
                                            @endphp
                                            @foreach($val as $row)
                                                <option value="{{$row}}" @selected($sel && $template->doc == $row) </option>{{ucwords(str_replace('pbg','PBG',str_replace('_',' ',$row)))}}</option>
                                       
                                            @endforeach
                                        </select>
                                        @error('tipe')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>                              

                                
                                <div class="form-group row mb-3">
                                    <div class="col-md-12">
                                        <label>Field</label>
                                        <textarea class="form-control summernote" name="field" rows="2">  {!! isset($template) ? $template->field : old('field') !!}</textarea>                                           
                                        @error('field')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-primary rounded-pill">Save</button>
                                        <a class="btn btn-danger ms-1 rounded-pill"
                                            href="{{ route('spj.template') }}">Back</a>
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
        <script src="{{ asset('assets/extensions/summernote/summernote-lite.min.js') }}"></script>
        <script src="{{ asset('assets/editor.js') }}"></script>
        <script>

            function getVal()
            {
                var sel = $('#sel').val();
                var tipe = $('#tipe').val();

                $.ajax({
                type: 'POST',
                data : {
                    da : tipe,
                    sel : sel
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('spj.data') }}",
                success: function(data) {

                 
                }
            });
            }

        </script>
    @endpush
