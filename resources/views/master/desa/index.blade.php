@extends('layout.base')     
@push('css')
<link rel="stylesheet" href="{{asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/compiled/css/table-datatable-jquery.css')}}">
@endpush
@section('main')
<div class="page-heading">

    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between py-3">
                    <div class="p-2">
                        <h5 class="card-title text-capitalize">Data {{$data}}</h5>
                    </div>
                    <div class="p-2 d-flex justify-content-around">
                        <a href="{{route('kecamatan.create')}}" class="btn btn-primary btn-sm">Tambah {{$data}}</a>
                        <button type="button" class="btn btn-danger btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#myModals">Import</button>
                        <form onsubmit="return myConfirm('reset');" action="{{ route('reset') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">Reset</button>
                        </form>
                    </div>
                </div>      
                
                <div class="modal fade" id="myModals">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Upload Data</h4>                               
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                            <a href="{{ asset('assets/import.xlsx') }}" class="text-primary my-1">Sample</a>
                                <form action="{{ route('import') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <small class="text-danger" style="font-size:0.8rem">Format ekstensi xlsx</small>
                                    <input class="form-control" type="file" name="file"
                                    accept=".xlsx" required>

                                    <button class="btn btn-primary btn-sm my-3">Import</button>
                                </form>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger"
                                    data-bs-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table1">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>      
                                <th>Kecamatan</th>                                      
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($da as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->name}}</td>   
                                <td>{{ucfirst($item->kecamatan->name)}}</td>                                      
                                <td>                
                                    <form onsubmit="return confirm('Apakah Anda Yakin Menghapus ?');" action="{{ route('desa.destroy', $item->id) }}" method="POST">
                                        <a href="{{ route('desa.edit', $item->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>                                       
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>                    
                            </tr>            
                            @endforeach      
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->

</div>
@endsection

@push('js')    
<script src="{{asset('assets/extensions/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/static/js/pages/datatables.js')}}"></script>
@endpush