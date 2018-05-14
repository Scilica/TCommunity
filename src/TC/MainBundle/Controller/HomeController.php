<?php

namespace TC\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HomeController extends Controller
{
    public function indexAction()
    {
      $em = $this->getDoctrine()->getManager();
    
    $bestGameslist = $em
    ->getRepository('TCWriterBundle:Games')
    ->FindBestGames()
    ;


    $listNews = $em
    ->getRepository('TCWriterBundle:News')
    ->FindNews(6)
    ;

    $listArticles = $em
    ->getRepository('TCWriterBundle:Articles')
    ->FindArticles(4)
    ;
    	

    	$listGuides = $em
      ->getRepository('TCWriterBundle:Guides')
      ->FindGuides(4)
      ;
    	

        return $this->render('TCMainBundle:Homepage:index.html.twig', array(
        	'listNews' => $listNews,
            'listArticles' => $listArticles,
            'listGuides' => $listGuides,
            'bestGameslist' => $bestGameslist,
        	));
    }



}
