<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangeNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name", TextType::class, [
            "required" => true,
            'label' => "Dein neuer Name:"
        ]);

        $builder->add("submit", SubmitType::class, [
            'label' => "Änderung SPEICHERN"
        ]);
    }
}
