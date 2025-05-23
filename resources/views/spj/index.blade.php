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
                    <h5 class="card-title">Dokumen SPJ</h5>
                    <a href="{{route('spj.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                </div>

                <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tipe</th>
                                <th>Tanggal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doc as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center"> {{ucwords(str_replace('pbg','PBG',str_replace('_',' ',$item->type)))}}</td>
                                    <td class="text-center">
                                        {{ date('d-m-Y',strtotime($item->time)) }}
                                    </td>
                                    <td>                        
                                        <div class="d-flex justify-content-center align-items-center">
                                            <a href="{{ route('spj.edit', ['id'=>md5($item->id)]) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                            &nbsp;
                                            <a target="_blank" href="{{ route('spj.preview', ['id'=>md5($item->id)]) }}" class="btn btn-sm btn-success"><i class="bi bi-file-pdf"></i></a>
                                            &nbsp;
                                            <form onsubmit="return myConfirm('rollback ?');" action="{{route('spj.del',['id'=>md5($item->id)])}}" method="post">
                                              @csrf
                                              <button class="btn btn-sm btn-danger" ><i class="bi bi-trash"></i></button>
                                            </form>
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
