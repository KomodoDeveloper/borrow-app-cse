@extends('layouts.app')
@section('content')
@if (Auth::user()->is_admin == 1)
<div class="container">
    @if(session()->has('success'))
        <div class="row text-align-center">
            <div class="col-sm"></div>
            <div class="col-sm mt-2 mb-2 d-flex justify-content-center">
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            </div>
            <div class="col-sm"></div>
        </div>
    @endif
    <div class="row text-align-center">
        <div class="col-sm"></div>
        <div class="col-sm mt-2 mb-2 d-flex justify-content-center"><h3>Menu</h3></div>
        <div class="col-sm"></div>
    </div>
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm mt-2 mb-2"><button type="button" class="btn btn-secondary btn-lg btn-block" onclick="location.href='{{ route('borrow.createmany') }}'">Demande de prêt multiple</button></div>
        <div class="col-sm"></div>
    </div>
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm mt-2 mb-2"><button type="button" class="btn btn-secondary btn-lg btn-block" onclick="location.href='{{ route('borrow.create') }}'">Demande de prêt</button></div>
        <div class="col-sm"></div>
    </div>
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm mt-2 mb-2"><button type="button" class="btn btn-secondary btn-lg btn-block" onclick="location.href='{{ route('stateofborrow.index') }}'">Etat des prêts</button></div>
        <div class="col-sm"></div>
    </div>
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm mt-2 mb-2"><button type="button" class="btn btn-secondary btn-lg btn-block" onclick="location.href='{{ route('stateofborrow.tocontrolindex') }}'">A contrôler</button></div>
        <div class="col-sm"></div>
    </div>
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm mt-2 mb-2"><button type="button" class="btn btn-secondary btn-lg btn-block" onclick="location.href='{{ route('equipment.create') }}'">Ajouter un objet</button></div>
        <div class="col-sm"></div>
    </div>
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm mt-2 mb-2"><button type="button" class="btn btn-secondary btn-lg btn-block" onclick="location.href='{{ route('category.index') }}'">Gérer les catégories</button></div>
        <div class="col-sm"></div>
    </div>
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm mt-2 mb-2"><button type="button" class="btn btn-secondary btn-lg btn-block" onclick="location.href='{{ route('admin.listinventorymultimedia') }}'">Inventaire multimédia</button></div>
        <div class="col-sm"></div>
    </div>
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm mt-2 mb-2"><button type="button" class="btn btn-secondary btn-lg btn-block" onclick="location.href='{{ route('admin.listinventorycollab') }}'">Inventaire par collaborateur</button></div>
        <div class="col-sm"></div>
    </div>
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm mt-2 mb-2"><button type="button" class="btn btn-secondary btn-lg btn-block" onclick="location.href='{{ route('admin.archive') }}'">Archive</button></div>
        <div class="col-sm"></div>
    </div>
    <div class="row">
        <div class="col-sm"></div>
        <div class="col-sm mt-2 mb-2"><button type="button" class="btn btn-secondary btn-lg btn-block" onclick="location.href='{{ route('admin.scan') }}'">Scan</button></div>
        <div class="col-sm"></div>
    </div>
</div>
@else
<div class="container">
    <h3 class="mt-5">Vous ne pouvez pas accéder à cette page ! Gestionnaire seulement</h3>
</div>
@endif
@stop
