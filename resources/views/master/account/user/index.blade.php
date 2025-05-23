@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
@endpush
@section('main')
    <div class="page-heading">

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between py-3">
                        <div class="p-2">
                            <h5 class="card-title">Data {{ $data }}</h5>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">Create
                                {{ $data }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="table1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($da as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->roles->name }}</td>
                                        <td class="text-center">
                                            @if ($item->status == 1)
                                                <button type="button" class="btn btn-success btn-sm rounded-pill"
                                                    data-bs-placement="top" data-bs-original-title="Aktif"
                                                    data-bs-toggle="modal" data-bs-target="#re{{ $item->id }}">
                                                    Aktif
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-danger btn-sm rounded-pill"
                                                    data-bs-placement="top" data-bs-original-title="Tidak Aktif"
                                                    data-bs-toggle="modal" data-bs-target="#re{{ $item->id }}">
                                                    Tidak Aktif
                                                </button>
                                            @endif
                                        </td>

                                        <td>{{ $item->note }}</td>
                                        <td>
                                            <form onsubmit="return confirm('Apakah Anda Yakin Menghapus ?');"
                                                action="{{ route('user.destroy', $item->id) }}" method="POST">
                                                <a href="{{ route('user.edit', $item->id) }}"
                                                    class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @foreach ($da as $item)
                <div class="modal fade" id="re{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">
                                    UBAH STATUS AKUN
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('user.drop', ['id' => md5($item->id)]) }}" method="post">
                                    @csrf
                                    <p class="mb-3">Anda akan Mengubah Status akun {{ $item->name }} ?
                                    <p>
                                        <label>Catatan : </label>
                                        <textarea class="form-control" name="noted" required></textarea>
                                        <button class="btn btn-success rounded-pill mt-3">Submit</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </section>
        <!-- Basic Tables end -->

    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/datatables.js') }}"></script>
@endpush
