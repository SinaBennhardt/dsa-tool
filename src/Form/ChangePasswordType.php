<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("oldPassword", PasswordType::class, [
            "required" => true,
            'label' => "Dein ALTES Passwort:",
            "constraints" => [
                new UserPassword()
            ]
        ]);

        $builder->add("password", RepeatedType::class, [
            "type" => PasswordType::class,
            "required" => true,
            "first_options" => ["label" => "Dein neues Passwort:"],
            "second_options" => ["label" => "Neues Passwort wiederholen:"]
        ]);

        $builder->add("submit", SubmitType::class, [
            'label' => "Änderung SPEICHERN"
        ]);
    }
}
