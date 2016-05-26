<?php

namespace tst\OrgzBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('secondname', 'text', ['label' => 'Фамилия','attr' =>['class' => 'form-control']])
            ->add('firstname', 'text', ['label' => 'Имя','required'=>false,'attr' =>['class' => 'form-control']])
            ->add('patronymic', 'text', ['label' => 'Отчество','required'=>false,'attr' =>['class' => 'form-control']])
            ->add('inn', 'text', ['label' => 'ИНН','required'=>false,'attr' => ['maxlength' => 16,'class' => 'form-control']])
            ->add('snils', 'text',['label' => 'СНИЛС','attr' => ['maxlength' => 13,'class' => 'form-control']])
            ->add('date_birth', 'birthday', [
                    'label'=>'Дата рождения','required'=>false,
                    'widget' => 'single_text',
                    'placeholder' =>[
                        'year' => 'Год', 'month' => 'Месяц', 'day' => 'День',
                    ],'attr' =>['class' => 'form-control']
                ]
            )

            ->add('organization', null, array('label' => 'Организация','attr' =>['class' => 'form-control hidden'],'label_attr' =>['class' => 'hidden']));
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'tst\OrgzBundle\Entity\User'
        ));
    }
}
