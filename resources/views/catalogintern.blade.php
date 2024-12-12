@extends('layouts.app')
@section('additionnalCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/homepage.css') }}" >
@stop
@section('content')
    @if(Auth::user()->is_cse_member ==1)
    <div class="container mb-5">

        <div class="row">
            <div class="col-lg-3">
                <h3 class="my-4">Catégories</h3>
                <div class="list-group">
                    <a href="{{ route('catalog.indexIntern') }}" class="list-group-item">Tout</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('catalog.internGetCategory', $category->id) }}" class="list-group-item">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
            <!-- /.col-lg-3 -->

            <div class="col-lg-9">

                <div class="mt-4 container">
                    <h4>Inventaire interne</h4>
                </div>
                <div class="row">
                    <div class="col-lg-4 my-1">
                        <input id="search" type="text" class="form-control" placeholder="Rechercher">
                    </div>
                </div>
                <div class="row" id="results">
                    @if(count($equipments) < 1)
                        <div class="col-lg-8 md-6 mb-4">
                            <div class="alert alert-warning" role="alert">
                                Aucun objet n'est disponible pour l'instant.
                            </div>
                        </div>
                    @else
                        @foreach ($equipments as $equipment)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100">
                                    <a href="{{ route('equipment.show', $equipment->id) }}"><img class="card-img-top" src="/storage/equipments_images/{{ $equipment->image }}" alt=""></a>
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            @if($equipment->is_out_of_service == 1)
                                                -HS-
                                            @endif
                                            <a href="{{ route('equipment.show', $equipment->id) }}"><strong>{{ $equipment->code }}-{{ $equipment->name  }}</strong></a>
                                        </h6>
                                        <h6 class="font-weight-light">
                                            @foreach($equipment->categories as $category)
                                                #{{ $category->name }}
                                            @endforeach
                                        </h6>
                                        <p class="card-text">{{ Str::limit($equipment->description, 60) }}</p>
                                    </div>
                                    <div class="card-footer">
                                        <small class="text-muted">
                                            @if($equipment->is_out_of_service == 1)
                                                <span style="font-size: 1.2em; color: darkorange;">
                                                    <i class="fas fa-tools"></i>
                                                </span>
                                            @else
                                                @if($equipment->availability == 1)
                                                    <span style="font-size: 1.2em; color: limegreen;">
                                                        <i class="fas fa-circle"></i>
                                                     </span>
                                                @else
                                                    <span style="font-size: 1.2em; color: red;">
                                                        <i class="fas fa-circle"></i>
                                                    </span>
                                                @endif
                                            @endif
                                        </small>
                                        <small class="text-muted">
                                            @if($equipment->internal == 1)
                                                <span style="font-size: 1.2em; color: purple;">
                                                    <i class="fas fa-house-user"></i>
                                                 </span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <!-- /.row -->

            </div>
            <!-- /.col-lg-9 -->
        </div>
        <!-- /.row -->
    </div>
    @else
        <h3 class="mt-5">Catalogue interne seulement pour le personnel du CSE</h3>
    @endif
@stop
@section('additionnalScript')
    <script>
        $("#search").keyup(function() {
            // Retrieve the input field text and reset the count to zero
            var filter = $(this).val(),
                count = 0;
            // Loop through the comment list
            $('#results .col-lg-4').each(function() {
                // If the list item does not contain the text phrase fade it out
                if ($(this).find(".card-body").text().search(new RegExp(filter, "i")) < 0) {
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
