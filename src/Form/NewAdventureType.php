<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class NewAdventureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name", TextType::class, [
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

}