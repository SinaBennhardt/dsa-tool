<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class HeadwordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("headwordName", TextType::class, [
           "required" => true
       ]);

        $builder->add("submit", SubmitType::class, [
            'label' => "Abschicken"
        ]);
    }
}
