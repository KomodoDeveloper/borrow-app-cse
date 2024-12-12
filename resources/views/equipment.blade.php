@extends('layouts.app')
@section('additionnalCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/equipment.css') }}" >
@stop
@section('content')
    <!-- Page Content -->
    <div class="container mb-5">

        <div class="row">

            <div class="col-lg-2">
            </div>
            <!-- /.col-lg-2 -->

            <div class="col-lg-8">
                @if(session()->has('updateEquipment'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        {{ session()->get('updateEquipment') }}
                    </div>
                @endif
                @if(session()->has('warningEquipment'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        {{ session()->get('warningEquipment') }}
                    </div>
                @endif
                <div class="card mt-3 mb-3">
                    <img class="card-img-top img-fluid" src="/storage/equipments_images/{{ $equipment->image }}" alt="">
                    <div class="card-body">
                        <h3 class="card-title">{{ $equipment->name }}</h3>
                        <div class="row">
                            <div class="col-lg-6">
                                <h4>Qrcode : {{ $equipment->code }}</h4>
                            </div>
                            <div class="col-lg-6">
                                <h4>No série : {{ $equipment->seriallNumber }}</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <h5>
                                    @foreach($equipment->categories as $category)
                                        #{{ $category->name }}
                                    @endforeach
                                </h5>
                            </div>
                            <div class="col-lg-3">
                                <h4>No Ci : {{ $equipment->ci_number }}</h4>
                            </div>
                            <div class="col-lg-3 text-right">
                                @if (Auth::user()->is_admin == 1)
                                    <a class="btn btn-secondary btn-sm mr-1" href="{{ route('equipment.edit', $equipment->id) }}" role="button">&#9998;</a>
                                    <a class="btn btn-secondary btn-sm mr-1" href="{{ route('equipment.duplicate', $equipment->id) }}" role="button"><i class="far fa-copy"></i></a>
                                    <a class="btn btn-secondary btn-sm" onclick="return confirm('Voulez-vous supprimer cet objet ?')" href="{{ route('equipment.destroy', $equipment->id) }}" role="button">&#10008;</a>
                                @endif
                            </div>
                        </div>
                        <p class="card-text">{{ $equipment->description }}</p>

                        <div class="row align-middle">
                            <div class="col-lg-1 d-flex align-items-center">
                                @if($equipment->is_out_of_service == 1)
                                    <span style="font-size: 1.5em; color: darkorange;">
                                        <i class="fas fa-tools"></i>
                                    </span>
                                @else
                                    @if($equipment->availability == 1)
                                        <span style="font-size: 1.5em; color: limegreen;">
                                            <i class="fas fa-circle"></i>
                                        </span>
                                    @else
                                        <span style="font-size: 1.5em; color: red;">
                                            <i class="fas fa-circle"></i>
                                        </span>
                                    @endif
                                @endif
                            </div>
                            <div class="col-lg-7 my-auto">
                                @if($equipment->is_out_of_service == 0)
                                    @if($equipment->availability == 0)
                                        Emprunt en cours  du  {{ $borrow->start_date }} au {{ $borrow->end_date }} &nbsp;
                                        @if (Auth::user()->is_admin == 1 and $borrow->status != "to_control")
                                            <a href="{{ route('stateofborrow.return', $borrow->id) }}" class="btn btn-primary">Rendu</a>
                                        @endif
                                    @endif
                                @else
                                    HORS SERVICE
                                @endif
                            </div>
                            <div class="col-lg-4 d-flex align-items-center">
                                @if($equipment->is_out_of_service == 0)
                                    @if (Auth::user()->is_admin == 1)
                                        <button type="button" class="btn btn-primary btn-block" onclick="location.href='{{ route('borrow.customcreate', $equipment->id) }}'">Demande de prêt</button>
                                    @else
                                        <p>Merci d'envoyer un mail à <b>cse@unil.ch</b></p>
                                    @endif
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.card -->

            </div>
            <!-- /.col-lg-8 -->
            <div class="col-lg-2">
            </div>

        </div>

    </div>
    <!-- /.container -->
@stop
