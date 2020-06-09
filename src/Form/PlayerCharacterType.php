<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PlayerCharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("playerType", ChoiceType:: class, [
            "required" => true,
            "label" => "Bist du ein ...?",
            "choices" => [
                "Spieler" => "Held",
                "NSC" => "NSC"
            ]
        ]);

        $builder->add("characterName", TextType:: class, [
            "required" => true,
            "label" => "Name:"
        ]);

        $builder->add("race", TextType::class, [
            "required" => true,
            "label" => "Rasse:"
        ]);

        $builder->add("culture", TextType::class, [
            "required" => true,
            "label" => "Kultur:"
        ]);

        $builder->add("profession", TextType::class, [
            "required" => true,
            "label" => "Profession:"
        ]);

        $builder->add("socialStatus", ChoiceType::class, [
            "required" => true,
            "label" => "Sozialstatus:",
            "choices" => [
                "Sozialstatus" => null,
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4",
                "5" => "5",
                "6" => "6",
                "7" => "7",
                "8" => "8",
                "9" => "9",
                "10" => "10",
                "11" => "11",
                "12" => "12",
                "13" => "13",
                "14" => "14",
                "15" => "15",
                "16" => "16"
            ]
        ]);

        $builder->add("advantages", TextType::class, [
            "required" => true,
            "label" => "Vorteile:"
        ]);

        $builder->add("disadvantages", TextType::class, [
            "required" => true,
            "label" => "Nachteile:"
        ]);

        $builder->add("race", TextType::class, [
            "required" => true,
            "label" => "Rasse"
        ]);

        $builder->add("uniqueSkills", TextType::class, [
            "required" => true,
            "label" => "Sonderfertigkeiten:"
        ]);

        $builder->add("playerProperty", PCPropertyType::class, [
            "required" => true,
            "label" => false
        ]);

        $builder->add("submit", SubmitType::class, [
            'label' => "Abschicken"
        ]);
    }

}