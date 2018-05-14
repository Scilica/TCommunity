<?php 

// src/TC/WriterBundle/Controller/NewsController.php

namespace TC\WriterBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TC\WriterBundle\Entity\News;
use Symfony\Component\HttpFoundation\Request;
use TC\WriterBundle\Entity\Games;
use TC\WriterBundle\Entity\Tag;
use TC\WriterBundle\Form\NewsType;
use TC\WriterBundle\Form\NewsEditType;
use TC\WriterBundle\Form\TagType;
use TC\WriterBundle\Form\TagEditType;
use TC\WriterBundle\Form\TagFilterType;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class NewsController extends Controller
{
	public function indexAction($page, Request $request)
	{

		if ($page < 1) {
		throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
		}

		$nbPerPage = 10;
		// je recupère toutes mes news 

		$em = $this->getDoctrine()->getManager();

		$listNews = $em
		->getRepository('TCWriterBundle:News')
		->FindAllNews($page, $nbPerPage)
		;

		$nbPages = ceil(count($listNews) / $nbPerPage);

		if ($page > $nbPages){
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

		// je lie mes news a mes jeux
		foreach ($listNews as $news) {
			$news->getGame();
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
       return $this->redirectToRoute('tc_writer_news_filtred', array('tag' => $date));
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
       return $this->redirectToRoute('tc_writer_news_game_filtred', array('jeux' => $date));
		} 

		return $this->render('TCWriterBundle:News:index.html.twig', array(
			'listnews' => $listNews,
			'nbPages' => $nbPages,
			'form' => $form->createView(),
			'forme' => $forme->createView(),
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

		$listNews = $em
		->getRepository('TCWriterBundle:News')
		->FindFiltredNews($tag, $page, $nbPerPage)
		;

		$nbPages = ceil(count($listNews) / $nbPerPage);

		if ($page > $nbPages){
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

		// je lie mes news a mes jeux
		foreach ($listNews as $news) {
			$news->getGame();
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
       return $this->redirectToRoute('tc_writer_news_filtred', array('tag' => $date));
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
       return $this->redirectToRoute('tc_writer_news_game_filtred', array('jeux' => $date));
		} 

		return $this->render('TCWriterBundle:News:filtred.html.twig', array(
			'listnews' => $listNews,
			'nbPages' => $nbPages,
			'page' => $page,
			'form' => $form->createView(),
			'forme' => $forme->createView(),
			'tag' => $tag
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

		$listNews = $em
		->getRepository('TCWriterBundle:News')
		->FindFiltredNewsByGame($jeux, $page, $nbPerPage)
		;

		$nbPages = ceil(count($listNews) / $nbPerPage);

		if ($page > $nbPages){
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

		// je lie mes news a mes jeux
		foreach ($listNews as $news) {
			$news->getGame();
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
       return $this->redirectToRoute('tc_writer_news_filtred', array('tag' => $date));
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
       return $this->redirectToRoute('tc_writer_news_game_filtred', array('jeux' => $date));
		} 

		return $this->render('TCWriterBundle:News:bygamefiltred.html.twig', array(
			'listnews' => $listNews,
			'nbPages' => $nbPages,
			'page' => $page,
			'form' => $form->createView(),
			'forme' => $forme->createView(),
			'jeux' => $jeux
			));
	}

	public function viewAction($jeux, $id, Request $request)
	{

		$jeux = str_replace("_", " ", $jeux);

		$em = $this->getDoctrine()->getManager();

		$manews = $em
		->getRepository("TCWriterBundle:News")
		->FindThisNews($jeux, $id)
		;

		$othernews = $em
		->getRepository("TCWriterBundle:News")
		->FindOtherNews($jeux, $id)
		;
		
		if (null == $manews){
			throw new NotFoundHttpException("L'annonce ou le jeu que vous recherchiez n'existe pas !!");
		}

		foreach ($manews as $news){
			$news->getTag();
			$this->get('views_counter.views_counter')->count($news);
		}

		    $id = 'thread_id';
    $thread = $this->container->get('fos_comment.manager.thread')->findThreadById($id);
    if (null === $thread) {
        $thread = $this->container->get('fos_comment.manager.thread')->createThread();
        $thread->setId($id);
        $thread->setPermalink($request->getUri());

        // Add the thread
        $this->container->get('fos_comment.manager.thread')->saveThread($thread);
    }

    $comments = $this->container->get('fos_comment.manager.comment')->findCommentTreeByThread($thread);

		return $this->render('TCWriterBundle:News:view.html.twig', array(
			'manews' => $manews,
			'jeux' => $jeux,
			        'comments' => $comments,
        'thread' => $thread,
			'othernews' => $othernews,
			));
	}

	/**
	* @Security("has_role('ROLE_ADD_PUBLICATION')")
	*/
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

		$news = new News();

		$form = $this->get('form.factory')->create(NewsType::class, $news, ['Game' => $jeux]);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->persist($news);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'news bien enregistrée.');

				return $this->redirectToRoute('tc_writer_news_view', array('id' => $news->getId(), 'jeux' => $news->getGame()));
			}
		
		return $this->render('TCWriterBundle:News:add.html.twig', array(
			'form' => $form->createView(),
			));


	}

	public function editAction($jeux, $id, Request $request)
	{
		$jeux = str_replace("_", " ", $jeux);

		$em = $this->getDoctrine()->getManager();


		$news = $em
		->getRepository("TCWriterBundle:News")
		->Find($id)
		;
		$form = $this->get('form.factory')->create(NewsEditType::class, $news, ['Game' => $jeux]);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'news bien enregistrée.');

				 return $this->redirectToRoute('tc_admin_writer_list_news');
			}
		
		return $this->render('TCWriterBundle:News:add.html.twig', array(
			'form' => $form->createView(),
			));

		
	}

	public function deleteAction($jeux, $id, Request $request)
	{
		$jeux = str_replace("_", " ", $jeux);

		$em = $this->getDoctrine()->getManager();

		$manews = $em
		->getRepository("TCWriterBundle:News")
		->FindThisNewsAdmin($jeux, $id)
		;

		if (null == $manews){
			throw new NotFoundHttpException("L'annonce ou le jeu que vous recherchiez n'existe pas !!");
		}
		else{
		$news = $em
		->getRepository("TCWriterBundle:News")
		->Find($id);

		$em->remove($news);
		$em->flush();
		 return $this->redirectToRoute('tc_admin_writer_list_news');

		}
	}
}