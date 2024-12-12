@extends('layouts.app')
@section('content')
    <div class="container mt-3 mb-5">
        <div class="row">
            <div class="col-lg-4 my-1">
                <input id="search_in_archive" type="text" class="form-control" placeholder="Rechercher">
            </div>
        </div>
        <div id="results_in_archive">
            @foreach ($archiveBorrows as $b)
                @if($loop->first)
                @elseif($b->a_equipment_id != $archiveBorrows[$loop->index-1]->a_equipment_id)
                    <hr style="border-top: 3px dashed black">
                @endif
                <div class="card border-dark my-1">
                    <div class="card-header font-weight-bold">
                        <div class="row">
                            <div class="col-lg-12">
                                Equipment emprunté : <b>@if(!is_object($b->a_equipment)) objet supprimé @else {{ $b->a_equipment->code }} - {{ $b->a_equipment->name }} @endif</b>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-5">
                                <h5 class="card-title">{{ $b->a_first_name_borrower }} {{ $b->a_surname_borrower }}</h5>
                            </div>
                            <div class="col-lg-3">
                                <h6 class="card-title">{{ $b->a_email_borrower }}</h6>
                            </div>
                            <div class="col-lg-4">
                                <h6 class="card-title status">statut : <strong>{{ $b->a_status}}</strong></h6>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-5 d-flex align-items-center">
                                Emprunt No : {{ $b->origin_id}} (id d'origine)
                            </div>
                            <div class="col-lg-3 d-flex align-items-center">
                                Début : {{ $b->a_start_date }}
                            </div>
                            <div class="col-lg-4 d-flex align-items-center">
                                Fin : {{ $b->a_end_date }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop
@section('additionnalScript')
    <script>
        $("#search_in_archive").keyup(function() {
            // Retrieve the input field text and reset the count to zero
            var filter = $(this).val(),
                count = 0;
            // Loop through the comment list
            $('#results_in_archive .card').each(function() {
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

