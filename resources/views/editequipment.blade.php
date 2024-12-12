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
                <form method="POST" action="{{ route('equipment.update', $equipment->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ $equipment->name }}" required>
                        @error('name')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description">{{ $equipment->description }}</textarea>
                        @error('description')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="seriallNumber">No de série</label>
                        <input name="seriallNumber" type="text" class="form-control @error('seriallNumber') is-invalid @enderror" id="seriallNumber" value="{{ $equipment->seriallNumber }}" maxlength="30">
                        @error('seriallNumber')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="ci_number">No inventaire Ci</label>
                        <input name="ci_number" type="text" class="form-control @error('ci_number') is-invalid @enderror" id="ci_number" value="{{ $equipment->ci_number }}" maxlength="6">
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
                                <input name="internal" type="checkbox" class="@error('internal') is-invalid @enderror" id="internal" value="true" {{ $equipment->internal == 1 ? 'checked' : '' }}>
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
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="is_out_of_service">Hors service</label>
                            </div>
                            <div class="col-lg-1">
                                <input name="is_out_of_service" type="checkbox" class="@error('is_out_of_service') is-invalid @enderror" id="is_out_of_service" value="true" {{ $equipment->is_out_of_service == 1 ? 'checked' : '' }}>
                            </div>
                            <div class="col-lg-8">
                            </div>
                        </div>
                        @error('is_out_of_service')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="categories">Catégorie</label>
                        <select name='categories[]' class="form-control form-control-chosen" id="multiple" multiple>
                            @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @foreach($equipment->categories as $categoryOfEquipment)
                                        {{ $categoryOfEquipment->id == $category->id ? 'selected' : '' }} @endforeach>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="product_year">Année de production</label>
                            </div>
                            <div class="col-lg-8">
                                <input name="product_year" type="text" class="form-control @error('product_year') is-invalid @enderror" id="product_year" value="{{ $equipment->product_year }}">
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
                                    <option value="99" {{ $stored_purchase_month == "99" ? 'selected' : '' }}>-----</option>
                                    <option value="01" {{ $stored_purchase_month == "01" ? 'selected' : '' }}>Janvier</option>
                                    <option value="02" {{ $stored_purchase_month == "02" ? 'selected' : '' }}>Février</option>
                                    <option value="03" {{ $stored_purchase_month == "03" ? 'selected' : '' }}>Mars</option>
                                    <option value="04" {{ $stored_purchase_month == "04" ? 'selected' : '' }}>Avril</option>
                                    <option value="05" {{ $stored_purchase_month == "05" ? 'selected' : '' }}>Mai</option>
                                    <option value="06" {{ $stored_purchase_month == "06" ? 'selected' : '' }}>Juin</option>
                                    <option value="07" {{ $stored_purchase_month == "07" ? 'selected' : '' }}>Juillet</option>
                                    <option value="08" {{ $stored_purchase_month == "08" ? 'selected' : '' }}>Aout</option>
                                    <option value="09" {{ $stored_purchase_month == "09" ? 'selected' : '' }}>Septembre</option>
                                    <option value="10" {{ $stored_purchase_month == "10" ? 'selected' : '' }}>Octobre</option>
                                    <option value="11" {{ $stored_purchase_month == "11" ? 'selected' : '' }}>Novembre</option>
                                    <option value="12" {{ $stored_purchase_month == "12" ? 'selected' : '' }}>Décembre</option>
                                </select>
                            </div>
                            <div class="col-lg-1">
                                <label for="purchase_date_year">Année</label>
                            </div>
                            <div class="col-lg-3">
                                <select name="purchase_date_year" class="my-selector" data-width="fit" data-size="10" id="purchase_date_year">
                                    <option value="9999" {{ $stored_purchase_year == "9999" ? 'selected' : '' }}>----</option>
                                    @for ($i = 1970; $i < 2060; $i++)
                                        <option value="{{ $i }}" {{ $stored_purchase_year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" class="form-control" id="image">
                    </div>
                    <div class="form-group">
                        <img src="/storage/equipments_images/{{ $equipment->image }}" class="img-rounded"  width="auto" height="200em">
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
