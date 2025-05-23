@extends('layout.base')     
@push('css')
<link rel="stylesheet" href="{{asset('assets/extensions/choices.js/public/assets/styles/choices.css')}}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
@endpush
@section('main')
<div class="page-heading">  

    <section class="section">
        <div class="card">       

            <div class="card-header">
                <div class="divider">
                    <div class="divider-text">{{$data}}</div>
                </div>                
            </div>

            <div class="card-body">
    
                <form action="{{route('next.news',['id'=>md5($news->id)])}}" method="post">                                                        
                    @csrf           
                    <div class="px-5">       
                        @if($news->status == 5)
                            <div class="row">
                                <h6>Informasi Umum :</h6>
                                @error('width')<div class='small text-danger text-left'>{{$message}}</div>@enderror
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>Lebar Jalan</label>
                                        <input type="hidden" name="val[]" value="Lebar Jalan" class="form-control">  
                                        <textarea class="form-control" name="width[]" rows="2" placeholder="Dimensi"></textarea>                                                                                                                  
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>Lebar saluran</label>
                                        <input type="hidden" name="val[]" value="Lebar Saluran" class="form-control">  
                                        <textarea class="form-control" name="width[]" rows="2" placeholder="Dimensi"></textarea>                                                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>Lebar Saluran/Irigasi</label>
                                        <input type="hidden" name="val[]" value="Lebar Saluran/Irigasi" class="form-control">  
                                        <textarea class="form-control" name="width[]" rows="2" placeholder="Dimensi"></textarea>                                                                       
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>Lebar Lahan</label>
                                        <input type="hidden" name="val[]" value="Lebar Lahan" class="form-control">  
                                        <textarea class="form-control" name="width[]" rows="2" placeholder="Dimensi"></textarea>                                                                           
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>Jumlah Basement</label>
                                        <input type="hidden" name="val[]" value="Jumlah Basement" class="form-control"> 
                                        <textarea class="form-control" name="width[]" rows="2" placeholder="Dimensi"></textarea>                                                      
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>Jumlah Lantai</label>
                                        <input type="hidden" name="val[]" value="Jumlah Lantai" class="form-control"> 
                                        <textarea class="form-control" name="width[]" rows="2" placeholder="Dimensi"></textarea>                                                                     
                                    </div>
                                </div> 
                            </div>                                                    
                        @endif        

                        @if($news->status == 4)
                            <div class="row">
                                <h6>Informasi Bangunan Gedung :</h6>
                                <div class="col-md-4 my-auto">
                                    <div class="form-group mb-3">                             
                                        <label>Garis Sempadan Bangunan</label>      
                                        <input type="hidden" name="gsn[]" value="garis_sempadan_bangunan" class="form-control">                            
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                                                   
                                        <input type="text" name="gsn[]" class="form-control" placeholder="Dimensi">                                                                    
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                              
                                        <textarea class="form-control" name="gsn[]" rows="1" placeholder="Catatan"></textarea>                                                                       
                                    </div>
                                </div>
                                
                                <div class="col-md-4 my-auto">
                                    <div class="form-group mb-3">                         
                                        <label>Garis Sempadan Sungai</label>     
                                        <input type="hidden" name="gsi[]" value="garis_sempadan_sungai" class="form-control">                                
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                                                   
                                        <input type="text" name="gsi[]" class="form-control" placeholder="Dimensi">                                                                           
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                        
                                        <textarea class="form-control" name="gsi[]" rows="1" placeholder="Catatan"></textarea>                                                              
                                    </div>
                                </div>

                                <div class="col-md-4 my-auto">
                                    <div class="form-group mb-3">                         
                                        <label>Garis Sempadan Saluran</label>     
                                        <input type="hidden" name="gsl[]" value="garis_sempadan_saluran" class="form-control">                                
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                                                   
                                        <input type="text" name="gsl[]" class="form-control" placeholder="Dimensi">                                                                           
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                        
                                        <textarea class="form-control" name="gsl[]" rows="1" placeholder="Catatan"></textarea>                                                              
                                    </div>
                                </div>

                                <div class="col-md-4 my-auto">
                                    <div class="form-group mb-3">                         
                                        <label>Garis Sempadan Danau/Pantai</label>     
                                        <input type="hidden" name="gsu[]" value="garis_sempadan_danau/pantai" class="form-control">                                
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                                                   
                                        <input type="text" name="gsu[]" class="form-control" placeholder="Dimensi">                                                                           
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                        
                                        <textarea class="form-control" name="gsu[]" rows="1" placeholder="Catatan"></textarea>                                                              
                                    </div>
                                </div>

                                <div class="col-md-4 my-auto">
                                    <div class="form-group mb-3">                         
                                        <label>Garis Sempadan Pagar</label>     
                                        <input type="hidden" name="gsr[]" value="garis_sempadan_pagar" class="form-control">                                
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                                                   
                                        <input type="text" name="gsr[]" class="form-control" placeholder="Dimensi">                                                                           
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                        
                                        <textarea class="form-control" name="gsr[]" rows="1" placeholder="Catatan"></textarea>                                                              
                                    </div>
                                </div>

                                <div class="col-md-4 my-auto">
                                    <div class="form-group mb-3">                         
                                        <label>Garis Sempadan Rel Kereta Api</label>     
                                        <input type="hidden" name="gsra[]" value="garis_sempadan_rel_kereta_api" class="form-control">                                
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                                                   
                                        <input type="text" name="gsra[]" class="form-control" placeholder="Dimensi">                                                                           
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                        
                                        <textarea class="form-control" name="gsra[]" rows="1" placeholder="Catatan"></textarea>                                                              
                                    </div>
                                </div>

                                <div class="col-md-4 my-auto">
                                    <div class="form-group mb-3">                         
                                        <label>Koefisien Dasar Bangunan</label>     
                                        <input type="hidden" name="kdb[]" value="koefiesien_dasar_bangunan" class="form-control">                                
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                                                   
                                        <input type="text" name="kdb[]" class="form-control" placeholder="Dimensi">                                                                           
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                        
                                        <textarea class="form-control" name="kdb[]" rows="1" placeholder="Catatan"></textarea>                                                              
                                    </div>
                                </div>

                                <div class="col-md-4 my-auto">
                                    <div class="form-group mb-3">                         
                                        <label>Koefisien Dasar Hijau</label>     
                                        <input type="hidden" name="kdh[]" value="koefiesien_dasar_hijau" class="form-control">                                
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                                                   
                                        <input type="text" name="kdh[]" class="form-control" placeholder="Dimensi">                                                                           
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                        
                                        <textarea class="form-control" name="kdh[]" rows="1" placeholder="Catatan"></textarea>                                                              
                                    </div>
                                </div>


                                <div class="col-md-4 my-auto">
                                    <div class="form-group mb-3">                         
                                        <label>Koefisien Lantai Bangunan</label>     
                                        <input type="hidden" name="kl[]" value="koefiesien_lantai_bangunan" class="form-control">                                
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                                                   
                                        <input type="text" name="kl[]" class="form-control" placeholder="Dimensi">                                                                           
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">                                        
                                        <textarea class="form-control" name="kl[]" rows="1" placeholder="Catatan"></textarea>                                                              
                                    </div>
                                </div>
                       
                            </div>  
                        @endif

                        @if($news->status == 3)
                           <div class="row">
                                <h6>Informasi Dimensi Bangunan dan Prasarana :</h6>
                                <div class="col-md-8">
                                    <div class="form-group mb-3">     
                                        <label>Bangunan Gedung</label>        
                                        <input type="hidden" name="idb[]" value="Informasi Dimensi Bangunan dan Prasarana" class="form-control">                     
                                        <textarea class="form-control" name="idb[]" rows="2"></textarea>                                                                       
                                    </div>
                                </div>
                                <h6>Informasi Dimensi Prasarana :</h6>
                                <div class="col-md-8">
                                    <div class="form-group mb-3">     
                                        <label>Prasarana :</label>                 
                                        <input type="hidden" name="idp[]" value="Informasi Dimensi Prasarana" class="form-control">                             
                                        <textarea class="form-control" name="idp[]" rows="1"></textarea>                                                                       
                                    </div>
                                </div>
                           </div>                           
                        @endif

                        @if($news->status == 2)
                            <div class="row">
                                    <h6>Catatan :</h6>
                                    <div class="col-md-8">
                                        <div class="form-group mb-3">                                                                    
                                            <textarea class="form-control" name="note" rows="2"></textarea>                                                                       
                                        </div>
                                    </div>                          
                            </div>
                        @endif
                    </div>  
                        
                        <div class="col-md-12" >
                            <button class="btn btn-primary rounded-pill">Next</button>
                            {{-- <a class="btn btn-danger ms-1 rounded-pill" href="{{route('news.index')}}">Back</a> --}}
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
<script src="{{asset('assets/extensions/choices.js/public/assets/scripts/choices.js')}}"></script>
<script src="{{asset('assets/static/js/pages/form-element-select.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$('#type').on('change',function(){
    var tipe = $(this).val();

    if(tipe == 'umum')
    {
        $('#con').html('Fungsi');
    }
    else
    {
        $('#con').html('Koordinat');
    }
});

$( '.select-field' ).select2( {
    theme: 'bootstrap-5'
});

$('#dis').on('change',function(e){
    e.preventDefault();    
    $('#des').empty();
    $.ajax({
        type:'POST',
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:"{{ route('village') }}",
        data:{id:$(this).val()},
        success:function(data){                        
            $.each(data, function(key, value) {
                $('#des').append('<option value="' + key + '">' + value + '</option>');
            });
        }
    });
});

</script>

@endpush