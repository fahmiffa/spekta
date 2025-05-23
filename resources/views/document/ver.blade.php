@extends('layout.pdf')
@section('main')
    {{-- bak --}}
    @if ($head->bak && $head->bak->grant == 1)
        @include('document.bak.doc.home')
    @endif
    <div class="page-break"></div>

    {{-- barp --}}
    @if ($head->barp && $head->barp->grant == 1)
        @include('document.barp.doc.home')
    @endif
    <div class="page-break"></div>

    {{-- lampiran --}}
    @if ($head->attach)
        @include('document.attach.doc.home')
    @endif
    <div class="page-break"></div>

    {{-- retribusi --}}
    @if ($head->tax)
        @include('document.tax.doc.home')
    @endif
@endsection
