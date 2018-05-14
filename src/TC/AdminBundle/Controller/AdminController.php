<?php

namespace TC\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Model\UserManagerInterface;
use TC\UserBundle\Form\RegistrationEditFormType;
use TC\UserBundle\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\AreaChart;
use Symfony\Component\Translation\TranslatorInterface;

class AdminController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $datetime7 = new \DateTime("7daysAgo");
        $jour7 = $datetime7->format('l');
        $datetime6 = new \DateTime("6daysAgo");
        $jour6 = $datetime6->format('l');
        $datetime5 = new \DateTime("5daysAgo");
        $jour5 = $datetime5->format('l');
        $datetime4 = new \DateTime("4daysAgo");
        $jour4 = $datetime4->format('l');
        $datetime3 = new \DateTime("3daysAgo");
        $jour3 = $datetime3->format('l');
        $datetime2 = new \DateTime("2daysAgo");
        $jour2 = $datetime2->format('l');
        $datetime1 = new \DateTime("1daysAgo");
        $jour1 = $datetime1->format('l');

        $jour7traducted = $this->get('translator')->trans($jour7);
        $jour6traducted = $this->get('translator')->trans($jour6);
        $jour5traducted = $this->get('translator')->trans($jour5);
        $jour4traducted = $this->get('translator')->trans($jour4);
        $jour3traducted = $this->get('translator')->trans($jour3);
        $jour2traducted = $this->get('translator')->trans($jour2);
        $jour1traducted = $this->get('translator')->trans($jour1);
        
        $totalnews = $em
        ->getRepository('TCWriterBundle:News')
        ->CountNews();

        $getunpublishednews = $em
        ->getRepository('TCWriterBundle:News')
        ->FindNewsUnpublished();

        $getbestnews = $em
        ->getRepository('TCWriterBundle:News')
        ->FindBestNews();

         $totalarticles = $em
        ->getRepository('TCWriterBundle:Articles')
        ->CountArticles();

        $getunpublishedarticles = $em
        ->getRepository('TCWriterBundle:Articles')
        ->FindArticlesUnpublished();

        $getbestarticles = $em
        ->getRepository('TCWriterBundle:Articles')
        ->FindBestArticles();

         $totalguides = $em
        ->getRepository('TCWriterBundle:Guides')
        ->CountGuides();

        $getunpublishedguides = $em
        ->getRepository('TCWriterBundle:Guides')
        ->FindGuidesUnpublished();

        $getbestguides = $em
        ->getRepository('TCWriterBundle:Guides')
        ->FindBestGuides();

        $bestGameslist = $em
        ->getRepository('TCWriterBundle:Games')
        ->FindBestGames()
        ;

        $analyticsService = $this->get('google_analytics_api.api');
        $analytics = $analyticsService->getAnalytics();
        $viewId = '174843059';

        $sessions = $analyticsService->getSessionsDateRange($viewId,'30daysAgo','today');
        $percentNewVisits = $analyticsService->getPercentNewVisitsDateRange($viewId,'30daysAgo','today');
        $avgPageLoadTime = $analyticsService->getAvgPageLoadTimeDateRange($viewId,'30daysAgo','today');
        $bounceRate = $analyticsService->getBounceRateDateRange($viewId,'30daysAgo','today');
        $pageViews = $analyticsService->getPageViewsDateRange($viewId,'30daysAgo','today');

        $pageViews7daysAgo = $analyticsService->getPageViewsDateRange($viewId,'7daysAgo','7daysAgo');
        $pageViews6daysAgo = $analyticsService->getPageViewsDateRange($viewId,'6daysAgo','6daysAgo');
        $pageViews5daysAgo = $analyticsService->getPageViewsDateRange($viewId,'5daysAgo','5daysAgo');
        $pageViews4daysAgo = $analyticsService->getPageViewsDateRange($viewId,'4daysAgo','4daysAgo');
        $pageViews3daysAgo = $analyticsService->getPageViewsDateRange($viewId,'3daysAgo','3daysAgo');
        $pageViews2daysAgo = $analyticsService->getPageViewsDateRange($viewId,'2daysAgo','2daysAgo');
        $pageViews1daysAgo = $analyticsService->getPageViewsDateRange($viewId,'1daysAgo','1daysAgo');

    $area = new AreaChart();
$area->getData()->setArrayToDataTable([
    ['Jour', 'Pages consultées'],
    [$jour7traducted,  intval($pageViews7daysAgo)],
    [$jour6traducted,  intval($pageViews6daysAgo)],
    [$jour5traducted,  intval($pageViews5daysAgo)],
    [$jour4traducted,  intval($pageViews4daysAgo)],
    [$jour3traducted,  intval($pageViews3daysAgo)],
    [$jour2traducted,  intval($pageViews2daysAgo)],
    [$jour1traducted,  intval($pageViews1daysAgo)]
]);
$area->getOptions()->setTitle('Analyse du trafic');
$area->getOptions()->getHAxis()->setTitle('Jour');
$area->getOptions()->getHAxis()->getTitleTextStyle()->setColor('#333');
$area->getOptions()->getVAxis()->setMinValue(0);
$area->getOptions()->setHeight(350);

        return $this->render('TCAdminBundle:Homepage:index.html.twig', array(
            'totalnews' => $totalnews,
            'unpublishednews' => $getunpublishednews,
            'totalarticles' => $totalarticles,
            'unpublishedarticles' => $getunpublishedarticles,
            'totalguides' => $totalguides,
            'unpublishedguides' => $getunpublishedguides,
            'bestGameslist' => $bestGameslist,
            'area' => $area,
            'sessions' => $sessions,
            'pageViews' => $pageViews,
            'percentNewVisits' => $percentNewVisits,
            'avgPageLoadTime' => $avgPageLoadTime,
            'bounceRate' => $bounceRate,
            'getbestnews' => $getbestnews,
            'getbestarticles' => $getbestarticles,
            'getbestguides' => $getbestguides,
            ));
    }

    public function userindexAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$userManager = $this->get('fos_user.user_manager');
    	
    	$users = $userManager->findUsers();
    	$form = $this->get('form.factory')->createBuilder(FormType::class)
		->add('value', TextType::class, array(
			'required' => false,
			'label' => false,
            'attr' => array(
        'placeholder' => 'Rechercher par ID, par Pseudo, par Email ou par Steam ID...')))
		->getForm();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $users, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );

    	if ($request->isMethod('POST')&& $form->handleRequest($request)->isValid()){
    	

		$value = $request->request->get("form");
		if(strlen($value['value']) < 1) {return $this->redirectToRoute('tc_admin_user');}
		$useres = $em
      ->getRepository('TCUserBundle:User')
      ->research($value['value'])
      ;
		 var_dump($value["value"]);
         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $useres, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );
		 return $this->render('TCAdminBundle:User:index.html.twig', array(
        	'listUsers' => $useres,
            'pagination' => $pagination,
		 'form' => $form->createView()));
		 }
        return $this->render('TCAdminBundle:User:index.html.twig', array(
        	'listUsers' => $users,
            'pagination' => $pagination,
        	'form' => $form->createView()));
    }

    public function groupindexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.group_manager');
        
        $groups = $userManager->findGroups();
        $form = $this->get('form.factory')->createBuilder(FormType::class)
        ->add('value', TextType::class, array(
            'required' => false,
            'label' => false,
            'attr' => array(
        'placeholder' => 'Rechercher par Nom...')))
        ->getForm();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $groups, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );

        if ($request->isMethod('POST')&& $form->handleRequest($request)->isValid()){
        

        $value = $request->request->get("form");
        if(strlen($value['value']) < 1) {return $this->redirectToRoute('tc_admin_user_group_list');}
        $groupes = $em
      ->getRepository('TCUserBundle:Groups')
      ->research($value['value'])
      ;
         var_dump($value["value"]);
         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $groupes, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );
         return $this->render('TCAdminBundle:Group:index.html.twig', array(
            'listGroups' => $groupes,
            'pagination' => $pagination,
         'form' => $form->createView()));
         }
        return $this->render('TCAdminBundle:Group:index.html.twig', array(
            'listGroups' => $groups,
            'pagination' => $pagination,
            'form' => $form->createView()));
    }
    
    public function writerindexAction()
    {

        return $this->render('TCAdminBundle:Homepage:index.html.twig');
    }

    public function newsindexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listNews = $em
        ->getRepository('TCWriterBundle:News')
        ->findAll()
        ;

         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $listNews, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );

       $form = $this->get('form.factory')->createBuilder(FormType::class)
        ->add('value', TextType::class, array(
            'required' => false,
            'label' => false,
            'attr' => array(
        'placeholder' => 'Rechercher par ID, par Titre, par Jeu ou par Auteur...')))
        ->getForm();


        if ($request->isMethod('POST')&& $form->handleRequest($request)->isValid()){
        

        $value = $request->request->get("form");
        if(strlen($value['value']) < 1) {return $this->redirectToRoute('tc_admin_writer_list_news');}
        $useres = $em
      ->getRepository('TCWriterBundle:News')
      ->research($value['value'])
      ;
         var_dump($value["value"]);
         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $useres, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );
         return $this->render('TCAdminBundle:News:index.html.twig', array(
            'listNews' => $useres,
            'pagination' => $pagination,
            'form' => $form->createView()
            ));
         }

        return $this->render('TCAdminBundle:News:index.html.twig', array(
            'listNews' => $listNews,
            'pagination' => $pagination,
            'form' => $form->createView()
            ));
    }

    public function newsfiltredAction($jeux, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $jeux = str_replace("_", " ", $jeux);

        $listNews = $em
        ->getRepository('TCWriterBundle:News')
        ->FindNewsByAdminGame($jeux)
        ;

         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $listNews, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );

       $form = $this->get('form.factory')->createBuilder(FormType::class)
        ->add('value', TextType::class, array(
            'required' => false,
            'label' => false,
            'attr' => array(
        'placeholder' => 'Rechercher par ID, par Titre ou par Auteur...')))
        ->getForm();


        if ($request->isMethod('POST')&& $form->handleRequest($request)->isValid()){
        

        $value = $request->request->get("form");
        if(strlen($value['value']) < 1) {$jeux = str_replace(" ", "_", $jeux); return $this->redirectToRoute('tc_admin_writer_game_news_filtred', array( 'jeux' => $jeux));}
        $useres = $em
      ->getRepository('TCWriterBundle:News')
      ->researchFiltred($jeux, $value['value'])
      ;
         var_dump($value["value"]);
         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $useres, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );
         return $this->render('TCAdminBundle:News:filtred.html.twig', array(
            'listNews' => $useres,
            'pagination' => $pagination,
            'jeux' => $jeux,
            'form' => $form->createView()
            ));
         }

        return $this->render('TCAdminBundle:News:filtred.html.twig', array(
            'listNews' => $listNews,
            'jeux' => $jeux,
            'pagination' => $pagination,
            'form' => $form->createView()
            ));
    }

    public function articlesindexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listArticles = $em
        ->getRepository('TCWriterBundle:Articles')
        ->findAll()
        ;

         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $listArticles, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );

       $form = $this->get('form.factory')->createBuilder(FormType::class)
        ->add('value', TextType::class, array(
            'required' => false,
            'label' => false,
            'attr' => array(
        'placeholder' => 'Rechercher par ID, par Titre, par Jeu ou par Auteur...')))
        ->getForm();


        if ($request->isMethod('POST')&& $form->handleRequest($request)->isValid()){
        

        $value = $request->request->get("form");
        if(strlen($value['value']) < 1) {return $this->redirectToRoute('tc_admin_writer_list_articles');}
        $useres = $em
      ->getRepository('TCWriterBundle:Articles')
      ->research($value['value'])
      ;
         var_dump($value["value"]);
         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $useres, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );
         return $this->render('TCAdminBundle:Articles:index.html.twig', array(
            'listArticles' => $useres,
            'pagination' => $pagination,
            'form' => $form->createView()
            ));
         }

        return $this->render('TCAdminBundle:Articles:index.html.twig', array(
            'listArticles' => $listArticles,
            'pagination' => $pagination,
            'form' => $form->createView()
            ));
    }

    public function articlesfiltredAction($jeux, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $jeux = str_replace("_", " ", $jeux);

        $listArticles = $em
        ->getRepository('TCWriterBundle:Articles')
        ->FindArticlesByAdminGame($jeux)
        ;

         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $listArticles, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );

       $form = $this->get('form.factory')->createBuilder(FormType::class)
        ->add('value', TextType::class, array(
            'required' => false,
            'label' => false,
            'attr' => array(
        'placeholder' => 'Rechercher par ID, par Titre ou par Auteur...')))
        ->getForm();


        if ($request->isMethod('POST')&& $form->handleRequest($request)->isValid()){
        

        $value = $request->request->get("form");
        if(strlen($value['value']) < 1) {$jeux = str_replace(" ", "_", $jeux); return $this->redirectToRoute('tc_admin_writer_game_articles_filtred', array( 'jeux' => $jeux));}
        $useres = $em
      ->getRepository('TCWriterBundle:Articles')
      ->researchFiltred($jeux, $value['value'])
      ;
         var_dump($value["value"]);
         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $useres, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );
         return $this->render('TCAdminBundle:Articles:filtred.html.twig', array(
            'listArticles' => $useres,
            'pagination' => $pagination,
            'jeux' => $jeux,
            'form' => $form->createView()
            ));
         }

        return $this->render('TCAdminBundle:Articles:filtred.html.twig', array(
            'listArticles' => $listArticles,
            'pagination' => $pagination,
            'jeux' => $jeux,
            'form' => $form->createView()
            ));
    }

     public function guidesindexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listGuides = $em
        ->getRepository('TCWriterBundle:Guides')
        ->findAll()
        ;

         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $listGuides, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );

       $form = $this->get('form.factory')->createBuilder(FormType::class)
        ->add('value', TextType::class, array(
            'required' => false,
            'label' => false,
            'attr' => array(
        'placeholder' => 'Rechercher par ID, par Titre, par Jeu, par Catégorie ou par Auteur...')))
        ->getForm();


        if ($request->isMethod('POST')&& $form->handleRequest($request)->isValid()){
        

        $value = $request->request->get("form");
        if(strlen($value['value']) < 1) {return $this->redirectToRoute('tc_admin_writer_list_guides');}
        $useres = $em
      ->getRepository('TCWriterBundle:Guides')
      ->research($value['value'])
      ;
         var_dump($value["value"]);
         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $useres, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );
         return $this->render('TCAdminBundle:Guides:index.html.twig', array(
            'listGuides' => $useres,
            'pagination' => $pagination,
            'form' => $form->createView()
            ));
         }

        return $this->render('TCAdminBundle:Guides:index.html.twig', array(
            'listGuides' => $listGuides,
            'pagination' => $pagination,
            'form' => $form->createView()
            ));
    }

     public function guidesfiltredAction($jeux, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $jeux = str_replace("_", " ", $jeux);

        $listGuides = $em
        ->getRepository('TCWriterBundle:Guides')
        ->FindGuidesByAdminGame($jeux)
        ;

         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $listGuides, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );

       $form = $this->get('form.factory')->createBuilder(FormType::class)
        ->add('value', TextType::class, array(
            'required' => false,
            'label' => false,
            'attr' => array(
        'placeholder' => 'Rechercher par ID, par Titre, par Catégorie ou par Auteur...')))
        ->getForm();


        if ($request->isMethod('POST')&& $form->handleRequest($request)->isValid()){
        

        $value = $request->request->get("form");
        if(strlen($value['value']) < 1) {$jeux = str_replace(" ", "_", $jeux); return $this->redirectToRoute('tc_admin_writer_game_guides_filtred', array( 'jeux' => $jeux));}
        $useres = $em
      ->getRepository('TCWriterBundle:Guides')
      ->researchFiltred($jeux, $value['value'])
      ;
         var_dump($value["value"]);
         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $useres, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );
         return $this->render('TCAdminBundle:Guides:filtred.html.twig', array(
            'listGuides' => $useres,
            'pagination' => $pagination,
            'jeux' => $jeux,
            'form' => $form->createView()
            ));
         }

        return $this->render('TCAdminBundle:Guides:filtred.html.twig', array(
            'listGuides' => $listGuides,
            'pagination' => $pagination,
            'jeux' => $jeux,
            'form' => $form->createView()
            ));
    }

     public function jeuxindexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listJeux = $em
        ->getRepository('TCWriterBundle:Games')
        ->findAll()
        ;

         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $listJeux, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );

       $form = $this->get('form.factory')->createBuilder(FormType::class)
        ->add('value', TextType::class, array(
            'required' => false,
            'label' => false,
            'attr' => array(
        'placeholder' => 'Rechercher par ID, par Titre ou par Editeur...')))
        ->getForm();


        if ($request->isMethod('POST')&& $form->handleRequest($request)->isValid()){
        

        $value = $request->request->get("form");
        if(strlen($value['value']) < 1) {return $this->redirectToRoute('tc_admin_writer_list_jeux');}
        $useres = $em
      ->getRepository('TCWriterBundle:Games')
      ->research($value['value'])
      ;
         var_dump($value["value"]);
         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $useres, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );
         return $this->render('TCAdminBundle:Jeux:index.html.twig', array(
            'listJeux' => $useres,
            'pagination' => $pagination,
            'form' => $form->createView()
            ));
         }

        return $this->render('TCAdminBundle:Jeux:index.html.twig', array(
            'listJeux' => $listJeux,
            'pagination' => $pagination,
            'form' => $form->createView()
            ));
    }

     public function jeuxviewAction($jeux, Request $request)
    {
        $jeux = str_replace("_", " ", $jeux);
        $em = $this->getDoctrine()->getManager();

        $nbNews = $em
        ->getRepository('TCWriterBundle:News')
        ->CountNewsByGame($jeux)
        ;

        $nbArticles = $em
        ->getRepository('TCWriterBundle:Articles')
        ->CountArticlesByGame($jeux)
        ;

        $nbGuides = $em
        ->getRepository('TCWriterBundle:Guides')
        ->CountGuidesByGame($jeux)
        ;

        $game = $em
        ->getRepository('TCWriterBundle:Games')
        ->findOneByTitle($jeux);

        return $this->render('TCAdminBundle:Jeux:view.html.twig', array(
            'nbNews' => $nbNews,
            'game' => $game,
            'nbArticles' => $nbArticles,
            'nbGuides' => $nbGuides,
            'jeux' => $jeux,
            ));
    }

    public function tagsindexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listTags = $em
        ->getRepository('TCWriterBundle:Tag')
        ->findAll()
        ;

         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $listTags, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );

       $form = $this->get('form.factory')->createBuilder(FormType::class)
        ->add('value', TextType::class, array(
            'required' => false,
            'label' => false,
            'attr' => array(
        'placeholder' => 'Rechercher par ID ou par Titre ...')))
        ->getForm();


        if ($request->isMethod('POST')&& $form->handleRequest($request)->isValid()){
        

        $value = $request->request->get("form");
        if(strlen($value['value']) < 1) {return $this->redirectToRoute('tc_admin_writer_list_tags');}
        $useres = $em
      ->getRepository('TCWriterBundle:Tag')
      ->research($value['value'])
      ;
         var_dump($value["value"]);
         $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $useres, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        20/*limit per page*/
    );
         return $this->render('TCAdminBundle:Tags:index.html.twig', array(
            'listTags' => $useres,
            'pagination' => $pagination,
            'form' => $form->createView()
            ));
         }

        return $this->render('TCAdminBundle:Tags:index.html.twig', array(
            'listTags' => $listTags,
            'pagination' => $pagination,
            'form' => $form->createView()
            ));
    }
}
