<?php

namespace App\Console\Commands;

use App\Models\Borrow;
use DateInterval;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Swift_Mailer;
use Swift_Message;
use Swift_SendmailTransport;

class CheckEndBorrow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkendborrow:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to the borrower when the end date of this borrow is near';

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
        foreach ($borrows as $borrow){
            $dateWarning = new DateTime($borrow->end_date);
            $dateWarning->sub(new DateInterval('P2D'));
            $dateWarning = $dateWarning->format('Y-m-d');
            $dateEnd = new DateTime($borrow->end_date);
            $dateEnd = $dateEnd->format('Y-m-d');
            $dateNow = new DateTime();
            $dateNow = $dateNow->format('Y-m-d');
            if($dateWarning <= $dateNow && $dateNow <= $dateEnd && $borrow->status != "to_control" && $borrow->status != "invalid"){
                //sendmail & SwiftMail
                $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
                // Create the Mailer using your created Transport
                $mailer = new Swift_Mailer($transport);
                // Create a message
                $message = (new Swift_Message('L\'échéance de votre emprunt de matériel auprès du CSE approche'))
                    ->setFrom([Config::get('constants.mails.setFrom') => Config::get('constants.mails.defaultName')])
                    ->setTo([$borrow->email_borrower => $borrow->surname_borrower])
                    ->setBody('Bonjour ' . $borrow->first_name_borrower . ' ' . $borrow->surname_borrower . PHP_EOL . PHP_EOL .
                            'Votre emprunt de matériel auprès du CSE concernant le matériel mentionné ci-dessous arrive à son terme dans moins de 2 jours. Date de fin : ' . $borrow->end_date . PHP_EOL .
                            'No emprunt : ' . $borrow->id . PHP_EOL .
                            'Matériel : ' . $borrow->equipment->name . PHP_EOL . PHP_EOL .
                            'Nous vous prions de bien vouloir retourner le matériel emprunté dans les délais' . PHP_EOL . PHP_EOL .
                            'Lieu et horaires de réception et restitution du matériel:' . PHP_EOL .
                            'Bâtiment Anthropole, 2126' . PHP_EOL . 'Tous les jours de 09h00 à 11h30 et de 14h00 à 16h00 ou selon ce qu\'il sera convenu avec notre équipe.' . PHP_EOL . PHP_EOL .
                            'Au besoin, vous pouvez contacter le CSE à l\'adresse cse@unil.ch' . PHP_EOL . PHP_EOL .
                            'Ceci est un mail automatique, nous vous prions de ne pas y répondre' . PHP_EOL . PHP_EOL .
                            'Avec nos salutations les meilleures.' . PHP_EOL . PHP_EOL .
                            '|||||||||||||||||||||||||||' . PHP_EOL . 'UNIL | Université de Lausanne' . PHP_EOL . 'Centre de Soutien à l\'Enseignement' . PHP_EOL .
                            'Bâtiment Anthropole - bureau 2126' . PHP_EOL . 'CH-1015 Lausanne');
                // Send the message
                $mailer->send($message);
            }
        }
    }
}
