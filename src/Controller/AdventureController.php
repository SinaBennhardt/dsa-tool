<?php

namespace App\Controller;

use App\Entity\Adventure;
use App\Entity\Campaign;
use App\Form\AddAdventureType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdventureController extends AbstractController
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
     * @Route("/campaign/{id}/adventures/add", name="add_adventures")
     * @Template()
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param $id
     * @return array|RedirectResponse
     */

    public function addAdventureAction (Request $request, $id) {
        $adventure = new Adventure();

        $addAdventureForm = $this->createForm(AddAdventureType::class, $adventure);
        $addAdventureForm->handleRequest($request);

        if ($addAdventureForm->isSubmitted() && $addAdventureForm->isValid()){
            $user = $this->getUser();
            $adventure->setAuthor($user);

            $repository = $this->entityManager->getRepository(Campaign::class);
            $campaign = $repository->find($id);

            $adventure->setCampaign($campaign);

            $this->entityManager->persist($adventure);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('change_campaign', ['id' => $id]));
        }

        return [
            'addAdventureForm' => $addAdventureForm->createView(),
            'campaign_id' => $id
        ];
    }


    /**
     * @Route("/campaign/{campaignId}/adventures/{adventureId}/change", name="change_adventures")
     * @Template()
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param $campaignId
     * @param $adventureId
     * @return RedirectResponse|array
     */

    public function changeAdventureAction(Request $request, $campaignId, $adventureId) {
        $repository = $this->entityManager->getRepository(Adventure::class);
        $adventure = $repository->find($adventureId);

        $changeAdventureForm = $this->createForm(AddAdventureType::class, $adventure);
        $changeAdventureForm->handleRequest($request);

        if ($changeAdventureForm->isSubmitted() && $changeAdventureForm->isValid()){

            $this->entityManager->persist($adventure);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('change_campaign', ['id' => $campaignId]));
        }

        return [
            'changeAdventureForm' => $changeAdventureForm->createView(),
            'title' => $adventure->getTitle(),
            'campaignId' => $campaignId
        ];
    }
}
