<?php

namespace App\Controller;

use App\Entity\Adventure;
use App\Entity\Campaign;
use App\Form\AdventureType;
use App\Form\DeleteAdventureConfirmationType;
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
     * @Route("/campaign/{campaignId}/adventures/add", name="add_adventures")
     * @Template()
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param $campaignId
     * @return array|RedirectResponse
     */

    public function addAdventureAction (Request $request, $campaignId) {
        $adventure = new Adventure();

        $AdventureForm = $this->createForm(AdventureType::class, $adventure);
        $AdventureForm->handleRequest($request);

        if ($AdventureForm->isSubmitted() && $AdventureForm->isValid()){
            $user = $this->getUser();
            $adventure->setAuthor($user);

            $repository = $this->entityManager->getRepository(Campaign::class);
            $campaign = $repository->find($id);

            $adventure->setCampaign($campaign);

            $this->entityManager->persist($adventure);
            $this->entityManager->flush();

            $this->addFlash('success',
                sprintf('Das Abenteuer "%s" wurde erfolgreich erstellt.', $adventure->getTitle()));

            return new RedirectResponse($this->router->generate('change_campaign', ['id' => $id]));
        }

        return [

            'AdventureForm' => $AdventureForm->createView(),
            'campaignId' => $campaignId
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

        $AdventureForm = $this->createForm(AdventureType::class, $adventure);
        $AdventureForm->handleRequest($request);

        if ($AdventureForm->isSubmitted() && $AdventureForm->isValid()){

            $this->entityManager->persist($adventure);
            $this->entityManager->flush();

            $this->addFlash('success',
                sprintf('Das Abenteuer "%s" wurde angepasst.', $adventure->getTitle()));

            return new RedirectResponse($this->router->generate('change_campaign', ['id' => $campaignId]));
        }

        return [
            'AdventureForm' => $AdventureForm->createView(),
            'title' => $adventure->getTitle(),
            'campaignId' => $campaignId
        ];
    }


    /**
     * @Route("/campaign/{id}/adventures/delete", name="delete_adventure")
     * @Template()
     * @param Request $request
     * @param $id
     * @return array
     */
    public function deleteAdventureAction(Request $request, $id)
    {
        $repository = $this->entityManager->getRepository(Adventure::class);

        /** @var Adventure[] $adventures */
        $adventures = $repository->findBy([
            'campaign' => $id
        ]);

        return ['adventures' => $adventures,
            'id' => $id
            ];
    }

    /**
     * @Route("/campaign/{id}/adventures/delete/{adventureId}", name="delete_adventure_confirmation")
     * @Template()
     * @param Request $request
     * @param $id
     * @param $adventureId
     * @return array|RedirectResponse
     */

    public function deleteAdventureConfirmationAction(Request $request, $id, $adventureId)
    {
        $repository = $this->entityManager->getRepository(Adventure::class);
        $adventure = $repository->find($adventureId);

        $deleteAdventureConfirmationForm = $this->createForm(DeleteAdventureConfirmationType::class);
        $deleteAdventureConfirmationForm->handleRequest($request);

        if ($deleteAdventureConfirmationForm->isSubmitted() && $deleteAdventureConfirmationForm->isValid()) {

            $this->addFlash('success',
                sprintf('Das Abenteuer "%s" wurde erfolgreich gelöscht.', $adventure->getTitle()));

            $this->entityManager->remove($adventure);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('delete_adventure', ['id' => $id]));
        }

        return ['adventure' => $adventure,
            'id' => $id,
            "deleteAdventureConfirmationForm" => $deleteAdventureConfirmationForm->createView()
        ];
    }


}
