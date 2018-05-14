<?php 


namespace TC\WriterBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TC\WriterBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use TC\WriterBundle\Entity\Guides;
use TC\WriterBundle\Entity\Games;
use TC\WriterBundle\Form\CategoryType;
use TC\WriterBundle\Form\CategoryEditType;

class CategoryController extends Controller
{

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

		$category = new Category();

		$form = $this->get('form.factory')->create(CategoryType::class, $category, ['Game' => $jeux]);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->persist($category);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Categorie bien ratachée');

				return $this->redirectToRoute('tc_writer_homepage');
			}
		
		return $this->render('TCWriterBundle:Category:add.html.twig', array(
			'form' => $form->createView(),
			));

	}

	public function editAction($jeux, $category, Request $request)
	{
		$jeux = str_replace("_", " ", $jeux);
		$category = str_replace("_", " ", $category);
		$category = str_replace("-", "'", $category);

		$em = $this->getDoctrine()->getManager();

		$macategory = $em
		->getRepository("TCWriterBundle:Category")
		->FindThisCategoryForThisGameForm($jeux, $category)
		;

		if (null == $macategory){
			throw new NotFoundHttpException("La catégorie ou le jeu n'existe pas");
		}
		else{
		$category = $em
		->getRepository("TCWriterBundle:Category")
		->FindThisCategoryForThisGame($jeux, $category)
		;

		if (null == $category){
			throw new NotFoundHttpException("La catégorie ou le jeu n'existe pas");
		}
		$form = $this->get('form.factory')->create(CategoryEditType::class, $category, ['Game' => $jeux]);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'guide bien modifier.');

				return $this->redirectToRoute('tc_writer_homepage');
			}
		
		return $this->render('TCWriterBundle:Category:add.html.twig', array(
			'form' => $form->createView(),
			));

		}
	}

	public function viewAction($tag)
	{

		// je recupère toutes mes news 

		$tag = str_replace("_", " ", $tag);

		$em = $this->getDoctrine()->getManager();

		$listTagNews = $em
		->getRepository('TCWriterBundle:News')
		->FindTagNews($tag)
		;

		$listTagArticles = $em
		->getRepository('TCWriterBundle:Articles')
		->FindTagArticles($tag)
		;

		return $this->render('TCWriterBundle:Tags:view.html.twig', array(
			'tagnews' => $listTagNews,
			'tagarticles' => $listTagArticles,
			'tag' => $tag,
			));
	}
}