@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
@endpush
@section('main')
    <div class="page-heading">

        <section class="section">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title">{{ $data }}</h5>
                </div>

                <div class="card-body">

                    <form action="{{ route('shsts') }}" method="post">
                        @csrf
                        <div class="px-5">
                            <div class="form-floating row mb-3">
                                <div class="col-md-12">
                                    <label>Nilai SHST</label>
                                    <input type="number" name="value" value="{{ isset($val) ? $val->shst : null }}"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="form-floating row mb-3">
                                <div class="col-md-12">
                                    <label>Timer</label>
                                    <input type="number" min="1" name="timer" value="{{ isset($val) ? $val->timer : null }}"
                                        class="form-control" required>
                                    <small class="text-danger">Dalam menit</small>
                                </div>
                            </div>


                            <div class="form-group row mb-3">
                                <div class="col-md-12">
                                    <button class="btn btn-primary rounded-pill">Save</button>
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
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/datatables.js') }}"></script>

    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/form-element-select.js') }}"></script>
@endpush
