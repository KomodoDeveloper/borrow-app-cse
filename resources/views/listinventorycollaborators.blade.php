@extends('layouts.app')
@section('additionnalCSS')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
@stop
@section('content')
    @if (Auth::user()->is_admin == 1)
        <div class="container">
            <h3 class="mt-4 mb-3">Inventaire par collaborateur</h3>
            <div class="row mb-3">
                <div class="col-lg-6">
                    <h6><i class="fas fa-circle" style="font-size: 1.2em; color: orange;"></i> Objets avec date d'expiration dans moins de 6 mois : <b>{{ $countLessThanSixMonthsBeforeExpiration }}</b></h6>
                </div>
                <div class="col-lg-6">
                    <h6><i class="fas fa-circle" style="font-size: 1.2em; color: red;"></i> Objets expirés : <b>{{ $countExpiredEquipment }}</b></h6>
                </div>
            </div>
        </div>
        <div class="mx-3 mb-5">
            <table id="table_id" class="display">
                <thead>
                    <tr>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Code</th>
                        <th>No Ci</th>
                        <th>Nom de l'objet</th>
                        <th>Année</th>
                        <th>Date d'achat</th>
                        <th>Date d'expiration</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allInventoryCollaborators as $inventoryIteration)
                    <tr>
                        <td>{{ $inventoryIteration->first_name_borrower}}</td>
                        <td>{{ $inventoryIteration->surname_borrower }}</td>
                        <td>{{ $inventoryIteration->email }}</td>
                        <td>{{ $inventoryIteration->start_date }}</td>
                        <td>{{ $inventoryIteration->end_date }}</td>
                        <td>{{ $inventoryIteration->code }}</td>
                        <td>{{ $inventoryIteration->ci_number }}</td>
                        <td>{{ $inventoryIteration->name }}</td>
                        <td>{{ $inventoryIteration->product_year }}</td>
                        <td>{{ $inventoryIteration->purchase_date === NULL ? '' : (new DateTime($inventoryIteration->purchase_date))->format('Y-m') }}</td>
                        <td>{{ $inventoryIteration->expiration_date === NULL ? '' : (new DateTime($inventoryIteration->expiration_date))->format('Y-m') }}</td>
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
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#table_id').DataTable({
                "order": [[ 2, 'asc' ]]
            });
        } );
    </script>
@stop
