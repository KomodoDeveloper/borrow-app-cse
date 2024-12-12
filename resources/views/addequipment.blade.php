@extends('layouts.app')
@section('additionnalCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/multiplechosen/component-chosen.min.css') }}" >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@stop
@section('content')
    <div class="container mb-5">
        <div class="row justify-content-md-center mt-3">
            <div class="col col-lg-3">
            </div>
            <div class="col-lg-6">
                @if(session()->has('errorInField'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        {{ session()->get('errorInField') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('equipment.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Entrez le nom de l'objet" required>
                        @error('name')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Description de l'objet"></textarea>
                        @error('description')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="seriallNumber">No de série</label>
                        <input name="seriallNumber" type="text" class="form-control @error('seriallNumber') is-invalid @enderror" id="seriallNumber" placeholder="numéro de série de l'objet" maxlength="30">
                        @error('seriallNumber')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="ci_number">No inventaire Ci</label>
                        <input name="ci_number" type="text" class="form-control @error('ci_number') is-invalid @enderror" id="ci_number" placeholder="numéro Ci si il y en a un" maxlength="6">
                        @error('ci_number')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="internal">Interne</label>
                            </div>
                            <div class="col-lg-1">
                                <input name="internal" type="checkbox" class="@error('internal') is-invalid @enderror" id="internal" value="true">
                            </div>
                            <div class="col-lg-8">
                            </div>
                        </div>
                        @error('internal')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="categories">Catégorie</label>
                        <select name='categories[]' id="multiple" class="form-control form-control-chosen" data-placeholder="Please select..." multiple>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="product_year">Année de production</label>
                            </div>
                            <div class="col-lg-8">
                                <input name="product_year" type="text" class="form-control @error('product_year') is-invalid @enderror" id="product_year" placeholder="juste l'année, exemple : 2019">
                            </div>
                        </div>
                        @error('product_year')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-4">
                                <label>Date d'achat</label>
                            </div>
                            <div class="col-lg-1">
                                <label for="purchase_date_month">Mois</label>
                            </div>
                            <div class="col-lg-3">
                                <select name="purchase_date_month" class="my-selector" data-width="fit" data-size="5" id="purchase_date_month">
                                    <option value="99" selected>-----</option>
                                    <option value="01">Janvier</option>
                                    <option value="02">Février</option>
                                    <option value="03">Mars</option>
                                    <option value="04">Avril</option>
                                    <option value="05">Mai</option>
                                    <option value="06">Juin</option>
                                    <option value="07">Juillet</option>
                                    <option value="08">Aout</option>
                                    <option value="09">Septembre</option>
                                    <option value="10">Octobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Décembre</option>
                                </select>
                            </div>
                            <div class="col-lg-1">
                                <label for="purchase_date_year">Année</label>
                            </div>
                            <div class="col-lg-3">
                                <select name="purchase_date_year" class="my-selector" data-width="fit" data-size="10" id="purchase_date_year">
                                    <option value="9999" selected>----</option>
                                    @for ($i = 1970; $i < 2060; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" class="form-control" id="image" required>
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Enregistrer</button>
                </form>
            </div>
            <div class="col col-lg-3">
            </div>
        </div>
    </div>

@stop
@section('additionnalScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.6/chosen.jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script>
        $('.form-control-chosen').chosen({
            // Chosen options here
        });
        $('.my-selector').selectpicker();
    </script>
@stop
