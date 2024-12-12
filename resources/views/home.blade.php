@extends('layouts.app')
@section('additionnalCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/homepage.css') }}" >
@stop
@section('content')
    <div class="container mb-5">
        @if(session()->has('newBorrow'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                {{ session()->get('newBorrow') }}
            </div>
        @endif
        <div class="row">

            <div class="col-lg-3">
                <h1 class="my-4">Emprunt matériel</h1>
                <div class="list-group">
                    @foreach ($categories as $category)
                    <a href="{{ route('catalog.getCategory', $category->id) }}" class="list-group-item">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
            <!-- /.col-lg-3 -->

            <div class="col-lg-9">

                <div class="mt-4 card">
                    <div class="card-body">
                        Le CSE mets à disposition des collaboratrices et collaborateurs de l’Université de Lausanne divers matériels audiovisuels
                        (caméras, micros, etc. …). Le matériel peut être emprunté pour une durée limitée en fonction du stock à disposition <b>afin de répondre à
                        des besoins particuliers (enseignement, projet pédagogiques, projet d’étude, etc.)</b>. Pour les étudiant·e·s, le prêt est ouvert uniquement avec l’aval
                        de l’enseignant·e responsable en vue de la réalisation d’un travail académique.
                    </div>
                </div>
                <div class="mt-2 card border-info">
                    <div class="card-body">
                        Notre catalogue est à votre disposition dans l'onglet "Inventaire". Si vous êtes intéressé par un ou plusieurs équipements, merci d'envoyer un email
                        à <b>cse@unil.ch</b> avec le/les numéro(s) d'inventaire des objets (chiffre commençant par 100***), et d'indiquer le motif ainsi que les dates
                        de début et de fin souhaitées afin que nous puissions traiter votre demande.
                    </div>
                </div>
                <div class="card border-info mt-2">
                    <div class="card-body">
                        <p>Le CSE se tient à votre disposition pour vous guider sur le choix du matériel adapté à vos besoins. N'hésitez pas à nous <a href="{{ route('contact') }}">contacter</a>.</p>
                        <p>Lieu et horaires de réception et restitution du matériel:</p>
                        <p>Bâtiment Anthropole, 2126<br>Tous les jours de 09h00 à 11h30 et de 14h00 à 16h00 ou selon ce qu'il sera convenu avec notre équipe.</p>
                        <p>Le délai de traitement d'une demande peut prendre jusqu'à 3 jours ouvrables.</p>
                    </div>
                </div>

                <div class="mt-3 container text-center">
                    <h5>Micro Rode NT-USB mini disponible au secrétariat du CSE</h5>
                </div>

                <div class="container">
                    <div class="mt-2 mb-4 text-center">
                        <img class="img-thumbnail" src="/storage/equipments_images/{{ $rodeMicForExample->image }}" alt="">
                    </div>
                </div>
                <!-- /.container-->

            </div>
            <!-- /.col-lg-9 -->

        </div>
        <!-- /.row -->
    </div>
@stop
