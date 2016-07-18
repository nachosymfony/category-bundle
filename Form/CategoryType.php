<?php

namespace nacholibre\CategoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CategoryType extends AbstractType {
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([]);
    }

    public function getParent() {
        return BaseCategoryType::class;
    }
}
