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

                @isset($title)
                <form action="{{route('title.update',['title'=>$title])}}" method="post">                            
                @method('PATCH')   
                @else                                      
                    <form action="{{route('title.store')}}" method="post">                               
                @endif                    
                    @csrf           
                    <div class="px-5">
                        <div class="form-floating row mb-3">
                            <div class="col-md-12">
                                <label>Name</label>
                                <input type="text" name="name" value="{{isset($title) ? $title->name : old('name')}}"   class="form-control">
                                @error('name')<div class='small text-danger text-left'>{{$message}}</div>@enderror
                            </div>
                        </div> 
                        
                        <div class="form-group row mb-3">
                            <div class="col-md-12">
                                <label>Dokumen</label>              
                                <select class="choices form-select" name="document">
                                    @foreach($doc as $item)
                                    <option value="{{$item->id}}"  @selected(isset($title) && $title->doc == $item->id)>{{$item->name}}</option>
                                    @endforeach                       
                                </select>
                                @error('document')<div class='small text-danger text-left'>{{$message}}</div>@enderror
                            </div>
                        </div>
                                                
                        <div class="form-group row mb-3">             
                            <div class="col-md-12" >
                                <button class="btn btn-primary rounded-pill">Save</button>
                                <a class="btn btn-danger ms-1 rounded-pill" href="{{route('title.index')}}">Back</a>
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