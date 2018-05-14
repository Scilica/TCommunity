<?php 

// src/TC/WriterBundle/Controller/GamesController.php

namespace TC\WriterBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TC\WriterBundle\Entity\Games;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use TC\WriterBundle\Form\GamesType;
use TC\WriterBundle\Form\GamesEditType;

class GamesController extends Controller
{

	public function viewAction($jeux)
	{
		$jeux = str_replace("_", " ", $jeux);

		$em = $this->getDoctrine()->getManager();

		$monjeu = $em
		->getRepository("TCWriterBundle:Games")
		->FindThisGames($jeux)
		;

		$listNews = $em
    	->getRepository('TCWriterBundle:News')
    	->FindGameNews($jeux)
    	;

    	$listArticles = $em
    	->getRepository('TCWriterBundle:Articles')
    	->FindGameArticles($jeux)
    	;
    	

    	$listGuides = $em
      	->getRepository('TCWriterBundle:Guides')
      	->FindGameGuides($jeux)
      ;

		return $this->render('TCWriterBundle:Games:view.html.twig', array(
			'games' => $monjeu,
			'listNews' => $listNews,
			'listArticles' => $listArticles,
			'listGuides' => $listGuides,
			));
	}

		public function addAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$game = new Games();

		$form = $this->get('form.factory')->create(GamesType::class, $game);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->persist($game);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'jeu bien enregistré.');

				return $this->redirectToRoute('tc_admin_jeux');
			}
		
		return $this->render('TCWriterBundle:Games:add.html.twig', array(
			'form' => $form->createView(),
			));

	}

	public function editAction($jeux, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$jeux = str_replace("_", " ", $jeux);

		$existgame = $em
		->getRepository('TCWriterBundle:Games')
		->FindThisGames($jeux)
		;

		if (null == $existgame){
			throw new NotFoundHttpException("Le jeu n'existe pas");
		}

		else{
		$game = $em
		->getRepository("TCWriterBundle:Games")
		->findOneByTitle($jeux)
		;
		
		$form = $this->get('form.factory')->create(GamesEditType::class, $game);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Jeu bien modifier.');

				return $this->redirectToRoute('tc_admin_jeux_dashboard');
			}
		
		return $this->render('TCWriterBundle:Games:add.html.twig', array(
			'form' => $form->createView(),
			));

		}
	}

	public function deleteAction($jeux, Request $request)
	{
		$jeux = str_replace("_", " ", $jeux);

		$em = $this->getDoctrine()->getManager();

		$monjeu = $em
		->getRepository("TCWriterBundle:Games")
		->FindThisGames($jeux)
		;

		if (null == $monjeu){
			throw new NotFoundHttpException("Le jeu n'existe pas");
		}
		else{
		$jeu = $em
		->getRepository("TCWriterBundle:Games")
		->findOneByTitle($jeux);

		foreach ($jeu->getCategories() as $category) {
			$em->remove($category);
    		}
		
		
		$em->remove($jeu);
		$em->flush();
		 return $this->redirectToRoute('tc_admin_jeux');

		}
	}

	public function best_menuAction()
	{
		$em = $this->getDoctrine()->getManager();
		
		$bestGameslist = $em
		->getRepository('TCWriterBundle:Games')
		->FindBestGames()
		;

		return $this->render('TCWriterBundle:Games:best_menu.html.twig', array(
			'bestGameslist' => $bestGameslist
			));
	}

}