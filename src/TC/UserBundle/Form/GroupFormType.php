<?php

namespace TC\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class GroupFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $permissions = array(
        'Administrateur'     => 'ROLE_ADMIN',
        'Rédacteur'        => 'ROLE_REDACTOR',
        'Modérateur'        => 'ROLE_MODERATOR',
        );
        $builder
        ->add('roles', ChoiceType::class, array(
            'label'   => 'Permissions',
            'choices' => $permissions,
            'multiple' => true,
            'expanded' => false
        ));
    }


    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\GroupFormType';
    }
    public function getName()
    {
        return 'tc_user_group';
    }
}
