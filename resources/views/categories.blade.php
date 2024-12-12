@extends('layouts.app')
@section('content')
@if (Auth::user()->is_admin == 1)
    <div class="container mt-3 mb-5">
        @if(session()->has('addCategory'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                {{ session()->get('addCategory') }}
            </div>
        @endif
        @if(session()->has('updateCategory'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                {{ session()->get('updateCategory') }}
            </div>
        @endif
        <div class="row my-3">
            <div class="col-lg-3"></div>
            <div class="col-lg-6 text-center"><h3>Catégories</h3></div>
            <div class="col-lg-3"></div>
        </div>
        <div class="row my-3">
            <div class="col-lg-3"></div>
            <div class="col-lg-6 text-right"><a href="{{ route('category.create') }}" class="btn btn-primary">Ajouter</a></div>
            <div class="col-lg-3"></div>
        </div>
        @foreach ($categories as $c)
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-6">
                    <div class="card border-dark my-1">
                    <div class="card-body">
                        <h5 class="card-title">{{ $c->name}}</h5>
                        <div class="row">
                            <div class="col-lg-10 d-flex align-items-center">
                                {{ $c->description }}
                            </div>
                            <div class="col-lg-1 d-flex align-items-center">
                                <a class="btn btn-outline-dark btn-sm mr-1" href="{{ route('category.edit', $c->id) }}" role="button">&#9998;</a>
                            </div>
                            <div class="col-lg-1 d-flex align-items-center">
                                <a class="btn btn-outline-dark btn-sm mr-1" href="{{ route('category.destroy', $c->id) }}" role="button">&#10008;</a>
                            </div>
                        </div>
                     </div>
                     </div>
                </div>
                <div class="col-lg-3"></div>
            </div>
        @endforeach
    </div>
@else
    <div class="container">
        <h3 class="mt-5">Vous ne pouvez pas accéder à cette page ! Gestionnaire seulement</h3>
    </div>
@endif
@stop


