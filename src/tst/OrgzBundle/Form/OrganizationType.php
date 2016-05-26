<?php

namespace tst\OrgzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OrganizationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text',['label' => 'Название','attr' =>['class' => 'form-control']])
            ->add('ogrn', 'text',['label' => 'ОГРН','attr' =>['maxlength' => 13,'class' => 'form-control']])
            ->add('oktmo', 'text',['label' => 'ОКТМО','attr' =>['maxlength' => 11,'class' => 'form-control']]);
        $builder->add('users', CollectionType::class,[
            'label' => false,
            'entry_type' => UserType::class,
            'allow_add'    => true,
            'by_reference' => false,
            'allow_delete' => true,
        ]);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'tst\OrgzBundle\Entity\Organization',
            'cascade_validation' => true
        ));
    }
}
