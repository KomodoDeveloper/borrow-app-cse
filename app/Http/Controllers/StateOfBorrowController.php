<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\ArchiveBorrow;
use App\Models\Borrow;
use App\Models\Equipments;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Swift_Mailer;
use Swift_Message;
use Swift_SendmailTransport;

class StateOfBorrowController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkaai');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $borrowsNotSorted = Borrow::all();
        $borrows = $borrowsNotSorted->where('status', '!=', 'to_control')->sortBy('end_date');

        return view('stateofborrow', [
            'borrows' => $borrows,
        ]);
    }

    public function tocontrolindex(): View
    {
        $borrowsToControl = Borrow::where('status', 'to_control')->get();
        $borrowsToControl = $borrowsToControl->sortBy('end_date');

        return view('tocontrol', [
            'borrowsToControl' => $borrowsToControl,
        ]);

    }

    public function returnBorrow($borrow_id): RedirectResponse
    {
        $borrow = Borrow::find($borrow_id);
        $borrow->status = 'to_control';
        $borrow->save();

        //sendmail & SwiftMail
        $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);
        // Create a message
        $message = (new Swift_Message('Confirmation du retour de votre emprunt de matériel auprès du CSE'))
            ->setFrom([Config::get('constants.mails.setFrom') => Config::get('constants.mails.defaultName')])
            ->setTo([$borrow->email_borrower => $borrow->surname_borrower])
            ->setBody('Bonjour '.$borrow->first_name_borrower.' '.$borrow->surname_borrower.PHP_EOL.PHP_EOL.
                'Nous accusons réception du matériel suivant et vous en remercions :'.PHP_EOL.
                'No emprunt : '.$borrow->id.PHP_EOL.
                'Matériel : '.$borrow->equipment->name.PHP_EOL.PHP_EOL.
                'Nous allons procéder au contrôle du matériel dans les prochains jours et reviendrons vers vous si nécessaire.'.PHP_EOL.PHP_EOL.
                'Le Centre de Soutien à l\'Enseignement reste à votre entière disposition pour tout emprunt de matériel. N\'hésitez pas à nous contacter à l\'adresse cse@unil.ch .'.PHP_EOL.PHP_EOL.
                'Avec nos salutations les meilleures.'.PHP_EOL.PHP_EOL.
                'Ceci est un mail automatique, nous vous prions de ne pas y répondre'.PHP_EOL.PHP_EOL.
                '|||||||||||||||||||||||||||'.PHP_EOL.'UNIL | Université de Lausanne'.PHP_EOL.'Centre de Soutien à l\'Enseignement'.PHP_EOL.
                'Bâtiment Anthropole - bureau 2126'.PHP_EOL.'CH-1015 Lausanne');
        // Send the message
        $mailer->send($message);

        return redirect()->back()->with('deleteBorrow', 'Emprunt rendu et à controler');

    }

    public function archivedBorrow($borrow_id): RedirectResponse
    {
        $borrow = Borrow::find($borrow_id);
        //$equipment = Equipments::find($borrow->equipment_id);
        //$equipment->availability = 1;
        //$equipment->save();
        //$dateTmp = new DateTime();
        //$realEndDate = new DateTime($borrow->end_date);
        //$realEndDate = $realEndDate->format('Y-m-d');
        //$dateTmp->sub(new DateInterval('P1D'));
        //$dateTmp = $dateTmp->format('Y-m-d');
        //$borrow->end_date = $dateTmp;
        $borrow->status = 'finish';
        $borrow->equipment->availability = 1;
        $borrow->equipment->save();
        //$borrow->save();

        //reset the original end date after the command call
        //$borrow->end_date = $realEndDate;
        //$borrow->save();

        //Create the borrow archive
        $archiveBorrow = new ArchiveBorrow;
        $archiveBorrow->a_first_name_borrower = $borrow->first_name_borrower;
        $archiveBorrow->a_surname_borrower = $borrow->surname_borrower;
        $archiveBorrow->a_email_borrower = $borrow->email_borrower;
        $archiveBorrow->a_equipment_id = $borrow->equipment_id;
        $archiveBorrow->a_start_date = $borrow->start_date;
        $archiveBorrow->a_end_date = $borrow->end_date;
        $archiveBorrow->a_status = $borrow->status;
        $archiveBorrow->origin_id = $borrow->id;

        $archiveBorrow->save();

        //delete the borrow when the archive borrow is save.
        $borrow->delete();

        dispatch(function () {
            Artisan::call('checkavailability:daily');
        });

        return redirect()->back()->with('deleteBorrow', 'Emprunt archivé');
    }

    public function updateAvailability(Request $request): JsonResponse
    {
        $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
        $mailer = new Swift_Mailer($transport);
        $html = '';
        $borrow = Borrow::find($request->borrowid);
        if ($request->validity == 'true') {
            $borrow->status = 'validated';
            $borrow->save();
            $html .= 'status : <strong>validated</strong>';
            $message = (new Swift_Message('Confirmation d\'emprunt de matériel auprès du CSE'))
                ->setFrom([Config::get('constants.mails.setFrom') => Config::get('constants.mails.defaultName')])
                ->setTo([$borrow->email_borrower => $borrow->surname_borrower])
                ->SetCc([Config::get('constants.mails.cc')])
                ->setBody('Bonjour '.$borrow->first_name_borrower.' '.$borrow->surname_borrower.PHP_EOL.PHP_EOL.
                    'Nous vous remercions et vous confirmons votre emprunt de matériel ci-dessous du CSE : '.PHP_EOL.
                    'No emprunt : '.$borrow->id.PHP_EOL.
                    'Matériel : '.$borrow->equipment->name.PHP_EOL.
                    'Période du prêt : du '.$borrow->start_date.' au '.$borrow->end_date.PHP_EOL.
                    'Statut de la demande : '.$borrow->status.PHP_EOL.
                    'Consulter le status de votre emprunt ici : https://cse-pret.unil.ch/myborrows'.PHP_EOL.PHP_EOL.
                    'Nous vous transmettons également le lien vers le contrat de prêt que vous avez déclaré avoir lu, compris et accepté lors de la réception du matériel : '.asset('otherfiles/Contrat_pret.pdf').
                    ' . Celui-ci étant est en vigueur dès la réception du matériel, vous pouvez néanmoins nous solliciter pour tout complément d\'information dans les plus brefs délais. '.PHP_EOL.PHP_EOL.
                    'Lieu et horaires de réception et restitution du matériel:'.PHP_EOL.
                    'Bâtiment Anthropole, 2126'.PHP_EOL.'Tous les jours de 09h00 à 11h30 et de 14h00 à 16h00 ou selon ce qu\'il sera convenu avec notre équipe.'.PHP_EOL.PHP_EOL.
                    'Pour plus d\'information, vous pouvez contacter le CSE à l\'adresse cse@unil.ch'.PHP_EOL.PHP_EOL.
                    'Ceci est un mail automatique, nous vous prions de ne pas y répondre.'.PHP_EOL.PHP_EOL.
                    'Avec nos salutations les meilleures.'.PHP_EOL.PHP_EOL.
                    '|||||||||||||||||||||||||||'.PHP_EOL.'UNIL | Université de Lausanne'.PHP_EOL.'Centre de Soutien à l\'Enseignement'.PHP_EOL.
                    'Bâtiment Anthropole - bureau 2126'.PHP_EOL.'CH-1015 Lausanne');
            $mailer->send($message);

        } elseif ($request->validity == 'false') {
            $borrow->status = 'invalid';
            $borrow->save();
            $html .= 'status : <strong>invalid</strong>';
            $message = (new Swift_Message('Demande d\'emprunt de matériel auprès du CSE non validé !'))
                ->setFrom([Config::get('constants.mails.setFrom') => Config::get('constants.mails.defaultName')])
                ->setTo([$borrow->email_borrower => $borrow->surname_borrower])
                ->SetCc([Config::get('constants.mails.cc')])
                ->setBody('Bonjour '.$borrow->first_name_borrower.' '.$borrow->surname_borrower.PHP_EOL.PHP_EOL.
                    'Votre demande d\'emprunt de matériel auprès du CSE concernant le matériel mentionné ci-dessous n\'a pas été validé : '.PHP_EOL.
                    'No emprunt : '.$borrow->id.PHP_EOL.
                    'Matériel : '.$borrow->equipment->name.PHP_EOL.PHP_EOL.
                    'Pour plus d\'information, vous pouvez contacter le CSE à l\'adresse cse@unil.ch'.PHP_EOL.PHP_EOL.
                    'Ceci est un mail automatique, nous vous prions de ne pas y répondre'.PHP_EOL.PHP_EOL.
                    'Avec nos salutations les meilleures.'.PHP_EOL.PHP_EOL.
                    '|||||||||||||||||||||||||||'.PHP_EOL.'UNIL | Université de Lausanne'.PHP_EOL.'Centre de Soutien à l\'Enseignement'.PHP_EOL.
                    'Bâtiment Anthropole - bureau 2126'.PHP_EOL.'CH-1015 Lausanne');
            $mailer->send($message);

        }
        dispatch(function () {
            Artisan::call('checkavailability:daily');
        });

        return response()->json(['html' => $html]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        //delete a borrow without archive (in case ouf we create a borrow with error)
        $borrow = Borrow::find($id);
        //$dateTmp = new DateTime();
        //$realEndDate = new DateTime($borrow->end_date);
        //$realEndDate = $realEndDate->format('Y-m-d');
        //$dateTmp->sub(new DateInterval('P1D'));
        //$dateTmp = $dateTmp->format('Y-m-d');
        //$borrow->end_date = $dateTmp;
        $borrow->equipment->availability = 1;
        $borrow->equipment->save();
        //$borrow->save();
        //ancienne place du dispatch funtion

        //reset the original end date after the command call
        //$borrow->end_date = $realEndDate;
        //$borrow->save();

        //delete the borrow
        $borrow->delete();

        dispatch(function () {
            Artisan::call('checkavailability:daily');
        });

        return redirect()->back()->with('deleteBorrow', 'Emprunt supprimé');
    }
}
