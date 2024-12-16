<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Equipments;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;
use Swift_Mailer;
use Swift_Message;
use Swift_SendmailTransport;

class BorrowController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkaai');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $equipments = Equipments::all();
        $equipments = $equipments->where('is_out_of_service', '!=', 1);

        return view('newborrow', [
            'equipments' => $equipments,
        ]);
    }

    public function customcreate($equipment_id): View
    {
        $equipment = Equipments::find($equipment_id);
        $existantBorrows = Borrow::where('equipment_id', $equipment_id)->get();
        $existantBorrows = $existantBorrows->sortBy('end_date');

        return view('newborrowcustom', [
            'equipment' => $equipment,
            'existantBorrows' => $existantBorrows,
        ]);
    }

    public function createmany(): View
    {
        $equipments = Equipments::all();
        $equipments = $equipments->where('is_out_of_service', '!=', 1);

        return view('newborrows', [
            'equipments' => $equipments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //check valididty of email adress
        if (! filter_var($request->input('email_borrower'), FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('dateError', 'email invalide');
        }
        //check if checkbox for contract approbation is checked (mandatory)
        if ($request->input('check_contract_borrower') != 'true') {
            return redirect()->back()->with('dateError', 'veuillez accepter les conditions');
        }
        //check if dates fields are empty
        if (empty($request->input('start_date'))) {
            return redirect()->back()->with('dateError', 'champs date de début vide');
        }
        if (empty($request->input('end_date'))) {
            return redirect()->back()->with('dateError', 'champs date de fin vide');
        }
        //check if the start date is later than the end date
        if ($request->input('start_date') > $request->input('end_date')) {
            return redirect()->back()->with('dateError', 'date de début plus tard que date de fin');
        }

        //check if dates are in competitions with others borrows
        $existantBorrows = Borrow::where('equipment_id', $request->input('equipment_id'))->get();
        foreach ($existantBorrows as $borrow) {
            if ($borrow->start_date <= $request->input('end_date') && $borrow->end_date >= $request->input('start_date')) {
                return redirect()->back()->with('dateError', 'dates invalide (autre emprunt touché)');
            }
        }

        $borrow = new Borrow;
        $borrow->first_name_borrower = $request->input('first_name_borrower');
        $borrow->surname_borrower = $request->input('surname_borrower');
        $borrow->email_borrower = $request->input('email_borrower');
        $borrow->equipment_id = $request->input('equipment_id');
        $borrow->registered_by = $request->input('registered_by');
        $borrow->handled_by = $request->input('handled_by');
        $borrow->reason = $request->input('reason');
        $borrow->start_date = $request->input('start_date');
        $borrow->end_date = $request->input('end_date');
        $borrow->status = 'waiting_validation';
        if ($request->input('need_explanation') == 'need') {
            $borrow->need_explanation = 1;
        } elseif ($request->input('need_explanation') == 'noneed') {
            $borrow->need_explanation = 0;
        }

        //dd($request->input('reason'));
        $borrow->save();

        //for the mail, save in variable the need_explanation choice
        $need_explanation_message = '';
        if ($borrow->need_explanation == 1) {
            $need_explanation_message = 'Je ne connais pas bien le matériel et aurais besoin d\'explications/conseils';
        } else {
            $need_explanation_message = 'Je connais le matériel et n\'aurais pas besoin d\'explications/conseils';
        }
        //send mail summary of my order to borrower
        /*
        $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
        $mailer = new Swift_Mailer($transport);
        $message = (new Swift_Message('Résumé de votre demande d\'emprunt de matériel auprès du CSE'))
            ->setFrom([Config::get('constants.mails.setFrom') => Config::get('constants.mails.defaultName')])
            ->setTo([$borrow->email_borrower => $borrow->surname_borrower])
            ->SetCc([Config::get('constants.mails.cc')])
            ->setBody('Bonjour ' . $borrow->first_name_borrower . ' ' . $borrow->surname_borrower . PHP_EOL .
                PHP_EOL .'Voici le récapitulatif de votre demande d\'emprunt de matériel auprès du CSE :' . PHP_EOL .
                'No emprunt : ' . $borrow->id . PHP_EOL .
                'Prénom et nom de l\'emprunteur :' . $borrow->first_name_borrower . ' ' . $borrow->surname_borrower . PHP_EOL .
                'Objet emprunté : ' . $borrow->equipment->name . PHP_EOL .
                'Motifs : ' . $borrow->reason . PHP_EOL .
                '' . $need_explanation_message . PHP_EOL .
                'Date de début : ' . $borrow->start_date . PHP_EOL .
                'Date de fin : ' . $borrow->end_date . PHP_EOL .
                'Statut de la demande : '. $borrow->status . PHP_EOL .
                'Consulter le status de votre emprunt ici : https://cse-pret.unil.ch/myborrows' . PHP_EOL . 'Le temps de traitement d\'une demande est peut prendre jusqu\'à 3 jours ouvrable.' . PHP_EOL . PHP_EOL .
                'Lieu et horaires de réception et restitution du matériel:' . PHP_EOL .
                'Bâtiment Anthropole, 2126' . PHP_EOL . 'Tous les jours de 09h00 à 11h30 et de 14h00 à 16h00 ou selon ce qu\'il sera convenu avec notre équipe.' . PHP_EOL . PHP_EOL .
                'Au besoin, vous pouvez contacter le CSE à l\'adresse cse@unil.ch pout tout complément d\'information ou de problème.' . PHP_EOL . PHP_EOL .
                'Ceci est un mail automatique, nous vous prions de ne pas y répondre.' . PHP_EOL . PHP_EOL .
                'Avec nos salutations les meilleures.' . PHP_EOL . PHP_EOL .
                '|||||||||||||||||||||||||||' . PHP_EOL . 'UNIL | Université de Lausanne' . PHP_EOL . 'Centre de Soutien à l\'Enseignement' . PHP_EOL .
                'Bâtiment Anthropole - bureau 2126' . PHP_EOL . 'CH-1015 Lausanne')
        ;
        $mailer->send($message);
        */

        //an other mail to alert the manager that there is a new request
        $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
        $mailer2 = new Swift_Mailer($transport);
        $message2 = (new Swift_Message('Nouvelle demande d\'emprunt de matériel'))
            ->setFrom([Config::get('constants.mails.setFrom') => Config::get('constants.mails.defaultName')])
            ->setTo(Config::get('constants.mails.admin'))
            ->setBody('Bonjour '.PHP_EOL.
                PHP_EOL.'Un nouvel emprunt de matériel a été créé'.PHP_EOL.
                'No emprunt : '.$borrow->id.PHP_EOL.
                'Prénom et nom de l\'emprunteur :'.$borrow->first_name_borrower.' '.$borrow->surname_borrower.PHP_EOL.
                'Objet emprunté : '.$borrow->equipment->name.PHP_EOL.
                'Motifs : '.$borrow->reason.PHP_EOL.
                ''.$need_explanation_message.PHP_EOL.
                'Date du : '.$borrow->start_date.' au '.$borrow->end_date.PHP_EOL.
                'Statut de la demande : '.$borrow->status.PHP_EOL.
                'Responsable de l’enregistrement de la demande du prêt : '.$borrow->registered_by.PHP_EOL.
                'Responsable de la gestion du prêt : '.$borrow->handled_by.PHP_EOL.
                PHP_EOL.'Merci de vérifier et de valider ou d\'invalider la demande !'.PHP_EOL.PHP_EOL.
                'Accédez à la gestion des prêts : https://cse-pret.unil.ch/stateofborrow'.PHP_EOL.PHP_EOL.
                'Ceci est un mail automatique envoyé par l\'application de prêt');
        $mailer2->send($message2);

        dispatch(function () {
            Artisan::call('checkavailability:daily');
        });

        return redirect()->route('home')->with('newBorrow', 'Demande d\'emprunt envoyé');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $borrow = Borrow::find($id);
        $existantBorrows = Borrow::where('equipment_id', $borrow->equipment_id)->get();
        $existantBorrows = $existantBorrows->sortBy('end_date');

        return view('editborrow', [
            'borrow' => $borrow,
            'existantBorrows' => $existantBorrows,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        //check valididty of email adress
        if (! filter_var($request->input('email_borrower'), FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('dateError', 'email invalide');
        }
        //check if checkbox for contract approbation is checked (mandatory)
        if ($request->input('check_contract_borrower') != 'true') {
            return redirect()->back()->with('dateError', 'veuillez accepter les conditions');
        }
        //check if dates fields are empty
        if (empty($request->input('start_date'))) {
            return redirect()->back()->with('dateError', 'champs date de début vide');
        }
        if (empty($request->input('end_date'))) {
            return redirect()->back()->with('dateError', 'champs date de fin vide');
        }
        //check if the start date is later than the end date
        if ($request->input('start_date') > $request->input('end_date')) {
            return redirect()->back()->with('dateError', 'date de début plus tard que date de fin');
        }

        $existantBorrows = Borrow::where('equipment_id', $request->input('equipment_id'))->where('id', '!=', $id)->get();
        foreach ($existantBorrows as $borrow) {
            if ($borrow->start_date <= $request->input('end_date') && $borrow->end_date >= $request->input('start_date')) {
                return redirect()->back()->with('dateError', 'dates invalide (autre emprunt touché)');
            }
        }

        $dateNow = new DateTime;
        $dateNow = $dateNow->format('Y-m-d');
        if ($request->input('status') == 'finish' && $request->input('end_date') > $dateNow) {
            return redirect()->back()->with('dateError', 'Incohérence :  end_date dans le futur et status \'finish\'');
        }
        if ($request->input('status') == 'borrowed' && $request->input('start_date') > $dateNow) {
            return redirect()->back()->with('dateError', 'Incohérence :  start_date dans le futur et status \'borrowed\', mettre \'validated\'');
        }
        if ($request->input('status') == 'to_control' && $request->input('start_date') > $dateNow) {
            return redirect()->back()->with('dateError', 'Incohérence :  start_date dans le futur et status \'to_control\'');
        }

        $borrow = Borrow::find($id);
        $borrow->first_name_borrower = $request->input('first_name_borrower');
        $borrow->surname_borrower = $request->input('surname_borrower');
        $borrow->email_borrower = $request->input('email_borrower');
        $borrow->status = $request->input('status');
        $borrow->equipment_id = $request->input('equipment_id');
        $borrow->registered_by = $request->input('registered_by');
        $borrow->handled_by = $request->input('handled_by');
        $borrow->reason = $request->input('reason');
        $borrow->start_date = $request->input('start_date');
        $borrow->end_date = $request->input('end_date');
        if ($request->input('need_explanation') == 'need') {
            $borrow->need_explanation = 1;
        } elseif ($request->input('need_explanation') == 'noneed') {
            $borrow->need_explanation = 0;
        }

        $borrow->save();

        dispatch(function () {
            Artisan::call('checkavailability:daily');
        });

        return redirect()->route('stateofborrow.index')->with('updateBorrow', 'Emprunt mis à jour');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storemany(Request $request): RedirectResponse
    {
        //check valididty of email adress
        if (! filter_var($request->input('email_borrower'), FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('dateError', 'email invalide');
        }
        //check if checkbox for contract approbation is checked (mandatory)
        if ($request->input('check_contract_borrower') != 'true') {
            return redirect()->back()->with('dateError', 'veuillez accepter les conditions');
        }
        //check if dates fields are empty
        if (empty($request->input('start_date'))) {
            return redirect()->back()->with('dateError', 'champs date de début vide');
        }
        if (empty($request->input('end_date'))) {
            return redirect()->back()->with('dateError', 'champs date de fin vide');
        }
        //check if the start date is later than the end date
        if ($request->input('start_date') > $request->input('end_date')) {
            return redirect()->back()->with('dateError', 'date de début plus tard que date de fin');
        }

        //extract equipments codes, find equipment relative in database and create a array of them
        $codesInRequest = $request->input('equipment_code');
        $codesArray = explode(',', $codesInRequest);
        $equipmentsArray = [];
        foreach ($codesArray as $code) {
            if (is_numeric($code)) {
                if (Equipments::where('code', '=', $code)->exists()) {
                    $equipemt = Equipments::where('code', '=', $code)->first();
                    $equipmentsArray[] = $equipemt;
                } else {
                    return redirect()->back()->with('dateError', 'un ou plusieurs codes (equipment) faux');
                }
            } else {
                return redirect()->back()->with('dateError', 'un ou plusieurs codes (equipment) faux, valeur doit être numérique');
            }
        }

        //foreach borrow, check if dates are in competitions with others borrows
        foreach ($equipmentsArray as $e) {
            $existantBorrows = Borrow::where('equipment_id', $e->id)->get();
            foreach ($existantBorrows as $borrow) {
                if ($borrow->start_date <= $request->input('end_date') && $borrow->end_date >= $request->input('start_date')) {
                    return redirect()->back()->with('dateError', 'dates invalide (autre emprunt touché)');
                }
            }
        }

        //dd($equipmentsArray);
        //create borrow for each equipment
        foreach ($equipmentsArray as $equipment) {
            $borrow = new Borrow;
            $borrow->first_name_borrower = $request->input('first_name_borrower');
            $borrow->surname_borrower = $request->input('surname_borrower');
            $borrow->email_borrower = $request->input('email_borrower');
            $borrow->equipment_id = $equipment->id;
            $borrow->registered_by = $request->input('registered_by');
            $borrow->handled_by = $request->input('handled_by');
            $borrow->reason = $request->input('reason');
            $borrow->start_date = $request->input('start_date');
            $borrow->end_date = $request->input('end_date');
            $borrow->status = 'waiting_validation';
            if ($request->input('need_explanation') == 'need') {
                $borrow->need_explanation = 1;
            } elseif ($request->input('need_explanation') == 'noneed') {
                $borrow->need_explanation = 0;
            }

            $borrow->save();

            //for the mail, save in variable the need_explanation choice
            $need_explanation_message = '';
            if ($borrow->need_explanation == 1) {
                $need_explanation_message = 'Je ne connais pas bien le matériel et aurais besoin d\'explications/conseils';
            } else {
                $need_explanation_message = 'Je connais le matériel et n\'aurais pas besoin d\'explications/conseils';
            }
            //send mail summary of my order to borrower
            /*
            $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
            $mailer = new Swift_Mailer($transport);
            $message = (new Swift_Message('Résumé de votre demande d\'emprunt de matériel auprès du CSE'))
                ->setFrom([Config::get('constants.mails.setFrom') => Config::get('constants.mails.defaultName')])
                ->setTo([$borrow->email_borrower => $borrow->surname_borrower])
                ->SetCc([Config::get('constants.mails.cc')])
                ->setBody('Bonjour ' . $borrow->first_name_borrower . ' ' . $borrow->surname_borrower . PHP_EOL .
                    PHP_EOL . 'Voici le récapitulatif de votre demande d\'emprunt de matériel auprès du CSE :' . PHP_EOL .
                    'No emprunt : ' . $borrow->id . PHP_EOL .
                    'Prénom et nom de l\'emprunteur :' . $borrow->first_name_borrower . ' ' . $borrow->surname_borrower . PHP_EOL .
                    'Objet emprunté : ' . $borrow->equipment->name . PHP_EOL .
                    'Motifs : ' . $borrow->reason . PHP_EOL .
                    '' . $need_explanation_message . PHP_EOL .
                    'Date de début : ' . $borrow->start_date . PHP_EOL .
                    'Date de fin : ' . $borrow->end_date . PHP_EOL .
                    'Statut de la demande : ' . $borrow->status . PHP_EOL .
                    'Consulter le status de votre emprunt ici : https://cse-pret.unil.ch/myborrows' . PHP_EOL . 'Le temps de traitement d\'une demande est peut prendre jusqu\'à 3 jours ouvrable.' . PHP_EOL . PHP_EOL .
                    'Lieu et horaires de réception et restitution du matériel:' . PHP_EOL .
                    'Bâtiment Anthropole, 2126' . PHP_EOL . 'Tous les jours de 09h00 à 11h30 et de 14h00 à 16h00 ou selon ce qu\'il sera convenu avec notre équipe.' . PHP_EOL . PHP_EOL .
                    'Au besoin, vous pouvez contacter le CSE à l\'adresse cse@unil.ch pout tout complément d\'information ou de problème.' . PHP_EOL . PHP_EOL .
                    'Ceci est un mail automatique, nous vous prions de ne pas y répondre.' . PHP_EOL . PHP_EOL .
                    'Avec nos salutations les meilleures.' . PHP_EOL . PHP_EOL .
                    '|||||||||||||||||||||||||||' . PHP_EOL . 'UNIL | Université de Lausanne' . PHP_EOL . 'Centre de Soutien à l\'Enseignement' . PHP_EOL .
                    'Bâtiment Anthropole - bureau 2126' . PHP_EOL . 'CH-1015 Lausanne');
            $mailer->send($message);
            */

            //an other mail to alert the manager that there is news requests
            $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
            $mailer2 = new Swift_Mailer($transport);
            $message2 = (new Swift_Message('Nouvelle demande d\'emprunt de matériel'))
                ->setFrom([Config::get('constants.mails.setFrom') => Config::get('constants.mails.defaultName')])
                ->setTo(Config::get('constants.mails.admin'))
                ->setBody('Bonjour '.PHP_EOL.
                    PHP_EOL.'Une nouvelle demande d\'emprunt de matériel a été effectuée'.PHP_EOL.
                    'No emprunt : '.$borrow->id.PHP_EOL.
                    'Prénom et nom de l\'emprunteur :'.$borrow->first_name_borrower.' '.$borrow->surname_borrower.PHP_EOL.
                    'Objet emprunté : '.$borrow->equipment->name.PHP_EOL.
                    'Motifs : '.$borrow->reason.PHP_EOL.
                    ''.$need_explanation_message.PHP_EOL.
                    'Date du : '.$borrow->start_date.' au '.$borrow->end_date.PHP_EOL.
                    'Statut de la demande : '.$borrow->status.PHP_EOL.
                    'Responsable de l’enregistrement de la demande du prêt : '.$borrow->registered_by.PHP_EOL.
                    'Responsable de la gestion du prêt : '.$borrow->handled_by.PHP_EOL.
                    PHP_EOL.'Merci de vérifier et de valider ou d\'invalider la demande !'.PHP_EOL.PHP_EOL.
                    'Accédez à la gestion des prêts : https://cse-pret.unil.ch/stateofborrow'.PHP_EOL.PHP_EOL.
                    'Ceci est un mail automatique envoyé par l\'application de prêt');
            $mailer2->send($message2);

            dispatch(function () {
                Artisan::call('checkavailability:daily');
            });
        }

        return redirect()->route('home')->with('newBorrow', 'Demande d\'emprunt envoyé');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
    }

    public function planned(Request $request): JsonResponse
    {
        $html = '';
        $existantBorrows = Borrow::where('equipment_id', $request->equipment_id)->get();
        $existantBorrows = $existantBorrows->sortBy('end_date');
        $html .= '<h6>Emprunts planifiés</h6>';
        foreach ($existantBorrows as $existantBorrow) {
            //$html .= '<p>'.$existantBorrow->id.'</p>';
            //$html .= '<p>'.$existantBorrow->start_date.'</p>';
            //$html .= '<p>'.$existantBorrow->end_date.'</p>';

            $html .= '<div class="card">
                        <div class="card-body">
                        <div class="row">
                            <div class="col">ID : '.$existantBorrow->id.'</div>
                        </div>
                        <div class="row">
                            <div class="col">début:</div>
                            <div class="col">fin:</div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <i class="far fa-calendar-alt"></i> '.$existantBorrow->start_date.'
                            </div>
                            <div class="col">
                                <i class="far fa-calendar-alt"></i> '.$existantBorrow->end_date.'
                            </div>
                        </div>
                        </div>
                    </div>';

        }

        return response()->json(['html' => $html]);
    }
}
