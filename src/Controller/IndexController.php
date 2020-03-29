<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\Headword;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="home")
     * @Route("/", name="app_index_home")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function homeAction(Request $request)
    {
        $user = $this->getUser();

        if ($user !== null) {

        $name = $user->getName();

        return ['name' => $name];
        }


        /*
        $user = new User();
        $user->name = 'Sina';

        $this->entityManager->persist($user);
        $this->entityManager->flush();


        $user = $this->entityManager->find(User::class, 1);
        $user->name = 'David';
        $this->entityManager->flush();
        */
        //$this->entityManager->getRepository(User::class)->findAll(); // gib alle zurück
        //$this->entityManager->getRepository(User::class)->findBy(['name' => 'David']); // gib alle mit $name === 'David' zurück
    }

}
