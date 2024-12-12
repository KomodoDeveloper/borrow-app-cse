@extends('layouts.app')
@section('additionnalCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pickadate/default.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pickadate/default.date.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('css/selectsearch/chosen.css') }}" >
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
                <form method="POST" action="{{ route('borrow.update', $borrow->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label for="first_name_borrower">Prénom </label>
                        <input name="first_name_borrower" type="text" class="form-control @error('first_name_borrower') is-invalid @enderror" id="first_name_borrower" value="{{ $borrow->first_name_borrower }}" required>
                        @error('first_name_borrower')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="surname_borrower">Nom</label>
                        <input name="surname_borrower" type="text" class="form-control @error('surname_borrower') is-invalid @enderror" id="surname_borrower" value="{{ $borrow->surname_borrower }}" required>
                        @error('surname_borrower')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email_borrower">Email</label>
                        <input name="email_borrower" type="text" class="form-control @error('email_borrower') is-invalid @enderror" id="email_borrower" value="{{ $borrow->email_borrower }}" required>
                        @error('email_borrower')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" id="status">
                            <option value="waiting_validation" {{ $borrow->status == 'waiting_validation' ? 'selected' : '' }}>waiting validation</option>
                            <option value="validated" {{ $borrow->status == 'validated' ? 'selected' : '' }}>validated</option>
                            <option value="invalid" {{ $borrow->status == 'invalid' ? 'selected' : '' }}>not validated</option>
                            <option value="borrowed" {{ $borrow->status == 'borrowed' ? 'selected' : '' }}>borrowed</option>
                            <option value="finish" {{ $borrow->status == 'finish' ? 'selected' : '' }}>finish</option>
                            <option value="to_control" {{ $borrow->status == 'to_control' ? 'selected' : '' }}>to control</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-5">
                                <label for="equipment_id">Equipement (id db)</label>
                            </div>
                            <div class="col-2">
                                <input name='equipment_id' class="form-control @error('equipment_id') is-invalid @enderror" id="equipment_id" value="{{ $borrow->equipment_id }}" readonly>
                            </div>
                            <div class="col-5">
                                <label for="equipment_id"><i>{{ $borrow->equipment->name }}</i></label>
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
                        <input name="registered_by" type="text" class="form-control @error('registered_by') is-invalid @enderror" id="registered_by" value="{{ $borrow->registered_by }}" required>
                        @error('registered_by')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="handled_by">Responsable de la gestion du prêt</label>
                        <input name="handled_by" type="text" class="form-control @error('handled_by') is-invalid @enderror" id="handled_by" value="{{ $borrow->handled_by }}">
                        @error('handled_by')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="reason">Motifs</label>
                        <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" id="reason" required>{{ $borrow->reason }}</textarea>
                        @error('reason')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="need_explanation" id="explanation" value="need" {{ $borrow->need_explanation == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="explanation">
                                Je ne connais pas bien le matériel et aurais besoin d’explications/conseils
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="need_explanation" id="noexplanation" value="noneed" {{ $borrow->need_explanation == 0 ? 'checked' : '' }}>
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
                            <input name="start_date" type="text" class="datepicker" id="datepicker1" value="{{ $borrow->start_date }}">
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col">
                            <label for="datepicker2">Date de fin</label>
                        </div>
                        <div class="col">
                            <input name="end_date" type="text" class="datepicker" id="datepicker2" value="{{ $borrow->end_date }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="check_contract_borrower" name="check_contract_borrower" value="true" checked required>
                            <label class="form-check-label" for="check_contract_borrower"> Je déclare avoir lu, compris et accepter les conditions énoncées dans le contrat de prêt <a href="{{ asset('otherfiles/Contrat_pret.pdf') }}" target="_blank" rel="noopener noreferrer">(lien sur le contrat)</a>. Le contrat entrera en vigueur à réception du matériel de prêt. </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1 mb-3">Enregistrer</button>
                </form>
            </div>
            <div class="col col-lg-4">
                <h6>Emprunts planifiés</h6>

                @foreach($existantBorrows as $existantBorrow)
                    <div class="card {{ $borrow->id == $existantBorrow->id ? 'border-success' : '' }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    ID : {{ $existantBorrow->id }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    début:
                                </div>
                                <div class="col">
                                    fin:
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <i class="far fa-calendar-alt"></i> {{ $existantBorrow->start_date }}
                                </div>
                                <div class="col">
                                    <i class="far fa-calendar-alt"></i> {{ $existantBorrow->end_date }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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

        //$(".chosen-select").chosen()
    </script>
@stop

