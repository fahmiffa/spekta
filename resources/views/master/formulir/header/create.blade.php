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

                @isset($header)
                <form action="{{route('header.update',['header'=>$header])}}" method="post">                            
                @method('PATCH')   
                @else                                      
                    <form action="{{route('header.store')}}" method="post">                               
                @endif                    
                    @csrf           
                    <div class="px-5">            
                        
                        <div class="form-group row mb-3">
                            <div class="col-md-12">
                                <label>Dokumen</label>              
                                <select class="choices form-select" name="document">
                                    @foreach($doc as $item)
                                    <option value="{{$item->id}}"  @selected(isset($header) && $header->doc == $item->id)>{{$item->name}}</option>
                                    @endforeach                       
                                </select>
                                @error('document')<div class='small text-danger text-left'>{{$message}}</div>@enderror
                            </div>
                        </div>

                        <div class="form-floating row mb-3">
                            <div class="col-md-12">
                                <p>Item Header</p>                               
                                <div id="input-item" class="mt-3">
                                    @error('item')<div class='small text-danger text-left'>{{$message}}</div>@enderror     
                                    @if(old('item'))
                                        @php $item = old('item') @endphp
                                        @for ($i = 0; $i < count($item); $i++)
                                            <div class="input-group mb-3">
                                                <input type="text" name="item[]" value="{{$item[$i]}}" placeholder="item" class="form-control">
                                                <button class="btn btn-danger remove-input" type="button"><i class="bi bi-trash"></i></button>
                                            </div>
                                        @endfor
                                    @else                                
                                        @isset($header)     
                                            @php $item = json_decode($header->item) @endphp  
                                            @for ($i = 0; $i < count($item); $i++)
                                            <div class="input-group mb-3">
                                                <input type="text" name="item[]" value="{{$item[$i]}}" placeholder="item" class="form-control">
                                                <button class="btn btn-danger remove-input" type="button"><i class="bi bi-trash"></i></button>
                                            </div>
                                            @endfor
                                        @endisset
                                    @endif
                                </div>
                                <button class="btn btn-success btn-sm rounded-pill" type="button" id="add-item">Tambah item</button> 
                            </div>
                        </div>
                                                
                        <div class="form-group row mb-3">             
                            <div class="col-md-12" >
                                <button class="btn btn-primary rounded-pill">Save</button>
                                <a class="btn btn-danger ms-1 rounded-pill" href="{{route('header.index')}}">Back</a>
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


<script>

    $("#add-item").on('click',function(){        
        var newInput = $('<div class="input-group mb-3">\
          <input type="text" name="item[]" placeholder="item" class="form-control">\
          <button class="btn btn-danger remove-input" type="button"><i class="bi bi-trash"></i></button>\
        </div>');
        $('#input-item').append(newInput);
    });
    
    
    $(document).on('click', '.remove-input', function() {
        $(this).parent('.input-group').remove();
    });
    
    </script>
@endpush