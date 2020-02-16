<?php


namespace App\Form;


use App\Entity\Headword;
use App\Entity\PlayerProperties;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PCPropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("courage", IntegerType::class, [
            "required" => true,
            "label" => "Mut:"
        ]);

        $builder->add("intelligence", IntegerType::class, [
            "required" => true,
            "label" => "Klugheit:"
        ]);

        $builder->add("intuition", IntegerType::class, [
            "required" => true,
            "label" => "Intuition:"
        ]);

        $builder->add("charisma", IntegerType::class, [
            "required" => true,
            "label" => "Charisma:"
        ]);

        $builder->add("fingerDex", IntegerType::class, [
            "required" => true,
            "label" => "Fingerfertigkeit:"
        ]);

        $builder->add("generalDex", IntegerType::class, [
            "required" => true,
            "label" => "Gewandtheit:"
        ]);

        $builder->add("constitution", IntegerType::class, [
            "required" => true,
            "label" => "Konstitution:"
        ]);

        $builder->add("strength", IntegerType::class, [
            "required" => true,
            "label" => "Körperkraft:"
        ]);


        $builder->add("LP", IntegerType::class, [
            "required" => true,
            "disabled" => true,
            "label" => "Lebenspunkte:"
        ]);

        $builder->add("AsP", IntegerType::class, [
            "required" => true,
            "disabled" => true,
            "label" => "Astralpunkte:"
        ]);

        $builder->add("KP", IntegerType::class, [
            "required" => true,
            "disabled" => true,
            "label" => "Karmalpunkte:"
        ]);

        $builder->add("MR", IntegerType::class, [
            "required" => true,
            "disabled" => true,
            "label" => "Magieresistenz"
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PlayerProperties::class
        ]);
    }
}