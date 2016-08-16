<?php

namespace nacholibre\CategoryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CategorySelectorType extends AbstractType {
    //private $container;

    //function __construct($container) {
    //    $this->container = $container;
    //}

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$parameters = $this->container->getParameter('nacholibre_pages');
        //$editor = $parameters['editor'];
        //$categoryManager = $this->container->get('nacholibre.category.manager');

        //$formCategories = $categoryManager->generateFormFlatArrayCategories();

        //$builder->add('name', TextType::class, [
        //    'label' => 'category.name',
        //    'required' => true,
        //]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'mapping' => null,
        ]);
    }

    public function getBlockPrefix() {
        return 'nacholibre_category_type';
    }
}
