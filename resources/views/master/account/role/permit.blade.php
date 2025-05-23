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
                <h5 class="card-title text-capitalize">{{$data}}</h5>                    
            </div>

            <div class="card-body">
                                                        
                <form action="{{route('permit',['id'=>md5($role->id)])}}" method="post">                                                      
                    @csrf           
                    <div class="px-3">
                        @foreach($per as $item)
                        <div class="form-group mb-3">                            
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="permit[{{md5($item->id)}}]"  {{ $item->permit && $item->permit->role_id == $role->id ? 'checked' : null}}>
                                <label class="form-check-label" for="flexSwitchCheckChecked">{{$item->name}}</label>
                            </div>
                        </div>
                        @endforeach                                                
                        <div class="form-group mb-3">                        
                            <button class="btn btn-primary btn-sm rounded-pill">Save</button>
                            <a class="btn btn-secondary ms-1 btn-sm rounded-pill" href="{{route('role.index')}}">Back</a>
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