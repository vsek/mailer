<?php

namespace App\Email;

use Nette\SmartObject;

/**
 * Description of Mailer
 *
 * @author Vsek
 */
class Mailer{
    use SmartObject;
    
    /**
     * \Nette\Mail\IMailer
     */
    protected $mailer;
    
    /**
     *
     * @var \App\Model\EmailLog
     */
    protected $model;
    
    public function __construct(\Nette\Mail\IMailer $mailer, \App\Model\EmailLog $model) {
        $this->mailer = $mailer;
        $this->model = $model;
    }
    
    public function send(Mail $mail){
        try{
            $this->mailer->send($mail);
            $this->model->insert(array(
                'adress' => implode(',', $mail->getAddress()),
                'text' => $mail->getText(),
                'subject' => $mail->getSubject(),
            ));
        }catch(\Exception $e){
            $this->model->insert(array(
                'adress' => implode(',', $mail->getAddress()),
                'text' => $mail->getText(),
                'subject' => $mail->getSubject(),
                'error' => $e->getMessage(),
            ));
            throw $e;
        }
    }
}
