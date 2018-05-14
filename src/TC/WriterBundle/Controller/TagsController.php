<?php 


namespace TC\WriterBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TC\WriterBundle\Entity\Tag;
use Symfony\Component\HttpFoundation\Request;
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

class TagsController extends Controller
{

	public function addAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		
		$tag = new Tag();

		$form = $this->get('form.factory')->create(TagType::class, $tag);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->persist($tag);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Tag bien enregistré.');

				return $this->redirectToRoute('tc_writer_homepage');
			}
		
		return $this->render('TCWriterBundle:Tags:add.html.twig', array(
			'form' => $form->createView(),
			));

		
	}

	public function editAction($tag, Request $request)
	{
		$tag = str_replace("_", " ", $tag);

		$em = $this->getDoctrine()->getManager();

		$tagexist = $em
		->getRepository('TCWriterBundle:Tag')
		->findOneByTitle($tag)
		;

		if (null == $tagexist){
			throw new NotFoundHttpException("Le tag n'existe pas");
		}
		else{
		$tag = $em
		->getRepository('TCWriterBundle:Tag')
		->findOneByTitle($tag)
		;
		$form = $this->get('form.factory')->create(TagEditType::class, $tag);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Tag bien modifier.');

				return $this->redirectToRoute('tc_admin_writer_list_tags');
			}
		
		return $this->render('TCWriterBundle:Tags:add.html.twig', array(
			'form' => $form->createView(),
			));

		}
	}

	public function deleteAction($tag, Request $request)
	{
		$tag = str_replace("_", " ", $tag);

		$em = $this->getDoctrine()->getManager();

		$tagexist = $em
		->getRepository('TCWriterBundle:Tag')
		->findOneByTitle($tag)
		;

		if (null == $tagexist){
			throw new NotFoundHttpException("Le tag n'existe pas");
		}
		else{
		$tag = $em
		->getRepository("TCWriterBundle:Tag")
		->findOneByTitle($tag);

		$em->remove($tag);
		$em->flush();
		 return $this->redirectToRoute('tc_admin_writer_list_tags');

		}
	}
	public function viewAction($tag, Request $request)
{

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

		$listTagGuides = $em
		->getRepository('TCWriterBundle:Guides')
		->FindTagGuides($tag)
		;
		
		return $this->render('TCWriterBundle:Tags:view.html.twig', array(
			'tagnews' => $listTagNews,
			'tagarticles' => $listTagArticles,
			'tagguides' => $listTagGuides,
			'tag' => $tag,
			));
	}

}