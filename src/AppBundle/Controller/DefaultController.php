<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if ($request->getMethod() === 'POST'){
            $email = htmlspecialchars($request->request->get('email'));
            $fio = htmlspecialchars($request->request->get('fio'));
            $txt = htmlspecialchars($request->request->get('txt'));
            $number = htmlspecialchars($request->request->get('number'));


            $message = \Swift_Message::newInstance()
                ->setSubject('Новогоднее поздравление')
                ->setFrom('noreply@evrika.ru')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'AppBundle:default:'.$number.'.html.twig',
                        array(
                            'fio'     => $fio,
                            'txt'     => $txt,
                        )
                    ),
                    'text/html'
                );


            return $this->render('AppBundle:default:index.html.twig', ['post' => true]);
        }

        // replace this example code with whatever you need
        return $this->render('AppBundle:default:index.html.twig', ['post' => false]);
    }
}
