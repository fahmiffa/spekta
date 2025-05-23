@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/compiled/css/table-datatable-jquery.css')}}">
@endpush
@section('main')
    <div class="page-heading">

        <section class="section">
            <div class="card">

                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title">Template Dokumen SPJ</h5>
                    <a href="{{route('template.add')}}" class="btn btn-primary btn-sm">Tambah</a>
                </div>

                <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Dokumen</th>
                                <th>Field</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($template as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center"> {{ucwords(str_replace('pbg','PBG',str_replace('_',' ',$item->doc)))}}</td>
                                    <td class="text-justify">
                                        {!! $item->field !!}
                                    </td>
                                    <td>                        
                                        <div class="d-flex justify-content-center align-items-center">
                                            <a href="{{ route('template.edit', ['id'=>md5($item->id)]) }}" class="btn btn-sm btn-danger"><i class="bi bi-pencil"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>

            </section>

        </div>
    @endsection

    @push('js')
    <script src="{{asset('assets/extensions/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{asset('assets/static/js/pages/datatables.js')}}"></script>
    @endpush
