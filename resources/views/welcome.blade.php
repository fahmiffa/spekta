<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="{{ env('APP_DES') }}">
    <meta name="keywords" content="{{ env('APP_TAG') }},{{ env('APP_NAME') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | {{ env('APP_TAG') }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/auth.css') }}">

    <style>
        #bg {
            background: url('{{ asset('assets/bg1.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .vertical-center {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .round-left {
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
        }

        .round-right {
            border-top-right-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }

        @media (max-width: 576px) {
            .pad {
                margin-left: 1rem;
                margin-right: 1rem;
            }

            .mt-5
            {
                margin-top: 0rem !important;
            }

            .vertical-center {                  
               height: 80vh !important;
            }

            .round-left-m {
                border-top-left-radius: 0.75rem;
                border-bottom-left-radius: 0.75rem;
            }

            
        }
        .float-button {
             position: fixed;
             bottom: 20px;
             right: 20px;
             z-index: 1000;                
             box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
             border-radius: 50px;
         }
    </style>
</head>

<body>
    <div id="bg">  
        <div class="row justify-content-center vertical-center">
            <div class="col-md-12 col-lg-8 col-sm-12">
                <div class="row mx-3 my-3">               
                    <div class="col-md-6 mx-auto bg-white round-right shadow opacity-100 round-left-m round-left">
                        <div class="p-3">
                            <div class="d-flex justify-content-start py-1">
                                <img src="{{ asset('logo.png') }}" style="height: 5rem;" class="p-1 me-1">  
                                <div class="d-flex-row">
                                    <h2 class="my-auto fw-bolder">{{ env('APP_NAME') }}</h2>
                                    <h6 class="my-auto">Sistem Informasi Penyelenggaraan <br> Bangunan Gedung</h6>
                                </div>
                            </div>
                            <p class="fw-bold mt-5">Cek Dokumen Verifikasi
                                @if(session('res'))
                                    <button onclick="window.location.reload();" class="btn btn-danger rounded-pill shadow-sm btn-sm float-end">Reset</button>
                                @endif
                            </p>
                            <form class="mb-5" action="{{ route('store') }}" method="post" class="{{session('res') ? 'd-none' : null}}">
                                @csrf
                                <div class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" value="{{ old('reg') }}"
                                        name="reg" placeholder="Nomor Registrasi PBG/SLF">
                                    <div class="form-control-icon">
                                        <i class="bi bi-file-text"></i>
                                    </div>
                                    @error('reg')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group position-relative has-icon-left">
                                    <input type="text" class="form-control" value="{{ old('doc') }}"
                                        name="doc" placeholder="Nomor Dokumen Verifikasi">
                                    <div class="form-control-icon">
                                        <i class="bi bi-file-binary"></i>
                                    </div>
                                    @error('doc')
                                        <div class='small text-danger text-left'>{{ $message }}</div>
                                    @enderror
                                </div>
                                <button class="btn btn-warning rounded-pill shadow-sm  btn-sm fw-bold mb-3">Check</button>
                            </form>
                            @if(session('res'))
                                @php 
                                $items = session('res'); 
                                $header = (array) json_decode($items[0]->header);
                                $item = $items[0];
                                @endphp
                                <div class="row small">
                                    <div class="col-4">Nama Pemohon</div>
                                    <div class="col-8">: {{ $header ? $header[2] : null }}   </div>
                                    <div class="col-4">Alamat Pemohon</div>
                                    <div class="col-8 d-inline-flex">:&nbsp;<p class="mb-0">{{ $header ? $header[4] : null }}</p></div>                              
                                    <div class="col-4">No. Registrasi</div>
                                    <div class="col-8">: {{ $item->reg }}</div>
                                    <div class="col-4">No. Dokumen</div>
                                    <div class="col-8 d-inline-flex">:&nbsp;<p class="mb-0" >{{ $item->nomor }}</p></div>
                                    <div class="col-4">Nama Bangunan</div>
                                    <div class="col-8">: {{ $header ? $header[5] : null }}</div>
                                    <div class="col-4">Lokasi Bangunan</div>
                                    <div class="col-8 d-flex align-items-top">:&nbsp;<p class="mb-0">{{ $header ? $header[7].', ' : null }} {{ $item->region ? 'Desa/Kel. '.$item->region->name : null }} {{ $item->region ? ', Kec. '.$item->region->kecamatan->name : null }}, Kab. Tegal</p></div>
                                    <div class="col-4">Status</div>
                                    <div class="col-8">: {{$item->dokumen}}</div>                     
                                    <div class="col-4">Lihat Dokumen</div>                          
                                    <div class="col-8 d-inline-flex">:&nbsp;<a target="_blank" href="{{$items[1]}}" class="badge bg-danger rounded-pill">Dokumen</a></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>     
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>

    <script type="text/javascript">
        $('#reload').click(function() {
            $.ajax({
                type: 'GET',
                url: 'reload-captcha',
                success: function(data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        });
    </script>
</body>

</html>
