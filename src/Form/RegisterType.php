<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name", TextType::class, [
            "required" => true,
            "label" => "Dein User Name:"
        ]);

        $builder->add("email", EmailType::class, [
            "required" => true,
            "label" => "Deine Email:"
        ]);

        $builder->add("password", RepeatedType::class, [
            "type" => PasswordType::class,
            "required" => true,
            "first_options" => ["label" => "Dein Passwort:"],
            "second_options" => ["label" => "Passwort wiederholen:"]
        ]);

        $builder->add("submit", SubmitType::class, [
            'label' => "Registrieren"
        ]);
    }
}
