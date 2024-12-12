@extends('layouts.app')
@section('additionnalCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/equipment.css') }}" >
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@stop
@section('content')
    <div class="container mt-3 mb-5">
        @if(session()->has('deleteBorrow'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                {{ session()->get('deleteBorrow') }}
            </div>
        @endif
        @if(count($myborrows) < 1)
            <div class="mt-5">
                <div class="alert alert-warning" role="alert">
                    Aucun emprunt en cours.
                </div>
            </div>
        @else
            @foreach ($myborrows as $b)
                <div class="card border-dark my-1">
                    <div class="card-header font-weight-bold">
                        Emprunt No : {{ $b->id}}
                    </div>
                    <div class="card-body" id="card{{$b->id}}">
                        <div class="row">
                            <div class="col-lg-5">
                                <h5 class="card-title">{{ $b->first_name_borrower }} {{ $b->surname_borrower }}</h5>
                            </div>
                            <div class="col-lg-3">
                                <h6 class="card-title">{{ $b->email_borrower }}</h6>
                            </div>
                            <div class="col-lg-3">
                                <h6 class="card-title status">status : <strong>{{ $b->status}}</strong></h6>
                            </div>
                            <div class="col-lg-1">
                                @if (Auth::user()->is_cse_member == 1)
                                    @if($b->status == 'waiting_validation')
                                        <button type="button" class="btn btn-success btn-sm validation" data-borrowid="{{ $b->id }}" data-validity="true"><i class="fas fa-clipboard-check"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm validation" data-borrowid="{{ $b->id }}" data-validity="false"><strong>x</strong></button>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-5">
                                <img class="imgBorrow" src="/storage/equipments_images/{{ $b->equipment->image }}" alt="">
                            </div>
                            <div class="col-lg-7">
                                <div class="row mb-1">
                                    Equipment emprunté : <strong>{{ $b->equipment->code }} - {{ $b->equipment->name }}</strong>
                                </div>
                                <div class="row mb-3">
                                    Description : {{ $b->equipment->description }}
                                </div>
                                <div class="row mb-1">
                                    Motifs : {{ $b->reason }}
                                </div>
                                <div class="row mb-1">
                                    @if($b->need_explanation == 1)
                                        Je ne connais pas bien le matériel et aurais besoin d’explications/conseils
                                    @elseif($b->need_explanation == 0)
                                        Je connais le matériel et n’aurais pas besoin d’explications/conseils
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-5">
                            </div>
                            <div class="col-lg-3 d-flex align-items-center">
                                Début : {{ $b->start_date }}
                            </div>
                            <div class="col-lg-2 d-flex align-items-center">
                                Fin : {{ $b->end_date }}
                            </div>
                            @if (Auth::user()->is_admin === 1)
                            <div class="col-lg-1">
                                <a href="{{ route('stateofborrow.return', $b->id) }}" class="btn btn-primary">Rendu</a>
                            </div>
                            <div class="col-lg-1 align-self-center text-right">
                                <a href="{{ route('stateofborrow.delete', $b->id) }}" class="btn btn-outline-dark btn-sm mr-1"><i class="far fa-trash-alt"></i></a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@stop
@section('additionnalScript')
    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.validation').click(function() {
                var borrowid = $(this).data('borrowid');
                var validity = $(this).data('validity');
                var status = "card" + borrowid;

                $.ajax({
                    url: "{{ route('stateofborrow.updateavailability') }}",
                    method: 'PUT',
                    data: {
                        borrowid: borrowid,
                        validity: validity,
                    },
                    success: function(data) {
                        $('#'+status+' .status').html(data.html);
                    }
                });
            });
        });
    </script>
@stop

