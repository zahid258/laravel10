<?php
    namespace App\Mail;

    use Illuminate\Bus\Queueable;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;

    class LikesCountEmail extends Mailable
    {
        use Queueable, SerializesModels;

        public $names;

        public function __construct(array $names)
        {
            $this->names = $names;
        }


        public function build()
        {
            return $this->view('emails.likes_count')
                ->with(['names' => $this->names]);
        }
    }

    ?>
