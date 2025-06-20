<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | {{ env('APP_TAG') }}</title>

    <meta content="{{ env('APP_DES') }}" name="description">
    <meta content="{{ env('APP_NAME') }}" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    @stack('css')

    <style>
        table.dataTable thead th {
            text-align: center;
        }

        .toast-top-right {
            top: 10% !important;
            left: 60% !important;
            transform: translate(-50%, -50%) !important;
            position: fixed !important;
            z-index: 9999 !important;
        }
    </style>
</head>

<body>
    <div id="app">
        @isset($single)
            <nav class="navbar navbar-light">
                <div class="container d-block">
                    @isset($public)
                    @else
                        @isset($kabid)
                            <a href="{{ route('ba.verifikasi') }}"><i class="bi bi-chevron-left"></i></a>
                        @else
                            <a href="{{ $doc == 'bak' ? route('news.index') : route('meet.index') }}"><i
                                    class="bi bi-chevron-left"></i></a>
                        @endisset
                    @endisset
                    <span class="navbar-brand ms-4 d-inline fw-bold ttd">
                        {{ $title }}
                    </span>
                </div>
            </nav>
            @yield('main')
        @else
            <div id="sidebar">
                <div class="sidebar-wrapper active">
                    <div class="sidebar-header position-relative">
                        <a href="{{ route('main') }}">
                            <div class="d-inline-flex">
                                <img style="height: 1.4rem" class="my-auto" src="{{ asset('assets/logo.png') }}"
                                    alt="Logo" srcset="">
                                <h4 class="ms-2 my-auto">{{ env('APP_NAME') }}</h4>
                            </div>
                        </a>
                        <p class="text-muted" style="font-size: 0.8rem">Sistem Informasi Penyelenggaraan Bangunan Gedung</p>
                    </div>
                    <div class="sidebar-menu">
                        @include('layout/sidebar')
                    </div>
                </div>
            </div>
            <div id="main" class='layout-navbar navbar-fixed'>
                <header>
                    <nav class="navbar navbar-expand navbar-light navbar-top">
                        <div class="container-fluid">
                            <a href="#" class="burger-btn d-block">
                                <i class="bi bi-justify fs-3"></i>
                            </a>

                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav mb-lg-0">
                                    <li class="nav-item dropdown me-3">
                                        <a class="nav-link active dropdown-toggle text-gray-600" href="#" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                            <i class="bi bi-bell bi-sub fs-4"></i>
                                            <span class="badge badge-notification bg-danger" id="notif">0</span>
                                        </a>
                                        <ul style="height: 500px" class="dropdown-menu dropdown-center dropdown-menu-sm-start notification-dropdown overflow-auto" aria-labelledby="dropdownMenuButton" id="this">
                                            <li class="dropdown-header">
                                                <h6>Notifikasi</h6>
                                            </li>                
                                        </ul>
                                    </li>
                                </ul>
                                <div class="theme-toggle d-flex gap-2 align-items-center mt-2 ms-auto me-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        aria-hidden="true" role="img" class="iconify iconify--system-uicons"
                                        width="20" height="20" preserveAspectRatio="xMidYMid meet"
                                        viewBox="0 0 21 21">
                                        <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path
                                                d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                                opacity=".3"></path>
                                            <g transform="translate(-210 -1)">
                                                <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                                <circle cx="220.5" cy="11.5" r="4"></circle>
                                                <path
                                                    d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                                </path>
                                            </g>
                                        </g>
                                    </svg>
                                    <div class="form-check form-switch fs-6">
                                        <input class="form-check-input  me-0" type="checkbox" id="toggle-dark"
                                            style="cursor: pointer">
                                        <label class="form-check-label"></label>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        aria-hidden="true" role="img" class="iconify iconify--mdi" width="20"
                                        height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="dropdown">
                                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="user-menu d-flex align-items-center">
                                            <div class="user-name me-3">
                                                <div class="text-gray-600 text-nowrap my-auto fw-bold">
                                                    {{ ucwords(auth()->user()->name) }}</div>
                                            </div>
                                            <div class="user-img d-flex align-items-center">
                                                <div class="avatar avatar-md">
                                                    @if(auth()->user()->img)
                                                        <img src="{{ asset('storage/'.auth()->user()->img) }}">
                                                    @else
                                                        <img src="{{ asset('assets/compiled/jpg/2.jpg') }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                                        style="min-width: 11rem;">
                                        <li><a class="dropdown-item" href="{{ route('profile') }}"><i
                                                    class="icon-mid bi bi-person me-2"></i>
                                                Profil</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('logout') }}"><i
                                                    class="icon-mid bi bi-box-arrow-left me-2"></i> Keluar</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </nav>
                </header>
                <div id="main-content">
                    @yield('main')
                </div>
                <footer class="my-2">
                    <div class="footer clearfix mb-0 text-muted">
                        <div class="float-start d-flex align-items-center">
                            <img src="{{ asset('assets/logo.png') }}" class="me-1"  style="height: 2.4rem; width:2.4rem;"> 
                            <p class="mb-0">Dinas Pekerjaan Umum dan Penataan Ruang (DPUPR) <br>Kabupaten Tegal</p>
                        </div>
                    </div>
                </footer>
            </div>
        @endisset
    </div>
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    @stack('js')

    <script>


        document.addEventListener('DOMContentLoaded', function () {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            }, false);

        function goBack() {
            window.history.back()
        }

        function prev() {
            document.getElementById('back').submit();
        }

        function myConfirm(par) {
            let person = prompt("Apakah Anda Yakin "+par+" silahkan ketikan ok");
            if(person === 'ok')
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        function getData()
        {
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('notif') }}",
                success: function(data) {
                    let n = 0;
                    const ver = data.filter((item) => item.verifikator == true);
                    const tp = data.filter((item) => item.penugasan == true);
                    const bak = tp.filter((item) => item.statusBak != 1 && item.bakUri != null);
                    const barp = tp.filter((item) => item.barp == null && item.barpUri != null);
                    const ba = data.filter((item) => item.persetujuan == true);

                 

                    if(ba.length > 0)
                    {
                        n = ba.length;
                    }

                    if(ver.length > 0)
                    {
                        n = ver.length;
                    }

                    if(tp.length > 0)
                    {
                        n = bak.length + barp.length;
                    }

                    $('#notif').html(n);

                    $.each(bak, function(key, value) {
                        $('#this').append('<li class="dropdown-item notification-item">\
                                            <a class="d-flex align-items-center" href="'+value.bakUri+'">\
                                                <div class="notification-icon bg-danger">\
                                                    <i class="bi bi-file-earmark-check"></i>\
                                                </div>\
                                                <div class="notification-text ms-4">\
                                                    <p class="notification-title font-bold">Dokumen '+value.reg+'</p>\
                                                     <p class="notification-title font-bold small">'+value.name+'</p>\
                                                    <p class="notification-subtitle font-thin text-sm">'+value.msg+'</p>\
                                                </div>\
                                            </a>\
                                            </li>');
                    });

                     $.each(barp, function(key, value) {
                         const msg = value.statusBarp;
                         console.log(msg);
                         $('#this').append('<li class="dropdown-item notification-item">\
                                             <a class="d-flex align-items-center" href="'+value.barpUri+'">\
                                                 <div class="notification-icon bg-primary">\
                                                     <i class="bi bi-file-earmark-check"></i>\
                                                 </div>\
                                                 <div class="notification-text ms-4">\
                                                     <p class="notification-title font-bold">Dokumen '+value.reg+'</p>\
                                                     <p class="notification-title font-bold">Status '+msg+'</p>\
                                                     <p class="notification-title font-bold small">'+value.name+'</p>\
                                                     <p class="notification-subtitle font-thin text-sm">Belum Tanda Tangan BARP</p>\
                                                 </div>\
                                             </a>\
                                             </li>');
                    });

                    $.each(ba, function(key, value) {
                        $('#this').append('<li class="dropdown-item notification-item">\
                                            <a class="d-flex align-items-center" href="'+value.uri+'">\
                                                <div class="notification-icon bg-danger">\
                                                    <i class="bi bi-file-earmark-check"></i>\
                                                </div>\
                                                <div class="notification-text ms-4">\
                                                    <p class="notification-title font-bold">Dokumen '+value.reg+'</p>\
                                                            <p class="notification-title font-bold small">'+value.name+'</p>\
                                                    <p class="notification-subtitle font-thin text-sm">Menunggu Persetujuan</p>\
                                                </div>\
                                            </a>\
                                            </li>');
                    });

                    $.each(ver, function(key, value) {
                        $('#this').append('<li class="dropdown-item notification-item">\
                                            <a class="d-flex align-items-center" href="'+value.uri+'">\
                                                <div class="notification-icon bg-danger">\
                                                    <i class="bi bi-file-earmark-check"></i>\
                                                </div>\
                                                <div class="notification-text ms-4">\
                                                    <p class="notification-title font-bold">Dokumen '+value.reg+'</p>\
                                                    <p class="notification-title font-bold small">'+value.name+'</p>\
                                                    <p class="notification-subtitle font-thin text-sm">Dokumen '+value.par+'</p>\
                                                </div>\
                                            </a>\
                                            </li>');
                    });

                 
                }
            });

        }

        getData();
        // setInterval(() => {
        //     $('#this').empty();
        //     getData();
        // }, 10000);


    </script>
</body>

</html>
