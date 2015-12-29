<?php
namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Bundle\TwigBundle\TwigEngine as Templating;

class EmailService
{
    private $container;
    private $templating;

    public function __construct(Container $container, Templating $templating)
    {
        $this->container  = $container;
        $this->templating = $templating;
    }

    public function send($emails, $template, $subject = 'Уведомление')
    {
        $mail = new \PHPMailer();

        $portal = 'Портал nevrologia.info';

        $mail->isSMTP();
        $mail->isHTML(true);
        $mail->CharSet  = 'UTF-8';
        $mail->FromName = $portal;
        $mail->Subject  = $subject;
        $mail->Host     = '127.0.0.1';
        $mail->From     = 'noreply@evrika.ru';

        if ($this->container->getParameter('kernel.environment') == 'dev') {
            $mail->Host       = 'smtp.gmail.com';
            $mail->From       = 'binacy@gmail.com';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;
            $mail->SMTPAuth   = true;
            $mail->Username   = 'binacy@gmail.com';
            $mail->Password   = '2q32q3q2';
        }

        # устанавливаем содержимое письма
        $templateParams = array('portal' => $portal);
        if (is_string($template)) {
            $templateName = $template;
        }
        else {
            $templateName   = $template[0];
            $templateParams = array_merge($templateParams, $template[1]);
        }

        $mail->addCustomHeader('List-id', $templateName);
        $mail->Body = $this->templating->render($templateName, $templateParams);

        # устанавливаем получателя(ей) письма
        if (is_string($emails)) {
            $mail->addAddress($emails);
        }
        else {
            foreach ($emails as $email) {
                $mail->addAddress($email);
            }
        }

        # если в настройках мы установили флажок тестирования (не отправлять)
        if ($this->container->hasParameter('no_email')) {
            return;
        }

        $mail->send();
    }
}