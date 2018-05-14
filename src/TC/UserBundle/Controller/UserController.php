<?php

namespace TC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Model\UserManagerInterface;
use TC\UserBundle\Form\RegistrationEditFormType;
use TC\UserBundle\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class UserController extends Controller
{

    
    public function editAction($id, Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
    	
    	$users = $userManager->findUserBy(array('id'=>$id));

		if (null == $users){
			throw new NotFoundHttpException("L'utilisateur que vous recherchiez n'existe pas !!");
		}
		
		
		$form = $this->get('form.factory')->create(RegistrationEditFormType::class, $users);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$userManager->updateUser($users);

			 return $this->redirectToRoute('tc_admin_user');}
		
		return $this->render('TCUserBundle:User:add.html.twig', array(
			'form' => $form->createView(),
			));

    }

    public function deleteAction($id, Request $request)
    {
    	if ($request->isMethod('GET')){
    		 $userManager = $this->get('fos_user.user_manager');
    		 $users = $userManager->findUserBy(array('id'=>$id));
    		 $userManager->deleteUser($users);

    		 return $this->redirectToRoute('tc_admin_user');
    	}
    	
    }
}
