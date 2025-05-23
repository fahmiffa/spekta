@extends('layout.base')
@section('main')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Ringkasan</h3>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card card-body">
                        <div class="row">
                            <div class="col col-md-4">
                                <div class="d-flex jusitify-content-between">
                                    <div class="p-1">
                                        <div class="stats-icon mb-2" style="background-color : #ff5722;">
                                            <i class="iconly-boldPaper"></i>
                                        </div>
                                    </div>
                                    <div class="p-1">
                                        <h6 class="text-muted font-semibold">Penugasan</h6>
                                        <h6 class="font-extrabold mb-0">{{ $task }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-md-4">
                                <div class="d-flex jusitify-content-between">
                                    <div class="p-1">
                                        <div class="stats-icon green mb-2">
                                            <i class="iconly-boldPaper"></i>
                                        </div>
                                    </div>
                                    <div class="p-1">
                                        <h6 class="text-muted font-semibold">Selesai</h6>
                                        <h6 class="font-extrabold mb-0">{{ $comp }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (auth()->user()->roles->kode != 'TPA')
                @include('graph')
            @endif

        </section>
    </div>
@endsection

@include('apex')   
