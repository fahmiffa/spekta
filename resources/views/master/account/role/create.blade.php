@extends('layout.base')     
@push('css')
<link rel="stylesheet" href="{{asset('assets/extensions/choices.js/public/assets/styles/choices.css')}}">
<link rel="stylesheet" href="{{asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/compiled/css/table-datatable-jquery.css')}}">

@endpush
@section('main')
<div class="page-heading">  

    <section class="section">
        <div class="card">       

            <div class="card-header">
                <h5 class="card-title">{{$data}}</h5>                    
            </div>

            <div class="card-body">
                @isset($role)
                @php 
                $val = explode(', ', $role->permission);  
                @endphp
                <form action="{{route('role.update',['role'=>$role])}}" method="post" enctype="multipart/form-data">                            
                @method('PATCH')   
                @else                                     
                    <form action="{{route('role.store')}}" method="post" enctype="multipart/form-data">                               
                @endif                    
                    @csrf           
                    <div class="px-5">
                        <div class="form-group row mb-3">
                            <label class="col-md-3">Name</label>
                            <div class="col-md-6">
                                <input type="text" name="name" value="{{isset($role) ? $role->name : old('name')}}"   class="form-control">
                                @error('name')<div class='small text-danger text-left'>{{$message}}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3">Kode</label>
                            <div class="col-md-6">
                                <input type="text" name="kode" value="{{isset($role) ? $role->kode : old('kode')}}"   class="form-control">
                                @error('kode')<div class='small text-danger text-left'>{{$message}}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3">Permission</label>
                            <div class="col-md-6">
                                @foreach($per as $item)
                                <div class="form-group mb-3">                            
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="permit[{{md5($item->id)}}]" {{ isset($role) && in_array($item->id,$val) ? 'checked' : null}}>
                                        <label class="form-check-label" for="flexSwitchCheckChecked">{{$item->name}}</label>
                                    </div>
                                </div>
                                @endforeach   
                            </div>
                        </div>
                        
                        <div class="form-group row mb-3">
                            <label class="col-md-3"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary rounded-pill">Save</button>
                                <a class="btn btn-danger ms-1 rounded-pill" href="{{route('role.index')}}">Back</a>
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
<script src="{{asset('assets/extensions/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/static/js/pages/datatables.js')}}"></script>

<script src="{{asset('assets/extensions/choices.js/public/assets/scripts/choices.js')}}"></script>
<script src="{{asset('assets/static/js/pages/form-element-select.js')}}"></script>

@endpush