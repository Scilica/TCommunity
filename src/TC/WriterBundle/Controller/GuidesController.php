<?php 

// src/TC/WriterBundle/Controller/GuidesController.php

namespace TC\WriterBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TC\WriterBundle\Entity\Guides;
use Symfony\Component\HttpFoundation\Request;
use TC\WriterBundle\Entity\Games;
use TC\WriterBundle\Entity\Tag;
use TC\WriterBundle\Entity\Category;
use TC\WriterBundle\Form\GuidesType;
use TC\WriterBundle\Form\GuidesEditType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class GuidesController extends Controller
{
	public function indexAction($page, Request $request)
	{

		if ($page < 1) {
		throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
		}

		$nbPerPage = 10;
		// je recupère toutes mes news 

		$em = $this->getDoctrine()->getManager();

		$listGuides = $em
		->getRepository('TCWriterBundle:Guides')
		->FindAllGuides($page, $nbPerPage)
		;

		$nbPages = ceil(count($listGuides) / $nbPerPage);

		if ($page > $nbPages){
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

		// je lie mes news a mes jeux
		foreach ($listGuides as $guide) {
			$guide->getGame();
		}	    

		$form = $this->get('form.factory')->createBuilder(FormType::class)
		->add('tag', EntityType::class, array(
            'class' => 'TCWriterBundle:Tag',
            'placeholder' => '-- Veuillez choisir un tag --',
            'choice_label' => 'title',
            'multiple' => false,))
        ->getForm();

        $forme = $this->get('form.factory')->createBuilder(FormType::class)
		->add('game', EntityType::class, array(
            'class' => 'TCWriterBundle:Games',
            'placeholder' => '-- Veuillez choisir un jeu --',
            'choice_label' => 'title',
            'multiple' => false,))
        ->getForm();
		
		 if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		
     $data = $request->request->get("form");
      $tag = $em
      ->getRepository('TCWriterBundle:Tag')
      ->findOneById($data['tag'])
      ;
      $date = $tag->getTitle();
      $date = str_replace(" ", "_", $date);
      $date = str_replace("é", "e", $date);
       return $this->redirectToRoute('tc_writer_guides_filtred', array('tag' => $date));
		}

		 if ($request->isMethod('POST') && $forme->handleRequest($request)->isValid()) {
		
     $data = $request->request->get("form");
      $tag = $em
      ->getRepository('TCWriterBundle:Games')
      ->findOneById($data['game'])
      ;
      $date = $tag->getTitle();
      $date = str_replace(" ", "_", $date);
      $date = str_replace("é", "e", $date);
       return $this->redirectToRoute('tc_writer_guides_game_filtred', array('jeux' => $date));
		} 	    

		return $this->render('TCWriterBundle:Guides:index.html.twig', array(
			'listGuides' => $listGuides,
			'nbPages' => $nbPages,
			'form' => $form->createView(),
			'forme' => $forme->createView(),
			'page' => $page,
			));
	}

	public function viewAction($jeux,$category,$id)
	{
		$jeux = str_replace("_", " ", $jeux);
		$category = str_replace("_", " ", $category);
		$category = str_replace("-", "'", $category);

		   

		$em = $this->getDoctrine()->getManager();

		$monguide = $em
		->getRepository("TCWriterBundle:Guides")
		->FindThisGuide($jeux, $category, $id)
		;

		$otherguides = $em
		->getRepository("TCWriterBundle:Guides")
		->FindOtherGuides($jeux, $id)
		;

		if (null == $monguide){
			throw new NotFoundHttpException("L'annonce ou le jeu que vous recherchiez n'existe pas !!");
		}

		foreach ($monguide as $guide){
			$guide->getGame();
			$this->get('views_counter.views_counter')->count($guide);
		}

		return $this->render('TCWriterBundle:Guides:view.html.twig', array(
			'monguide' => $monguide,
			'otherguides' => $otherguides,
			));
	}

	public function categoryAction($jeux)
	{
		$jeux = str_replace("_", " ", $jeux);

		$em = $this->getDoctrine()->getManager();

		$games = $em
		->getRepository("TCWriterBundle:Games")
		->FindThisGames($jeux)
		;

		if (null == $games){
			throw new NotFoundHttpException("L'annonce ou le jeu que vous recherchiez n'existe pas !!");
		}

		$categories_game = $em
		->getRepository("TCWriterBundle:Category")
		->FindCategoryForThisGame($jeux)
		;

		return $this->render('TCWriterBundle:Guides:catview.html.twig', array(
			'categories' => $categories_game,
			'games' => $games,
			));
	}

	public function filtredAction($tag, $page, Request $request)
	{
		$tag = str_replace("_", " ", $tag);

		if ($page < 1) {
		throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
		}

		$nbPerPage = 10;

		$em = $this->getDoctrine()->getManager();

		$listGuides = $em
		->getRepository('TCWriterBundle:Guides')
		->FindFiltredGuides($tag, $page, $nbPerPage)
		;

		$nbPages = ceil(count($listGuides) / $nbPerPage);

		if ($page > $nbPages){
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

		// je lie mes news a mes jeux
		foreach ($listGuides as $guide) {
			$guide->getGame();
		}

		$form = $this->get('form.factory')->createBuilder(FormType::class)
		->add('tag', EntityType::class, array(
            'class' => 'TCWriterBundle:Tag',
            'placeholder' => '-- Veuillez choisir un tag --',
            'choice_label' => 'title',
            'multiple' => false,))
        ->getForm();

        $forme = $this->get('form.factory')->createBuilder(FormType::class)
		->add('game', EntityType::class, array(
            'class' => 'TCWriterBundle:Games',
            'placeholder' => '-- Veuillez choisir un jeu --',
            'choice_label' => 'title',
            'multiple' => false,))
        ->getForm();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		
     $data = $request->request->get("form");
      $tag = $em
      ->getRepository('TCWriterBundle:Tag')
      ->findOneById($data['tag'])
      ;
      $date = $tag->getTitle();
      $date = str_replace(" ", "_", $date);
      $date = str_replace("é", "e", $date);
       return $this->redirectToRoute('tc_writer_guides_filtred', array('tag' => $date));
		}

		 if ($request->isMethod('POST') && $forme->handleRequest($request)->isValid()) {
		
     $data = $request->request->get("form");
      $tag = $em
      ->getRepository('TCWriterBundle:Games')
      ->findOneById($data['game'])
      ;
      $date = $tag->getTitle();
      $date = str_replace(" ", "_", $date);
      $date = str_replace("é", "e", $date);
       return $this->redirectToRoute('tc_writer_guides_game_filtred', array('jeux' => $date));
		} 	    

		return $this->render('TCWriterBundle:Guides:filtred.html.twig', array(
			'listguides' => $listGuides,
			'nbPages' => $nbPages,
			'form' => $form->createView(),
			'forme' => $forme->createView(),
			'page' => $page,
			'tag' => $tag,
			));
	}

	public function addAction($jeux, Request $request)
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

		$guide = new Guides();

		$form = $this->get('form.factory')->create(GuidesType::class, $guide, ['Game' => $jeux]);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->persist($guide);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'guide bien enregistré.');

				return $this->redirectToRoute('tc_writer_guides_view', array('id' => $guide->getId(), 'jeux' => $guide->getGame()));
			}
		
		return $this->render('TCWriterBundle:Guides:add.html.twig', array(
			'form' => $form->createView(),
			));
	}

	public function bygamefiltredAction($jeux, $page, Request $request)
	{
		$jeux = str_replace("_", " ", $jeux);

		if ($page < 1) {
		throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
		}

		$nbPerPage = 10;

		$em = $this->getDoctrine()->getManager();

		$listGuides = $em
		->getRepository('TCWriterBundle:Guides')
		->FindFiltredGuidesByGame($jeux, $page, $nbPerPage)
		;

		$nbPages = ceil(count($listGuides) / $nbPerPage);

		if ($page > $nbPages){
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

		// je lie mes news a mes jeux
		foreach ($listGuides as $guide) {
			$guide->getGame();
		}

		$form = $this->get('form.factory')->createBuilder(FormType::class)
		->add('category', EntityType::class, array(
            'class' => 'TCWriterBundle:Category',
             'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use($jeux) {
        return $er->FindCategoryForThisGameForm($jeux);},
            'choice_label' => 'title',
            'placeholder' => '-- Veuillez choisir une catégorie --',
            'multiple' => false,))
        ->getForm();

        $forme = $this->get('form.factory')->createBuilder(FormType::class)
		->add('game', EntityType::class, array(
            'class' => 'TCWriterBundle:Games',
            'placeholder' => '-- Veuillez choisir un jeu --',
            'choice_label' => 'title',
            'multiple' => false,))
        ->getForm();
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		
     $data = $request->request->get("form");
      $tag = $em
      ->getRepository('TCWriterBundle:Category')
      ->findOneById($data['category'])
      ;
      $date = $tag->getTitle();
      $date = str_replace(" ", "_", $date);
      $date = str_replace("é", "e", $date);
      $jeux = str_replace(" ", "_", $jeux);
       return $this->redirectToRoute('tc_writer_guides_category_filtred', array('jeux' => $jeux, 'category' => $date));
		}

		 if ($request->isMethod('POST') && $forme->handleRequest($request)->isValid()) {
		
     $data = $request->request->get("form");
      $tag = $em
      ->getRepository('TCWriterBundle:Games')
      ->findOneById($data['game'])
      ;
      $date = $tag->getTitle();
      $date = str_replace(" ", "_", $date);
      $date = str_replace("é", "e", $date);
       return $this->redirectToRoute('tc_writer_guides_game_filtred', array('jeux' => $date));
		} 

		return $this->render('TCWriterBundle:Guides:bygamefiltred.html.twig', array(
			'listguides' => $listGuides,
			'nbPages' => $nbPages,
			'page' => $page,
			'form' => $form->createView(),
			'forme' => $forme->createView(),
			'jeux' => $jeux
			));
	}

	public function bycategoryfiltredAction($jeux, $category,$page, Request $request)
	{
		$jeux = str_replace("_", " ", $jeux);
		$category = str_replace("_", " ", $category);
		$category = str_replace("-", "'", $category);

		if ($page < 1) {
		throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
		}

		$nbPerPage = 10;

		$em = $this->getDoctrine()->getManager();

		$listGuides = $em
		->getRepository('TCWriterBundle:Guides')
		->FindFiltredGuidesByCategory($jeux,$category, $page, $nbPerPage)
		;

		$nbPages = ceil(count($listGuides) / $nbPerPage);

		if ($page > $nbPages){
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

		// je lie mes news a mes jeux
		foreach ($listGuides as $guide) {
			$guide->getGame();
		}

		$form = $this->get('form.factory')->createBuilder(FormType::class)
		->add('category', EntityType::class, array(
            'class' => 'TCWriterBundle:Category',
             'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use($jeux) {
        return $er->FindCategoryForThisGameForm($jeux);},
            'choice_label' => 'title',
            'placeholder' => '-- Veuillez choisir une catégorie --',
            'multiple' => false,))
        ->getForm();

        $forme = $this->get('form.factory')->createBuilder(FormType::class)
		->add('game', EntityType::class, array(
            'class' => 'TCWriterBundle:Games',
            'placeholder' => '-- Veuillez choisir un jeu --',
            'choice_label' => 'title',
            'multiple' => false,))
        ->getForm();
		
		 if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		
     $data = $request->request->get("form");
      $tag = $em
      ->getRepository('TCWriterBundle:Category')
      ->findOneById($data['category'])
      ;
      $date = $tag->getTitle();
      $date = str_replace(" ", "_", $date);
      $date = str_replace("é", "e", $date);
      $jeux = str_replace(" ", "_", $jeux);
       return $this->redirectToRoute('tc_writer_guides_category_filtred', array('jeux' => $jeux, 'category' => $date));
		}

		 if ($request->isMethod('POST') && $forme->handleRequest($request)->isValid()) {
		
     $data = $request->request->get("form");
      $tag = $em
      ->getRepository('TCWriterBundle:Games')
      ->findOneById($data['game'])
      ;
      $date = $tag->getTitle();
      $date = str_replace(" ", "_", $date);
      $date = str_replace("é", "e", $date);
       return $this->redirectToRoute('tc_writer_guides_game_filtred', array('jeux' => $date));
		} 	    

		return $this->render('TCWriterBundle:Guides:bycategoryfiltred.html.twig', array(
			'listguides' => $listGuides,
			'nbPages' => $nbPages,
			'form' => $form->createView(),
			'forme' => $forme->createView(),
			'page' => $page,
			'jeux' => $jeux
			));
	}
	
	public function editAction($jeux, $id, $category, Request $request)
	{
		$jeux = str_replace("_", " ", $jeux);
		$category = str_replace("_", " ", $category);
		$category = str_replace("-", "'", $category);

		$em = $this->getDoctrine()->getManager();

		$guide = $em
		->getRepository("TCWriterBundle:Guides")
		->Find($id)
		;
		$form = $this->get('form.factory')->create(GuidesEditType::class, $guide, ['Game' => $jeux]);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'guide bien modifier.');

				 return $this->redirectToRoute('tc_admin_writer_list_guides');
			}
		
		return $this->render('TCWriterBundle:Guides:add.html.twig', array(
			'form' => $form->createView(),
			));

		
	}
	
	public function deleteAction($jeux, $id,$category, Request $request)
	{
		$jeux = str_replace("_", " ", $jeux);
		$category = str_replace("_", " ", $category);
		$category = str_replace("-", "'", $category);

		$em = $this->getDoctrine()->getManager();

		$monguide = $em
		->getRepository("TCWriterBundle:Guides")
		->FindThisGuideAdmin($jeux, $category, $id)
		;

		if (null == $monguide){
			throw new NotFoundHttpException("L'annonce ou le jeu que vous recherchiez n'existe pas !!");
		}
		else{
		$guide = $em
		->getRepository("TCWriterBundle:Guides")
		->Find($id);

		$em->remove($guide);
		$em->flush();
		 return $this->redirectToRoute('tc_admin_writer_list_guides');

		}
	}
	
}