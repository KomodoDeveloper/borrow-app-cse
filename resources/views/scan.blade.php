@extends('layouts.app')
@section('additionnalCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/qrcodereader/qrcode-reader.min.css') }}" >
@stop
@section('content')
    <div class="container mb-5">
        @if(session()->has('warning'))
            <div class="row text-align-center">
                <div class="col-sm"></div>
                <div class="col-sm mt-2 mb-2 d-flex justify-content-center">
                    <div class="alert alert-danger">
                        {{ session()->get('warning') }}
                    </div>
                </div>
                <div class="col-sm"></div>
            </div>
        @endif
        <div class="row text-align-center">
            <div class="col-sm"></div>
            <div class="col-sm mt-2 mb-2 d-flex justify-content-center"><h3>Scanner</h3></div>
            <div class="col-sm"></div>
        </div>
        <form method="POST" action="{{ route('admin.getElementByCode') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-sm"></div>
                <div class="col-sm mt-2 mb-2"><input class="form-control form-control-lg" type="text" id="target-input" name="code"/></div>
                <div class="col-sm"></div>
            </div>
            <div class="row">
                <div class="col-sm"></div>
                <div class="col-sm mt-2 mb-2">
                    <input type="button" class="btn btn-secondary btn-lg btn-block" id="openreader-btn" data-qrr-target="#target-input" data-qrr-audio-feedback="false" value="Scan QRCode"/>
                </div>
                <div class="col-sm"></div>
            </div>
            <div class="row">
                <div class="col-sm"></div>
                <div class="col-sm mt-2 mb-2"><button type="submit" class="btn btn-primary btn-lg btn-block mt-3" >Détails de l'objet</button></div>
                <div class="col-sm"></div>
            </div>
        </form>
    </div>
@stop
@section('additionnalScript')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="{{ asset('javascript/qrcodereader/qrcode-reader.min.js') }}"></script>
    <script>
        $.qrCodeReader.jsQRpath = {!! json_encode(asset('javascript/jsQR/jsQR.js')) !!};
        $("#openreader-btn").qrCodeReader();


    </script>
@stop
