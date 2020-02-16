<?php


namespace App\Form;

use App\Entity\Headword;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class DeleteHeadwordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("headwords", EntityType::class, [
            "required" => true,
            'label' => "Schlagwort löschen",
            'class' => Headword::class,
            'choice_label' => 'headwordName',
            'multiple' => true,
            'expanded' => true
        ]);

        $builder->add("submit", SubmitType::class, [
            'label' => "Löschen"
        ]);
    }

}