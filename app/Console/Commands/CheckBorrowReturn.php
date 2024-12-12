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

class CheckBorrowReturn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkborrowreturn:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check each borrow end_date and if it is two day in past (before date now). Send mail to manager and borrower to alert that borrow is finish but the equipment is not return';

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
        $dateLate = new DateTime();
        $dateLate->sub(new DateInterval('P2D'));
        $dateLate = $dateLate->format('Y-m-d');
        foreach ($borrows as $borrow) {
            $dateEnd= new DateTime($borrow->end_date);
            $dateEnd = $dateEnd->format('Y-m-d');
            if ($dateEnd <= $dateLate && $borrow->status != "to_control" && $borrow->status != "invalid") {
                //the borrow is not yet return and this end_date is finish a least two day ago
                $mailer = new Swift_Mailer($transport);
                $message = (new Swift_Message('Emprunt fini non rendu !'))
                    ->setFrom([Config::get('constants.mails.setFrom') => Config::get('constants.mails.defaultName')])
                    ->setTo(Config::get('constants.mails.admin'))
                    ->setBody('Bonjour ' . PHP_EOL . PHP_EOL .
                        'L\'emprunt mentionné ci-dessus est terminé (statut finish) depuis au moins 2 jours !' . PHP_EOL .
                        'No emprunt : ' . $borrow->id . PHP_EOL .
                        'Matériel : ' . $borrow->equipment->name . PHP_EOL .
                        PHP_EOL . 'Cependant, Celui-ci n\'a toujours pas été retourné au CSE ou l\'emprunt n\'a pas été rendu dans l\'admin -> états des prêts ' .
                        PHP_EOL . PHP_EOL . 'Controlez sur le site SVP (https://cse-pret.unil.ch/admin) ou ' .
                        'prenez contact avec l\'emprunteur : ' . $borrow->first_name_borrower . ' ' . $borrow->surname_borrower . ' ' . $borrow->email_borrower)
                ;
                $mailer->send($message);

                $mailer2 = new Swift_Mailer($transport);
                $message2 = (new Swift_Message('Délai d\'emprunt du matériel auprès du CSE échu'))
                    ->setFrom([Config::get('constants.mails.setFrom') => Config::get('constants.mails.defaultName')])
                    ->setTo([$borrow->email_borrower => $borrow->surname_borrower])
                    ->setBody('Bonjour ' . $borrow->first_name_borrower . ' ' . $borrow->surname_borrower . PHP_EOL . PHP_EOL .
                        'Le délai de votre emprunt de matériel auprès du CSE concernant le matériel mentionné ci-dessous est arrivé à échéance depuis plus de 2 jours! Date de fin : ' . $borrow->end_date . PHP_EOL .
                        'No emprunt : ' . $borrow->id . PHP_EOL .
                        'Matériel : ' . $borrow->equipment->name . PHP_EOL . PHP_EOL .
                        'Nous vous remercions de bien vouloir retourner le matériel concerné dans les plus brefs délais.' . PHP_EOL . PHP_EOL .
                        'Lieu et horaires de réception et restitution du matériel:' . PHP_EOL .
                        'Bâtiment Anthropole, 2126' . PHP_EOL . 'Tous les jours de 09h00 à 11h30 et de 14h00 à 16h00 ou selon ce qu\'il sera convenu avec notre équipe.' . PHP_EOL . PHP_EOL .
                        'Au besoin, vous pouvez contacter le CSE à l\'adresse cse@unil.ch' . PHP_EOL . PHP_EOL .
                        'Ceci est un mail automatique, nous vous prions de ne pas y répondre' . PHP_EOL . PHP_EOL .
                        'Avec nos salutations les meilleures.' . PHP_EOL . PHP_EOL .
                        '|||||||||||||||||||||||||||' . PHP_EOL . 'UNIL | Université de Lausanne' . PHP_EOL . 'Centre de Soutien à l\'Enseignement' . PHP_EOL .
                        'Bâtiment Anthropole - bureau 2126' . PHP_EOL . 'CH-1015 Lausanne');
                $mailer2->send($message2);

            }
        }
    }
}
