<?php


namespace App\Form;


use App\Entity\Headword;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("title", TextType::class, [
            "required" => true,
            'label' => "Titel"
        ]);

        $builder->add("text", TextareaType::class, [
            "required" => true,
            'label' => "Text"
        ]);

        $builder->add("access", ChoiceType::class, [
            "required" => true,
            'label' => "Wer soll diesen Text lesen können:",
            'choices' => [
                'Jeder' => 'all',
                'Nur ich (und Admin)' => 'restricted'
            ]
        ]);

        $builder->add("headwords", EntityType::class, [
            "required" => true,
            'label' => "Schlagwort hinzufügen",
            'class' => Headword::class,
            'choice_label' => 'headwordName',
            'multiple' => true,
            'expanded' => true
        ]);

        $builder->add("submit", SubmitType::class, [
            'label' => "Abschicken"
        ]);
    }


}