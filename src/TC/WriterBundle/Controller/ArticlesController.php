<?php 

// src/TC/WriterBundle/Controller/ArticlesController.php

namespace TC\WriterBundle\Controller;

use TC\WriterBundle\Entity\Articles;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use TC\WriterBundle\Entity\Games;
use TC\WriterBundle\Entity\Tag;
use TC\WriterBundle\Form\ArticlesType;
use TC\WriterBundle\Form\ArticlesEditType;
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

class ArticlesController extends Controller
{
	public function indexAction($page, Request $request)
	{

		if ($page < 1) {
		throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
		}

		$nbPerPage = 10;
		// je recupère toutes mes news 

		$em = $this->getDoctrine()->getManager();

		$listArticles = $em
		->getRepository('TCWriterBundle:Articles')
		->FindAllArticles($page, $nbPerPage)
		;

		$nbPages = ceil(count($listArticles) / $nbPerPage);

		if ($page > $nbPages){
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

		// je lie mes news a mes jeux
		foreach ($listArticles as $article) {
			$article->getGame();
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
       return $this->redirectToRoute('tc_writer_articles_filtred', array('tag' => $date));
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
       return $this->redirectToRoute('tc_writer_articles_game_filtred', array('jeux' => $date));
		} 	    

		return $this->render('TCWriterBundle:Articles:index.html.twig', array(
			'listArticles' => $listArticles,
			'form' => $form->createView(),
			'forme' => $forme->createView(),
			'nbPages' => $nbPages,
			'page' => $page,
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

		$listArticles = $em
		->getRepository('TCWriterBundle:Articles')
		->FindFiltredArticles($tag, $page, $nbPerPage)
		;

		$nbPages = ceil(count($listArticles) / $nbPerPage);

		if ($page > $nbPages){
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

		// je lie mes news a mes jeux
		foreach ($listArticles as $article) {
			$article->getGame();
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
       return $this->redirectToRoute('tc_writer_articles_filtred', array('tag' => $date));
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
       return $this->redirectToRoute('tc_writer_articles_game_filtred', array('jeux' => $date));
		} 	    

		return $this->render('TCWriterBundle:Articles:filtred.html.twig', array(
			'listarticles' => $listArticles,
			'nbPages' => $nbPages,
			'form' => $form->createView(),
			'forme' => $forme->createView(),
			'page' => $page,
			'tag' => $tag,
			));
	}

	public function viewAction($jeux, $id)
	{
		$jeux = str_replace("_", " ", $jeux);

		$em = $this->getDoctrine()->getManager();

		$monarticle = $em
		->getRepository("TCWriterBundle:Articles")
		->FindThisArticle($jeux, $id)
		;

		$otherarticles = $em
		->getRepository("TCWriterBundle:Articles")
		->FindOtherArticles($jeux, $id)
		;

		if (null == $monarticle){
			throw new NotFoundHttpException("L'annonce ou le jeu que vous recherchiez n'existe pas !!");
		}

		foreach ($monarticle as $article){
			$article->getTag();
			$this->get('views_counter.views_counter')->count($article);
		}
		

		return $this->render('TCWriterBundle:Articles:view.html.twig', array(
			'monarticle' => $monarticle,
			'jeux' => $jeux,
			'otherarticles' => $otherarticles
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

		$listArticles = $em
		->getRepository('TCWriterBundle:Articles')
		->FindFiltredArticlesByGame($jeux, $page, $nbPerPage)
		;

		$nbPages = ceil(count($listArticles) / $nbPerPage);

		if ($page > $nbPages){
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

		// je lie mes news a mes jeux
		foreach ($listArticles as $article) {
			$article->getGame();
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
       return $this->redirectToRoute('tc_writer_articles_filtred', array('tag' => $date));
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
       return $this->redirectToRoute('tc_writer_articles_game_filtred', array('jeux' => $date));
		} 

		return $this->render('TCWriterBundle:Articles:bygamefiltred.html.twig', array(
			'listarticles' => $listArticles,
			'nbPages' => $nbPages,
			'page' => $page,
			'form' => $form->createView(),
			'forme' => $forme->createView(),
			'jeux' => $jeux
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
		$article = new Articles();

		$form = $this->get('form.factory')->create(ArticlesType::class, $article, ['Game' => $jeux]);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->persist($article);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'article bien enregistré.');

				return $this->redirectToRoute('tc_writer_articles_view', array('id' => $article->getId(), 'jeux' => $article->getGame()));
			}
		
		return $this->render('TCWriterBundle:Articles:add.html.twig', array(
			'form' => $form->createView(),
			));
		}

	public function editAction($jeux, $id, Request $request)
	{
		$jeux = str_replace("_", " ", $jeux);

		$em = $this->getDoctrine()->getManager();

		
		$article = $em
		->getRepository("TCWriterBundle:Articles")
		->Find($id)
		;

		$form = $this->get('form.factory')->create(ArticlesEditType::class, $article, ['Game' => $jeux]);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'article bien modifier.');

				 return $this->redirectToRoute('tc_admin_writer_list_articles');
			}
		
		return $this->render('TCWriterBundle:Articles:add.html.twig', array(
			'form' => $form->createView(),
			));

		
	}

	public function deleteAction($jeux, $id, Request $request)
	{
		$jeux = str_replace("_", " ", $jeux);

		$em = $this->getDoctrine()->getManager();

		$monarticle = $em
		->getRepository("TCWriterBundle:Articles")
		->FindThisArticleAdmin($jeux, $id)
		;

		if (null == $monarticle){
			throw new NotFoundHttpException("L'annonce ou le jeu que vous recherchiez n'existe pas !!");
		}
		else{
		$article = $em
		->getRepository("TCWriterBundle:Articles")
		->Find($id);

		$em->remove($article);
		$em->flush();
		 return $this->redirectToRoute('tc_admin_writer_list_articles');

		}
	}

}