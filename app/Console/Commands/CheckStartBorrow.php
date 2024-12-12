<?php

namespace App\Console\Commands;

use App\Models\Borrow;
use DateInterval;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Swift_Mailer;
use Swift_Message;
use Swift_SendmailTransport;

class CheckStartBorrow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkstartborrow:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check each borrow and send mail one day before the start of them';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $borrows = Borrow::all();
        $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
        foreach ($borrows as $borrow){
            $dateWarning = new DateTime($borrow->start_date);
            $dateWarning->sub(new DateInterval('P1D'));
            $dateWarning = $dateWarning->format('Y-m-d');
            $dateNow = new DateTime();
            $dateNow = $dateNow->format('Y-m-d');
            if($dateWarning == $dateNow && $borrow->status != 'invalid'){
                //sendmail to borrower to alert him that his borrow start in one day (tomorow)
                $mailer = new Swift_Mailer($transport);
                $message = (new Swift_Message('Début de votre emprunt de matériel auprès du CSE dans un jour'))
                    ->setFrom([Config::get('constants.mails.setFrom') => Config::get('constants.mails.defaultName')])
                    ->setTo([$borrow->email_borrower => $borrow->surname_borrower])
                    ->setBody('Bonjour ' . $borrow->first_name_borrower . ' ' . $borrow->surname_borrower . PHP_EOL . PHP_EOL .
                        'Votre emprunt de matériel auprès du CSE concernant le matériel mentionné ci-dessous débute dans 1 jour. Date de début : ' . $borrow->start_date . PHP_EOL .
                        'No emprunt : ' . $borrow->id . PHP_EOL .
                        'Matériel : ' . $borrow->equipment->name . PHP_EOL . PHP_EOL .
                        'Nous vous remercions de bien vouloir venir chercher le matériel mentionné.' . PHP_EOL . PHP_EOL .
                        'Lieu et horaires de réception et restitution du matériel:' . PHP_EOL .
                        'Bâtiment Anthropole, 2126' . PHP_EOL . 'Tous les jours de 09h00 à 11h30 et de 14h00 à 16h00 ou selon ce qu\'il sera convenu avec notre équipe.' . PHP_EOL . PHP_EOL .
                        'Pour plus d\'information, vous pouvez contacter le CSE à l\'adresse cse@unil.ch' . PHP_EOL . PHP_EOL .
                        'Ceci est un mail automatique, nous vous prions de ne pas y répondre' . PHP_EOL . PHP_EOL .
                        'Avec nos salutations les meilleures.' . PHP_EOL . PHP_EOL .
                        '|||||||||||||||||||||||||||' . PHP_EOL . 'UNIL | Université de Lausanne' . PHP_EOL . 'Centre de Soutien à l\'Enseignement' . PHP_EOL .
                        'Bâtiment Anthropole - bureau 2126' . PHP_EOL . 'CH-1015 Lausanne');
                $mailer->send($message);

                //send mail to manager to alert him that a borrow will start soon (in one day)
                $message = (new Swift_Message('Début d\'emprunt prochainement'))
                    ->setFrom([Config::get('constants.mails.setFrom') => Config::get('constants.mails.defaultName')])
                    ->setTo(Config::get('constants.mails.admin'))
                    ->setBody('Bonjour ' . PHP_EOL . PHP_EOL .
                        'L\' emprunt suivant va commencer dans 1 jour. Date de début : ' . $borrow->start_date . PHP_EOL .
                        'No emprunt : ' . $borrow->id . PHP_EOL .
                        'Matériel : ' . $borrow->equipment->name . PHP_EOL .
                        'Attention : vérifier que la demande soit validée.');
                $mailer->send($message);
            }
        }
    }
}
