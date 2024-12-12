@extends('layouts.app')
@section('additionnalCSS')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@stop
@section('content')
@if (Auth::user()->is_admin == 1)
    <div class="container mt-3 mb-5">
        @if(session()->has('updateBorrow'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                {{ session()->get('updateBorrow') }}
            </div>
        @endif
        @if(session()->has('deleteBorrow'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                {{ session()->get('deleteBorrow') }}
            </div>
        @endif
        <div class="row">
            <div class="col-lg-4 my-1">
                <input id="search_to_control_borrow" type="text" class="form-control" placeholder="Rechercher">
            </div>
        </div>
        <div id="results_to_control_borrow_search">
        @foreach ($borrowsToControl as $b)
            <div class="card {{ $b->status == 'to_control' ? 'border-info' : 'border-dark' }} my-1">
                <div class="card-header font-weight-bold">
                    <div class="row">
                        <div class="col-lg-2">
                            Emprunt No : {{ $b->id}}
                        </div>
                        <div class="col-lg-6 font-weight-light">
                            créé le : {{ $b->created_at }}
                        </div>
                        <div class="col-lg-4" >
                            <strong>Matériel à contrôler</strong>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="card{{$b->id}}">
                    <div class="row">
                        <div class="col-lg-5">
                            <h5 class="card-title">{{ $b->first_name_borrower }} {{ $b->surname_borrower }}</h5>
                        </div>
                        <div class="col-lg-3">
                            <h6 class="card-title">{{ $b->email_borrower }}</h6>
                        </div>
                        <div class="col-lg-4">
                            <h6 class="card-title status">statut : <strong>{{ $b->status}}</strong></h6>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-5 d-flex align-items-center">
                            Equipment emprunté :
                            @if($b->equipment->is_out_of_service == 1)
                                <span style="font-size: 1.5em; color: darkorange;">
                                        <i class="fas fa-tools fa-xs"></i>
                                </span>
                                &nbsp;<b style="color: darkorange">{{ $b->equipment->code }} - {{ $b->equipment->name }}</b>
                                &nbsp;<a href="{{ route('equipment.show', $b->equipment->id) }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt"></i></a>
                            @else
                                <b>{{ $b->equipment->code }} - {{ $b->equipment->name }}</b>
                                &nbsp;<a href="{{ route('equipment.show', $b->equipment->id) }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt"></i></a>
                            @endif
                        </div>
                        <div class="col-lg-3 d-flex align-items-center">
                            Début : {{ $b->start_date }}
                        </div>
                        <div class="col-lg-3 d-flex align-items-center">
                            Fin : {{ $b->end_date }}
                        </div>
                        <div class="col-lg-1 d-flex align-items-center">
                            <a class="btn btn-outline-dark btn-sm mr-1" href="{{ route('borrow.edit', $b->id) }}" role="button">&#9998;</a>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-11 d-flex align-items-center">
                            @if($b->need_explanation == 1)
                                &#10137; Je ne connais pas bien le matériel et aurais besoin d'explications/conseils
                            @elseif($b->need_explanation == 0)
                                &#10137; Je connais le matériel et n'aurais pas besoin d'explications/conseils
                            @endif
                        </div>
                        <div class="col-lg-1 d-flex align-items-center">
                            <a href="{{ route('stateofborrow.delete', $b->id) }}" class="btn btn-outline-dark btn-sm mr-1"><i class="far fa-trash-alt"></i></a>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            Motifs : {{ $b->reason }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{ route('stateofborrow.close', $b->id) }}" class="btn btn-primary">Clôturer</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
@else
    <div class="container">
        <h3 class="mt-5">Vous ne pouvez pas accéder à cette page ! Gestionnaire seulement</h3>
    </div>
@endif
@stop
@section('additionnalScript')
    <script>
        $("#search_to_control_borrow").keyup(function() {
            // Retrieve the input field text and reset the count to zero
            var filter = $(this).val(),
                count = 0;
            // Loop through the comment list
            $('#results_to_control_borrow_search .card').each(function() {
                // If the list item does not contain the text phrase fade it out
                if ($(this).find(".card-body, .card-header").text().search(new RegExp(filter, "i")) < 0) {
                    $(this).hide();
                    // Show the list item if the phrase matches and increase the count by 1
                } else {
                    $(this).show();
                    count++;
                }
            });
        });
    </script>
@stop


