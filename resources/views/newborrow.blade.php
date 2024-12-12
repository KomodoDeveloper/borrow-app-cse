@extends('layouts.app')
@section('additionnalCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pickadate/default.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pickadate/default.date.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('css/selectsearch/chosen.css') }}" >
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@stop
@section('content')
    <div class="container mb-5">
        <div class="row justify-content-md-center mt-3">
            <div class="col col-lg-3">
                @include('layouts.infocardschedule')
            </div>
            <div class="col-lg-5">
                @if(session()->has('dateError'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        {{ session()->get('dateError') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('borrow.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="first_name_borrower">Prénom </label>
                        <input name="first_name_borrower" type="text" class="form-control @error('first_name_borrower') is-invalid @enderror" id="first_name_borrower" placeholder="Prénom" required>
                        @error('first_name_borrower')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="surname_borrower">Nom</label>
                        <input name="surname_borrower" type="text" class="form-control @error('surname_borrower') is-invalid @enderror" id="surname_borrower" placeholder="Nom" required>
                        @error('surname_borrower')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email_borrower">Email</label>
                        <input name="email_borrower" type="text" class="form-control @error('email_borrower') is-invalid @enderror" id="email_borrower" required>
                        @error('email_borrower')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="equipment_id">Equipement</label>
                            </div>
                            <div class="col">
                                <select name='equipment_id' class="chosen-select @error('equipment_id') is-invalid @enderror" id="equipment_id">
                                    @foreach($equipments as $equipment)
                                        <option value="{{ $equipment->id }}">{{ $equipment->code }}-{{ $equipment->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @error('equipment_id')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="registered_by">Responsable de l'enregistrement de la demande du prêt</label>
                        <input name="registered_by" type="text" class="form-control @error('registered_by') is-invalid @enderror" id="registered_by" value="{{ Auth::user()->name }}" readonly>
                        @error('registered_by')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="handled_by">Responsable de la gestion du prêt</label>
                        <input name="handled_by" type="text" class="form-control @error('handled_by') is-invalid @enderror" id="handled_by">
                        @error('handled_by')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="reason">Motifs</label>
                        <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" id="reason" placeholder="Motifs de la demande d'emprunt" required></textarea>
                        @error('reason')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="need_explanation" id="explanation" value="need" checked>
                            <label class="form-check-label" for="explanation">
                                Je ne connais pas bien le matériel et aurais besoin d’explications/conseils
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="need_explanation" id="noexplanation" value="noneed">
                            <label class="form-check-label" for="noexplanation">
                                Je connais le matériel et n’aurais pas besoin d’explications/conseils
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="datepicker1">Date de début</label>
                        </div>
                        <div class="col">
                            <input name="start_date" type="text" class="datepicker" id="datepicker1">
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col">
                            <label for="datepicker2">Date de fin</label>
                        </div>
                        <div class="col">
                            <input name="end_date" type="text" class="datepicker" id="datepicker2">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="check_contract_borrower" name="check_contract_borrower" value="true" required>
                            <label class="form-check-label" for="check_contract_borrower"> Je déclare avoir lu, compris et accepté les conditions énoncées dans le contrat de prêt <a href="{{ asset('otherfiles/Contrat_pret.pdf') }}" target="_blank" rel="noopener noreferrer">(lien du contrat)</a>. Le contrat entrera en vigueur dès réception du matériel de prêt. </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1 mb-3">Envoyer</button>
                </form>
            </div>
            <div class="col col-lg-4" id="plannedBorrows">
            </div>
        </div>
    </div>
@stop
@section('additionnalScript')
    <script src="{{ asset('javascript/pickadate/picker.js') }}"></script>
    <script src="{{ asset('javascript/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('javascript/pickadate/fr_FR.js') }}"></script>
    <script src="{{ asset('javascript/selectsearch/chosen.jquery.js') }}"></script>
    <script src="{{ asset('javascript/selectsearch/chosen.proto.js') }}"></script>
    <script>
        $('.datepicker').pickadate({
            format: 'yyyy-mm-dd',
            clear: '',
        });

        $(".chosen-select").chosen()

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#equipment_id").change(function(e){
            var equipment_id = e.target.value;
            $.ajax({
                url: "{{ route('borrow.planned') }}",
                type: 'POST',
                data: {
                    equipment_id: equipment_id,
                },
                success: function(data) {
                    $('#plannedBorrows').html(data.html);
                }
            });
        });
    </script>
@stop

