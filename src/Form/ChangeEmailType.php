<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangeEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("email", EmailType::class, [
            "required" => true,
            'label' => "Deine neue Email:"
        ]);

        $builder->add("password", PasswordType::class, [
            "required" => true,
            'label' => "Gib dein Passwort ein, um deine Änderung zu verifizieren:",
            "constraints" => [
                new UserPassword()
            ]
        ]);

        $builder->add("submit", SubmitType::class, [
            'label' => "Änderung SPEICHERN"
        ]);
    }

}