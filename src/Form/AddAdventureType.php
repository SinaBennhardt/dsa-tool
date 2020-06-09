<?php


namespace App\Form;

use App\Entity\Adventure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddAdventureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("title", TextType::class, [
            "required" => true,
            'label' => "Titel"
        ]);

        $builder->add("blurb", TextareaType::class, [
            'required' => true,
            'label' => 'Zusammenfassung'
        ]);

        $builder->add("submit", SubmitType::class, [
            'label' => 'Abschicken'
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adventure::class
        ]);
    }
}