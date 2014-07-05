<?php

namespace MewesK\WebRedirectorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RedirectType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hostname')
            ->add('path')
            ->add('destination')
            ->add('isRegex')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MewesK\WebRedirectorBundle\Entity\Redirect'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mewesk_webredirectorbundle_redirect';
    }
}
