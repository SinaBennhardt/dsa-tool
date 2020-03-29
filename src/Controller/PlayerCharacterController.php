<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\Headword;
use App\Entity\PlayerCharacterInfo;
use App\Entity\PlayerProperties;
use App\Entity\User;
use App\Form\PlayerCharacterType;
use App\Form\PCPropertyType;
use phpDocumentor\Reflection\DocBlock\Tags\Author;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlayerCharacterController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    private $router;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route("/character", name="character_sheet")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return array
     */
    public function CharacterSheetAction(Request $request)
    {
        $user = $this->getUser();

        $repository = $this->entityManager->getRepository(PlayerCharacterInfo::class);
        $character = $repository->findBy(
            ['playerType' => 'Held']
        );


        return ["character" => $character,
            "user" => $user];
    }


    /**
     * @Route("/character/creation", name="create_new_character")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return array|Response
     */
    public function CreateNewCharacterAction(Request $request)
    {
        $user = $this->getUser();
        $characterInfo = new PlayerCharacterInfo();
        $characterProperties = new PlayerProperties();

        $characterInfo->playerProperty = $characterProperties;
        $characterInfo->user = $user;

        $addPlayerCharacterForm = $this->createForm(PlayerCharacterType::class, $characterInfo);
        $addPlayerCharacterForm->handleRequest($request);

        if ($addPlayerCharacterForm->isSubmitted() && $addPlayerCharacterForm->isValid()) {

            $this->entityManager->persist($characterInfo);
            $this->entityManager->persist($characterProperties);
            $this->entityManager->flush();

            $this->addFlash('success', 'Charakter wurde erfolgreich erstellt.');

            return $this->redirectToRoute('character_sheet');
        }

        return ["addPlayerCharacterForm" => $addPlayerCharacterForm->createView()];
    }

    /**
     * @Route("/character/change/{id}", name="change_character")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return array
     */

    public function ChangeCharacterAction(Request $request)
    {

        $user = $this->getUser();
        $repository = $this->entityManager->getRepository(PlayerCharacterInfo::class);
        $id = $request->attributes->get('id');
        $characterInfo = $repository->find($id);

        $addPlayerCharacterForm = $this->createForm(PlayerCharacterType::class, $characterInfo);
        $addPlayerCharacterForm->handleRequest($request);

        if ($addPlayerCharacterForm->isSubmitted() && $addPlayerCharacterForm->isValid()) {

            $this->entityManager->persist($characterInfo);
            //$this->entityManager->persist($characterProperties);
            $this->entityManager->flush();
        }

        return ["addPlayerCharacterForm" => $addPlayerCharacterForm->createView(),
        ];
    }

    /**
     * @Route("/nsc", name="all_nsc")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return array
     */

    public function ListAllNscAction(Request $request)
    {
        $user = $this->getUser();

        $repository = $this->entityManager->getRepository(PlayerCharacterInfo::class);
        $character = $repository->findBy(
            [
                'playerType' => 'NSC'
            ]

        );


        return ["character" => $character,
            "user" => $user];
    }


    /**
     * @Route("/nsc/create", name="new_nsc")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return array
     */

    public function CreateNewNscAction(Request $request)
    {
        $user = $this->getUser();
        $characterInfo = new PlayerCharacterInfo();
        $characterProperties = new PlayerProperties();

        $characterInfo->playerProperty = $characterProperties;
        $characterInfo->user = $user;

        $addPlayerCharacterForm = $this->createForm(PlayerCharacterType::class, $characterInfo);
        $addPlayerCharacterForm->handleRequest($request);

        if ($addPlayerCharacterForm->isSubmitted() && $addPlayerCharacterForm->isValid()) {

            $characterInfo->playerType = 'NSC';
            $this->entityManager->persist($characterInfo);
            $this->entityManager->persist($characterProperties);
            $this->entityManager->flush();
        }


        return ["addPlayerCharacterForm" => $addPlayerCharacterForm->createView(),
        ];
    }
}
