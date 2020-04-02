<?php

namespace App\Controller;


use App\Entity\Campaign;
use App\Form\AddCampaignType;
use App\Form\ChangeCampaignType;
use App\Form\DeleteCampaignConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CampaignController extends AbstractController
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
     * @Route("/campaigns", name="campaigns")
     * @Template()
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return array
     */
    public function listAllCampaignsAction(Request $request)
    {
        $repository = $this->entityManager->getRepository(Campaign::class);
        $campaigns = $repository->findAll();

        $user = $this->getUser();

        return ['campaigns' => $campaigns,
            'user' => $user];
    }

    /**
     * @Route("/campaigns/add", name="add_campaigns")
     * @Template()
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return array|RedirectResponse
     */

    public function addCampaignAction(Request $request)
    {
        $campaign = new Campaign();

        $addCampaignForm = $this->createForm(AddCampaignType::class, $campaign);
        $addCampaignForm->handleRequest($request);

        if ($addCampaignForm->isSubmitted() && $addCampaignForm->isValid()){

            $user = $this->getUser();
            $campaign->author = $user;

            $this->entityManager->persist($campaign);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('change_campaign', ['id' => $campaign->id]));
        }

        return ["addCampaignForm" => $addCampaignForm->createView()];
    }

    /**
     * @Route("/campaigns/change/{id}", name="change_campaign")
     * @Template()
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
<<<<<<< Updated upstream
     * @return array
=======
     * @param $id
     * @return array|RedirectResponse
>>>>>>> Stashed changes
     */
    public function changeCampaignAction(Request $request, $id) {

        $repository = $this->entityManager->getRepository(Campaign::class);
        $campaign = $repository->find($id);

        $changeCampaignForm = $this->createForm(ChangeCampaignType::class, $campaign);
        $changeCampaignForm->handleRequest($request);

        return [ 'changeCampaignForm' => $changeCampaignForm->createView(),
            'campaign_name' => $campaign->title];

    }

    /**
     * @Route("/campaign/{id}/delete", name="delete_campaign")
     * @Template()
     * @param Request $request
     * @param $id
     * @return array|RedirectResponse
     */
    public function deleteCampaignConfirmationAction(Request $request, $id)
    {
        $repository = $this->entityManager->getRepository(Campaign::class);
        $campaign = $repository->find($id);

        $deleteCampaignConfirmationForm = $this->createForm(DeleteCampaignConfirmationType::class, []);
        $deleteCampaignConfirmationForm->handleRequest($request);

        if ($deleteCampaignConfirmationForm->isSubmitted() && $deleteCampaignConfirmationForm->isValid()) {

            $repository = $this->entityManager->getRepository(Adventure::class);
            $adventureList = $repository->findBy([
                'campaign' => $id
            ]);

            foreach ($adventureList as $adventure) {
                $this->entityManager->remove($adventure);
            }

            $this->entityManager->remove($campaign);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('campaigns'));
        }

        return ['campaign' => $campaign,
            'id' => $id,
            'deleteCampaignConfirmationForm' => $deleteCampaignConfirmationForm->createView()
        ];
    }



}