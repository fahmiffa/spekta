<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ ucwords(str_replace('pbg','PBG',str_replace('_',' ',$title))) }}</title>
    <meta content="{{ env('APP_DES') }}" name="description">
    <meta content="{{ env('APP_NAME') }}" name="keywords">
    <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}" type="image/x-icon">
</head>
<style>

    .signs{
        width: 50px;
        height: 50px;
        object-fit: cover;
    }
    
    body {
        font-size: 11pt;
        margin: 0;
        padding: 0;
        height: 100vh;
        width: 100%;
        background: white;
        font-family: 'Noto Serif', serif;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
    }

    td {
        border: 1px solid black;
        vertical-align: top;
    }

    .warp {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-all;
        white-space: normal; 
    }

    .img {
        object-fit: cover;
        width: 50px;
        height: 50px;
    }

    .ttd {
        object-fit: cover;
        height: 60px;
    }

    .page-break {
        page-break-after: always;
    }

    .watermark {
        position: fixed;
        top: 50%;
        left: 45%;
        transform: translate(-50%, -50%);
        opacity: 0.07;
        z-index: -1;
    }

    p {
        overflow-wrap: break-word;
        white-space: normal;
    }

    .footer {
        position: fixed;
        bottom: -38px;
        left: 0px;
        right: 0px;
        height: 20px;
        color: black;
    }

    .table-bordered{        
        width: 100%;
    }

    .table-bordered td{
        border: none;        
    }

    .simbol{
        font-family:DejaVu Sans;
    }

    .des {
        margin-left:1rem;
    }

    .des, p {
        margin-top:0.1rem;
    }

    .column {
        float: left;
        width: 20%;
        padding: 5px;
    }

    .columns {
        float: left;
        width: 78%;
        padding: 5px;
    }

    .clearfix {
        clear: both;
    }

    li {
        text-align:justify;
    }
</style>

</style>

<body>
    @yield('main')
</body>

</html>
