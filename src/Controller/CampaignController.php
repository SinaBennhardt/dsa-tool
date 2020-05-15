<?php

namespace App\Controller;


use App\Entity\Adventure;
use App\Entity\Campaign;
use App\Entity\PlayerCharacterInfo;
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

        if ($addCampaignForm->isSubmitted() && $addCampaignForm->isValid()) {

            $user = $this->getUser();
            $campaign->setAuthor($user);

            $this->addFlash('success',
                sprintf('Die Kampagne "%s" wurde erfolgreich erstellt.', $campaign->getTitle()));

            $this->entityManager->persist($campaign);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('change_campaign', ['campaignId' => $campaign->getId()]));
        }

        return ["addCampaignForm" => $addCampaignForm->createView()];
    }

    /**
     * @Route("/campaigns/{campaignId}/change", name="change_campaign")
     * @Template()
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param $campaignId
     * @return array|RedirectResponse
     */
    public function changeCampaignAction(Request $request, $campaignId)
    {

        $repository = $this->entityManager->getRepository(Campaign::class);
        $campaign = $repository->find($campaignId);

        $repository = $this->entityManager->getRepository(Adventure::class);

        /** @var Adventure[] $adventures */
        $adventures = $repository->findBy([
                'campaign' => $campaign
            ]
        );

        /** @var PlayerCharacterInfo[] $playerCharacters */
        $repository = $this->entityManager->getRepository(PlayerCharacterInfo::class);
        $playerCharacters = $repository->findBy([
           'campaign' =>$campaign
        ]);

        $changeCampaignForm = $this->createForm(ChangeCampaignType::class, $campaign);
        $changeCampaignForm->handleRequest($request);

        if ($changeCampaignForm->isSubmitted() && $changeCampaignForm->isValid()) {

            $this->addFlash('success',
                sprintf('Du hast die Kampagne "%s" angepasst.', $campaign->getTitle()));

            $this->entityManager->persist($campaign);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('change_campaign', ['campaignId' => $campaign->getId()]));
        }

        return [
            'changeCampaignForm' => $changeCampaignForm->createView(),
            'campaign_name' => $campaign->getTitle(),
            'campaignId' => $campaign->getId(),
            'adventures' => $adventures,
            'playerCharacters' => $playerCharacters
        ];

    }

    /**
     * @Route("/campaigns/{campaignId}/", name="display_campaign")
     * @Template()
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param $campaignId
     * @return array|RedirectResponse
     */

    public function displayCampaignAction(Request $request, $campaignId)
    {
        $repository = $this->entityManager->getRepository(Campaign::class);
        $campaign = $repository->find($campaignId);

        $repository = $this->entityManager->getRepository(Adventure::class);

        /** @var Adventure[] $adventures */
        $adventures = $repository->findBy([
                'campaign' => $campaign
            ]
        );

        /** @var PlayerCharacterInfo[] $playerCharacters */
        $repository = $this->entityManager->getRepository(PlayerCharacterInfo::class);
        $playerCharacters = $repository->findBy([
            'campaign' =>$campaign
        ]);

        return [
            'campaign_name' => $campaign->getTitle(),
            'campaignId' => $campaign->getId(),
            'campaign' => $campaign,
            'adventures' => $adventures,
            'playerCharacters' => $playerCharacters
        ];
    }


    /**
     * @Route("/campaign/{campaignId}/delete", name="delete_campaign")
     * @Template()
     * @param Request $request
     * @param $campaignId
     * @return array|RedirectResponse
     */
    public function deleteCampaignConfirmationAction(Request $request, $campaignId)
    {
        $repository = $this->entityManager->getRepository(Campaign::class);
        $campaign = $repository->find($campaignId);

        $deleteCampaignConfirmationForm = $this->createForm(DeleteCampaignConfirmationType::class, []);
        $deleteCampaignConfirmationForm->handleRequest($request);

        if ($deleteCampaignConfirmationForm->isSubmitted() && $deleteCampaignConfirmationForm->isValid()) {

            $repository = $this->entityManager->getRepository(Adventure::class);
            $adventureList = $repository->findBy([
                'campaign' => $campaignId
            ]);

            foreach ($adventureList as $adventure) {
                $this->entityManager->remove($adventure);
            }

            $this->addFlash('success',
                sprintf('Du hast die Kampagne "%s" erfolgreich gelöscht.', $campaign->getTitle()));

            $this->entityManager->remove($campaign);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('campaigns'));
        }

        return ['campaign' => $campaign,
            'id' => $campaignId,
            'deleteCampaignConfirmationForm' => $deleteCampaignConfirmationForm->createView()
        ];
    }

    /**
     * @Template()
     */

    public function getCampaigns()
    {
        $repository = $this->entityManager->getRepository(Campaign::class);
        $campaigns = $repository->findBy([], [
            'title' => 'ASC'
        ]);

        return ['campaigns' => $campaigns];


    }

}