<?php

namespace TC\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProfileFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('avatarFile', VichFileType::class, array(
            'allow_delete'  => false, // not mandatory, default is true
            'download_link' => true, // not mandatory, default is true
            ))
            ->add('birthdate',BirthdayType::class, array(
            ))
            ->add('steam_id', TextType::class);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }
    public function getName()
    {
        return 'tc_user_profile';
    }
}
