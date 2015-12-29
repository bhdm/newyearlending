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


            $this->get('email.service')->send(
                $email,
                array('LearningMainBundle:default:1.html.twig', array(
                    'fio'     => $fio,
                    'txt'     => $txt,
                )),
                'Открытка с сайта Неврология.инфо'
            );


        }

        // replace this example code with whatever you need
        return $this->render('AppBundle:default:index.html.twig');
    }
}
