<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\Headword;
use App\Entity\PlayerCharacterInfo;
use App\Entity\PlayerProperties;
use App\Entity\User;
use App\Form\DeleteCharacterType;
use App\Form\DeleteNscType;
use App\Form\PlayerCharacterType;
use App\Form\PCPropertyType;
use Doctrine\ORM\Mapping\Id;
use phpDocumentor\Reflection\DocBlock\Tags\Author;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
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

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->router = $router;
    }

    /**
     * @Route("/campaign/{campaignId}/character", name="character_sheet")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param $campaignId
     * @param Request $request
     * @return array
     */
    public function CharacterSheetAction(Request $request, $campaignId)
    {
        $user = $this->getUser();

        $repository = $this->entityManager->getRepository(PlayerCharacterInfo::class);
        $character = $repository->findBy(
            ['playerType' => 'Held',
                'campaign' => $campaignId]
        );

        return ["character" => $character,
            "user" => $user,
            'campaignId' => $campaignId];
    }


    /**
     * @Route("/campaign/{campaignId}/character/creation", name="create_new_character")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param $campaignId
     * @param Request $request
     * @return array|Response
     */
    public function CreateNewCharacterAction(Request $request, $campaignId)
    {
        $user = $this->getUser();
        $characterInfo = new PlayerCharacterInfo();
        $characterProperties = new PlayerProperties();

        $characterInfo->setPlayerProperty($characterProperties);
        $characterInfo->setUser($user);

        $addPlayerCharacterForm = $this->createForm(PlayerCharacterType::class, $characterInfo);
        $addPlayerCharacterForm->handleRequest($request);

        if ($addPlayerCharacterForm->isSubmitted() && $addPlayerCharacterForm->isValid()) {

            $characterInfo->setCampaign($campaignId);
            $this->entityManager->persist($characterInfo);
            $this->entityManager->persist($characterProperties);
            $this->entityManager->flush();

            $this->addFlash('success',
                sprintf('Dein Held "%s" wurde erfolgreich erstellt.', $characterInfo->getCharacterName()));

            return $this->redirectToRoute('character_sheet');
        }

        return ["addPlayerCharacterForm" => $addPlayerCharacterForm->createView()];
    }

    /**
     * @Route("/campaign/{campaignId}/character/change/{characterId}", name="change_character")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param $characterId
     * @param $campaignId
     * @return array
     */

    public function ChangeCharacterAction(Request $request, $characterId, $campaignId)
    {
        $repository = $this->entityManager->getRepository(PlayerCharacterInfo::class);
        $characterInfo = $repository->find($characterId);

        $user = $this->getUser();

        $addPlayerCharacterForm = $this->createForm(PlayerCharacterType::class, $characterInfo);
        $addPlayerCharacterForm->handleRequest($request);

        if ($addPlayerCharacterForm->isSubmitted() && $addPlayerCharacterForm->isValid()) {

            $this->addFlash('success',
                sprintf('Dein Held "%s" wurde angepasst.', $characterInfo->getCharacterName()));

            $this->entityManager->persist($characterInfo);
            $this->entityManager->flush();
        }

        return ["addPlayerCharacterForm" => $addPlayerCharacterForm->createView(),
            'characterId' => $characterId,
            'user' => $user,
            'characterInfo' => $characterInfo
        ];
    }

    /**
     * @Route("/campaign/{campaignId}/character/delete/{characterId}", name="delete_character")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param $characterId
     * @param $campaignId
     * @return array|RedirectResponse
     */

    public function deleteCharacterAction(Request $request, $characterId, $campaignId)
    {
        $repository = $this->entityManager->getRepository(PlayerCharacterInfo::class);
        $character = $repository->find($characterId);

        $deleteCharacterForm = $this->createForm(DeleteCharacterType::class);
        $deleteCharacterForm->handleRequest($request);

        if ($deleteCharacterForm->isSubmitted() && $deleteCharacterForm->isValid()) {

            $repository = $this->entityManager->getRepository(PlayerProperties::class);
            $characterProperties = $repository->find($characterId);

            $this->addFlash('success',
                sprintf('Der Held "%s" wurde erfolgreich gelöscht.', $character->getCharacterName()));

            $this->entityManager->remove($character);
            $this->entityManager->remove($characterProperties);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('character_sheet'));
        }

        return [
            'deleteCharacterForm' => $deleteCharacterForm->createView(),
            'characterId' => $characterId,
            'character' => $character
        ];
    }


    /**
     * @Route("campaign/{campaignId}/nsc", name="all_nsc")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param $campaignId
     * @param Request $request
     * @return array
     */

    public function ListAllNscAction(Request $request, $campaignId)
    {
        $user = $this->getUser();

        $repository = $this->entityManager->getRepository(PlayerCharacterInfo::class);
        $character = $repository->findBy(
            ['playerType' => 'NSC',
                'campaign' => $campaignId
            ]
        );

        return ["character" => $character,
            "user" => $user,
            'campaignId' => $campaignId];
    }


    /**
     * @Route("/campaign/{campaignId}/nsc/create", name="new_nsc")
     * @Template()
     * @IsGranted("ROLE_ADMIN")
     * @param $campaignId
     * @param Request $request
     * @return array|RedirectResponse
     */

    public function CreateNewNscAction(Request $request, $campaignId)
    {
        $user = $this->getUser();
        $characterInfo = new PlayerCharacterInfo();
        $characterProperties = new PlayerProperties();

        $characterInfo->setPlayerProperty($characterProperties);
        $characterInfo->setUser($user);

        $addPlayerCharacterForm = $this->createForm(PlayerCharacterType::class, $characterInfo);
        $addPlayerCharacterForm->handleRequest($request);

        if ($addPlayerCharacterForm->isSubmitted() && $addPlayerCharacterForm->isValid()) {

            $characterInfo->setPlayerType('NSC');
            $characterInfo->setCampaign($campaignId);

            $this->addFlash('success',
                sprintf('Der NSC "%s" wurde erfolgreich erstellt.', $characterInfo->getCharacterName()));

            $this->entityManager->persist($characterInfo);
            $this->entityManager->persist($characterProperties);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('all_nsc'));
        }

        return ["addPlayerCharacterForm" => $addPlayerCharacterForm->createView(),
        ];
    }

    /**
     * @Route("campaign/{campaignId}/nsc/delete/{nscId}", name="delete_nsc")
     * @Template()
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param $campaignId
     * @param $nscId
     * @return array|RedirectResponse
     */

    public function deleteNscAction(Request $request, $nscId, $campaignId)
    {
        $repository = $this->entityManager->getRepository(PlayerCharacterInfo::class);
        $nsc = $repository->find($nscId);

        $deleteNscForm = $this->createForm(DeleteNscType::class);
        $deleteNscForm->handleRequest($request);

        if ($deleteNscForm->isSubmitted() && $deleteNscForm->isValid()) {

            $repository = $this->entityManager->getRepository(PlayerProperties::class);
            $nscProperties = $repository->find($id);

            $this->addFlash('success',
                sprintf('Der NSC "%s" wurde erfolgreich gelöscht.', $nsc->getCharacterName()));

            $this->entityManager->remove($nsc);
            $this->entityManager->remove($nscProperties);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('all_nsc'));
        }

        return [
            'deleteNscForm' => $deleteNscForm->createView(),
            'nscId' => $nscId,
            'nsc' => $nsc
        ];
    }
}
