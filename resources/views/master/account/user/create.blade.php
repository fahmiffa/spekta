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

                @isset($user)
                <form action="{{route('user.update',['user'=>$user])}}" method="post" enctype="multipart/form-data">                            
                @method('PATCH')   
                @else                                      
                    <form action="{{route('user.store')}}" method="post" enctype="multipart/form-data">                               
                @endif                    
                    @csrf           
                    <div class="px-5">
                        <div class="form-group row mb-3">
                            <label class="col-md-3">Name</label>
                            <div class="col-md-6">
                                <input type="text" name="name" value="{{isset($user) ? $user->name : old('name')}}"   class="form-control">
                                @error('name')<div class='small text-danger text-left'>{{$message}}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3">Role</label>              
                            <div class="col-md-6">
                                <select class="choices form-select" name="role" id="role">
                                    @foreach($role as $item)
                                    <option value="{{$item->id}}"  @selected(isset($user) && $user->role == $item->id)>{{$item->name}}</option>
                                    @endforeach                       
                                </select>
                                @error('role')<div class='small text-danger text-left'>{{$message}}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3">Email</label>
                            <div class="col-md-6">
                                <input type="email" name="email" value="{{isset($user) ? $user->email : old('email')}}"   class="form-control">
                                @error('email')<div class='small text-danger text-left'>{{$message}}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3 {{ isset($user) && $user->role == 5 ? null : 'd-none' }}" id="nip">
                            <label class="col-md-3">NIP</label>
                            <div class="col-md-6">
                                <input type="text" name="nip" value="{{isset($user) ? $user->nip : old('nip')}}"   class="form-control">
                                @error('nip')<div class='small text-danger text-left'>{{$message}}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3">Password</label>
                            <div class="col-md-6">
                                <div class="form-group position-relative has-icon-right">
                                        <input type="password" name="password" value="{{old('password')}}"   class="form-control">
                                        @error('password')<div class='small text-danger text-left'>{{$message}}</div>@enderror
                                        <div class="form-control-icon" onclick="show(this)">
                                            <i class="bi bi-eye"></i>
                                        </div>
                                </div>
                            </div>
                        </div>
                                                
                        <div class="form-group row mb-3">
                            <label class="col-md-3"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary rounded-pill">Save</button>
                                <a class="btn btn-danger ms-1 rounded-pill" href="{{route('user.index')}}">Back</a>
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

    function show(e) {
        const parentDiv = e.closest('.form-group.position-relative.has-icon-right');
        const input = parentDiv.querySelector('input[type="password"], input[type="text"]');
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        const con = input.getAttribute('type') === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
        e.innerHTML = con; 
    }

    $('#role').on('change',function(){        
        if($(this).val() === '5')
        {
            $('#nip').removeClass('d-none');
        }
        else
        {
            $('#nip').addClass('d-none');
        }
    });
</script>

@endpush