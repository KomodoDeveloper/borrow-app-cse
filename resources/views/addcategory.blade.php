@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-md-center mt-3">
            <div class="col col-lg-3">
            </div>
            <div class="col-lg-6">
                <form method="POST" action="{{ route('category.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nom </label>
                        <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nom" required>
                        @error('name')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Description de la catégorie"></textarea>
                        @error('description')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Envoyer</button>
                </form>
            </div>
            <div class="col col-lg-3">
            </div>
        </div>
    </div>
@stop
