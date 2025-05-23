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
            @include('graph')            
        </section>
    </div>
@endsection

@include('apex')   
