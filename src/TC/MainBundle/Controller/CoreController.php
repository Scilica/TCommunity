<?php

namespace TC\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CoreController extends Controller
{
    public function menuAction()
    {
    	$em = $this->getDoctrine()->getManager();

    	$listGames = $em
        ->getRepository('TCWriterBundle:Games')
        ->findAll();
    	
        return $this->render('TCMainBundle:Core:menu.html.twig', array(
        	// controler passe les variables.
        	'listGames' => $listGames
        	));
    }

    public function menubackofficeAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listGames = $em
        ->getRepository('TCWriterBundle:Games')
        ->findAll();
        
        return $this->render('TCMainBundle:Core:menu_backoffice.html.twig', array(
            // controler passe les variables.
            'listGames' => $listGames));
    }


}
