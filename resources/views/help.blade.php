@extends('layouts.app')
@section('content')
    <div class="container">

        <!--Section: Contact v.2-->
        <section class="mb-4">

            <!--Section heading-->
            <h2 class="h1-responsive font-weight-bold text-center mt-4 mb-5">Comment fonctionne le site ?</h2>

            <div class="container">
                <h6 class="my-2" style="font-variant: small-caps">Onglets du menu</h6>

                <dl class="row">
                    <dt class="col-sm-2">Home</dt>
                    <dd class="col-sm-10">les 6 dernières nouveautés ajoutées sur le site</dd>

                    <dt class="col-sm-2">Inventaire </dt>
                    <dd class="col-sm-10">
                        <p>Tout les objets disponibles à l'emprunt</p>
                        <p>Les catégories sur la gauche de la page vous permette d'affiner votre recherche</p>
                        <ul>
                            <li>Pour faire une demande d'emprunt, cliquez sur l'objet désiré et ensuite sur "nouveau prêt"</li>
                            <li>Dans la page de demande d'emprunt, entrez les informations demandées (nom, prénom, email, date de début de l'emprunt, date de fin). Sur la droite, vous pouvez voir les autres emprunts planifiés pour cet objet </li>
                            <li>Une fois la formulaire complété, cliquez sur "Envoyer"</li>
                            <li>Un mail de résumé va vous parvenir et votre emprunt devra obtenir l'approbation du personnel en charge des emprunts au CSE</li>
                        </ul>
                    </dd>
                    <dt class="col-sm-2">Mes emprunts</dt>
                    <dd class="col-sm-10">
                        <p>Page de résumé de mes emprunts en cours</p>
                        <p>le <b>statut</b> indique l'état de votre demande</p>
                    </dd>
                    <dt class="col-sm-2">Contact</dt>
                    <dd class="col-sm-10">Page avec les informations de contact</dd>
                </dl>

            </div>

        </section>
        <!--Section: Contact v.2-->
    </div>
@stop
