<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Services\FormEventService;
use App\Services\FormListnerFactory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Image;

class RecipeType extends AbstractType
{
    public function __construct(private FormListnerFactory $listner) {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('slug', TextType::class, [
                'required' => false,
            ])
            ->add('thumbnailFile', FileType::class)
            ->add('category', CategoryAutocompleteField::class)
            ->add('content')
            ->add('duration')
            ->add('save', SubmitType::class, [
                'label' => "Enregistrer"
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->listner->autoSlug('title'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->listner->timeStemp())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
