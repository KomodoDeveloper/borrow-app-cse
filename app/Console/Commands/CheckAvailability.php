<?php

namespace App\Console\Commands;

use App\Models\Borrow;
use App\Models\Equipments;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Swift_Mailer;
use Swift_Message;
use Swift_SendmailTransport;

class CheckAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkavailability:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check every day which borrows start and switch the availabiliy setting';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function sendMailForInvalid($borrow)
    {
        $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
        $mailer = new Swift_Mailer($transport);
        $message = (new Swift_Message('Emprunt non validé !'))
            ->setFrom([Config::get('constants.mails.setFrom') => Config::get('constants.mails.defaultName')])
            ->setTo([$borrow->email_borrower => $borrow->surname_borrower])
            ->setBody('Bonjour '.$borrow->first_name_borrower.' '.$borrow->surname_borrower.PHP_EOL.
                PHP_EOL.'Votre emprunt pour '.$borrow->equipment->name.'. ID: '.$borrow->id.', n\'a pas été validé ! '.
                PHP_EOL.'Si vous le souhaitez, vous pouvez nous contacter à l\'adresse cse@unil.ch');
        $mailer->send($message);

    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $borrows = Borrow::all();
        $dateNow = new DateTime;
        $dateNow = $dateNow->format('Y-m-d');
        $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
        foreach ($borrows as $borrow) {
            $startDate = new DateTime($borrow->start_date);
            $startDate = $startDate->format('Y-m-d');
            $endDate = new DateTime($borrow->end_date);
            $endDate = $endDate->format('Y-m-d');
            if ($startDate <= $dateNow && $dateNow <= $endDate) {
                if ($borrow->status == 'validated' || $borrow->status == 'borrowed') {
                    $borrow->equipment->availability = 0;
                    $borrow->status = 'borrowed';
                    $borrow->save();
                    $borrow->equipment->save();
                    //$equipment = Equipments::find($borrow->equipment_id);
                    //$equipment->save();
                } elseif ($borrow->status == 'waiting_validation' && $dateNow != $startDate) {
                    //send Urgent email to manager to alert, there is a borrow's start date who could not start because the borrow isn't valid
                    //don't send alert mail if the current date is the same as start date. Assume it's a direct borrow from office, colleagues must validate at the same time.
                    //And it's also for multiple borrow functionnality, in order to avoid generating dozens of emails during a multiple borrow action
                    $borrow->equipment->availability = 1;
                    $borrow->equipment->save();
                    $mailer = new Swift_Mailer($transport);
                    $message = (new Swift_Message('Urgent : emprunt non validé commence'))
                        ->setFrom([Config::get('constants.mails.setFrom') => Config::get('constants.mails.defaultName')])
                        ->setTo(Config::get('constants.mails.admin'))
                        ->setBody('Attention'.PHP_EOL.PHP_EOL.'Un emprunt en attente de validation a dépassé sa date de départ : '.PHP_EOL.
                            'No Emprunt : '.$borrow->id.PHP_EOL.
                            'Matériel : '.$borrow->equipment->name.PHP_EOL.
                            'Date de départ prévue : '.$borrow->start_date.PHP_EOL.
                            PHP_EOL.'Veuillez ragerdez et vérifier rapidement dans l\'état des prêts le status de cette demande. https://cse-pret.unil.ch/admin');
                    // Send the message
                    $mailer->send($message);
                } elseif ($borrow->status == 'invalid') {
                    //$this->sendMailForInvalid($borrow); ca va envoyé tout le temps si le pret invalide est pas supprimé
                } elseif ($borrow->status == 'to_control') {
                    $borrow->equipment->availability = 0;
                    $borrow->equipment->save();
                }
            } else {
                if ($endDate < $dateNow) {
                    if ($borrow->status == 'to_control' || $borrow->status == 'invalid') {
                        // if borrow has "to_control" or "invalid" status, we don't change to finish
                    } else {
                        $borrow->status = 'finish';
                        $borrow->save();
                    }
                    $borrow->equipment->availability = 0;
                    $borrow->equipment->save();
                } elseif ($startDate > $dateNow and $borrow->status == 'validated' || $borrow->status == 'waiting_validation' || $borrow->status == 'invalid') {
                    //if we edit and update a borrow who has current and delay the start date, we need to check the availability of the equipment to also update if needed.
                    //it's usefull when we have only one borrow for an equipment and we update that borrow as written above
                    $borrowsForEquipment = Borrow::where('equipment_id', $borrow->equipment->id)->get();
                    $availiblityCount = 0;
                    foreach ($borrowsForEquipment as $borrowFE) {
                        if ($borrowFE->status == 'finish' || $borrowFE->status == 'to_control' || $borrowFE->status == 'borrowed') {
                            $availiblityCount += 1;
                        }
                    }

                    if ($availiblityCount >= 1) {
                        $borrow->equipment->availability = 0;
                    } else {
                        $borrow->equipment->availability = 1;
                    }
                    $borrow->equipment->save();
                    $borrow->save();
                } elseif ($borrow->status == 'invalid') {
                    //$this->sendMailForInvalid($borrow); ça va envoyé tout le temps si le pret invalide n'est supprimé
                }
            }
        }
    }
}
