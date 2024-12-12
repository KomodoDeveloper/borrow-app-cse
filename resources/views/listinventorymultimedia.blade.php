@extends('layouts.app')
@section('additionnalCSS')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css" />
@stop
@section('content')
    @if (Auth::user()->is_admin == 1)
        <div class="container">
            <h3 class="mt-4 mb-3">Inventaire des objets multimédia</h3>
            <h6 class="mb-3">Tableau avec les équipments (objets) qui ont la catégorie CAME</h6>
            <div class="row">
                <div class="col-lg-2">
                    <h6><i class="fas fa-circle" style="font-size: 1.2em; color: orange;"></i>  Objet hors service</h6>
                </div>
                <div class="col-lg-10">
                    <h6><i class="fas fa-circle" style="font-size: 1.2em; color: Lime;"></i>  Objet fonctionnel</h6>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-12">
                    <h6><i class="fas fa-circle" style="font-size: 1.2em; color: purple;"></i> Objet taggé interne </h6>
                </div>
            </div>
        </div>
        <div class="mx-3 mb-5">
            <table id="table_media" class="display">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Numérie de série</th>
                    <th>Code CSE</th>
                    <th>Interne</th>
                    <th>Etat</th>
                    <th>Année de production</th>
                    <th>Date d'achat</th>
                    <th>Date de fin</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($allEquipmentsTaggedMultimedia as $equiTaggedMultimediaInteration)
                    <tr>
                        <td>{{ $equiTaggedMultimediaInteration->name}}</td>
                        <td>{{ $equiTaggedMultimediaInteration->description }}</td>
                        <td>{{ $equiTaggedMultimediaInteration->seriallNumber }}</td>
                        <td>{{ $equiTaggedMultimediaInteration->code }}</td>
                        <td><i class="fas fa-circle" style="{{ $equiTaggedMultimediaInteration->internal == 1 ? 'font-size: 1.2em; color: purple;' : 'font-size: 1.2em; color: LightBlue;' }}"></i></td>
                        <td><i class="fas fa-circle" style="{{ $equiTaggedMultimediaInteration->is_out_of_service == 1 ? 'font-size: 1.2em; color: orange;' : 'font-size: 1.2em; color: Lime;' }}"></i></td>
                        <td>{{ $equiTaggedMultimediaInteration->product_year }}</td>
                        <td>{{ $equiTaggedMultimediaInteration->purchase_date === NULL ? '' : (new DateTime($equiTaggedMultimediaInteration->purchase_date))->format('Y-m') }}</td>
                        <td>{{ $equiTaggedMultimediaInteration->expiration_date === NULL ? '' : (new DateTime($equiTaggedMultimediaInteration->expiration_date))->format('Y-m') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="container">
            <h3 class="mt-5">Vous ne pouvez pas accéder à cette page ! Gestionnaire seulement</h3>
        </div>
    @endif
@stop
@section('additionnalScript')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#table_media').DataTable({
                "order": [[ 0, 'asc' ]]
            });
        } );
    </script>
@stop
