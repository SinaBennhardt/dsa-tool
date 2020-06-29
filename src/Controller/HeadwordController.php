<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Content;
use App\Entity\Headword;
use App\Form\ChangeHeadwordType;
use App\Form\DeleteConfirmationType;
use App\Form\HeadwordType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HeadwordController extends AbstractController
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
     * @Route("/campaign/{campaignId}/headword/change", name="change_headword")
     * @Template()
     * @param $campaignId
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function changeHeadwordAction(Request $request, $campaignId)
    {
        $repository = $this->entityManager->getRepository(Campaign::class);
        $campaign = $repository->find($campaignId);

        $repository = $this->entityManager->getRepository(Headword::class);
        $headwords = $repository->findHeadwordsByCampaign($campaignId);

        $ChangeHeadwordForm = $this->createForm(ChangeHeadwordType::class, [
            'headwords' => $headwords
        ]);
        $ChangeHeadwordForm->handleRequest($request);

        if ($ChangeHeadwordForm->isSubmitted() && $ChangeHeadwordForm->isValid()) {

            $this->entityManager->flush();
            return new RedirectResponse($this->router->generate('change_headword'));
        }

        return ['headwords' => $headwords,
            "HeadwordForm" => $ChangeHeadwordForm->createView()];
    }

    /**
     * @Route("campaign/{campaignId}/headword/add", name="add_headword")
     * @Template()
     * @param $campaignId
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function addHeadwordAction(Request $request, $campaignId)
    {
        $headword = new Headword;

        $HeadwordForm = $this->createForm(HeadwordType::class, $headword);
        $HeadwordForm->handleRequest($request);

        if ($HeadwordForm->isSubmitted() && $HeadwordForm->isValid()) {
            $repository = $this->entityManager->getRepository(Campaign::class);
            $campaign = $repository->find($campaignId);

            $headword->setCampaign($campaign);

            $this->entityManager->persist($headword);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('add_headword'));
        }

        $repository = $this->entityManager->getRepository(Headword::class);
        $headwords = $repository->findHeadwordsByCampaign($campaignId);

        return ["HeadwordForm" => $HeadwordForm->createView(),
            'headwords' => $headwords];

    }


    /**
     * @Route("campaign/{campaignId}/headword/delete", name="delete_headword")
     * @Template()
     * @param Request $request
     * @param $campaignId
     * @return array|RedirectResponse
     */
    public function deleteHeadwordAction(Request $request, $campaignId)
    {
        $repository = $this->entityManager->getRepository(Headword::class);
        $headwords = $repository->findHeadwordsByCampaign($campaignId);

        return [
            'headwords' => $headwords
        ];
    }

    /**
     * @Route("campaign/{campaignId}/headword/{headwordId}/delete/", name="delete_headword_confirmation")
     * @Template()
     * @param $headwordId
     * @param $campaignId
     * @param Request $request
     * @return array|RedirectResponse
     */

    public function deleteHeadwordConfirmationAction(Request $request, $headwordId, $campaignId)
    {
        $repository = $this->entityManager->getRepository(Headword::class);
        $headword = $repository->find($headwordId);

        $deleteConfirmationForm = $this->createForm(DeleteConfirmationType::class);
        $deleteConfirmationForm->handleRequest($request);


        if ($deleteConfirmationForm->isSubmitted() && $deleteConfirmationForm->isValid()) {

            $builder = $this->entityManager->createQueryBuilder();
            $builder->select('content');
            $builder->from(Content::class, 'content');
            $builder->join('content.headwords', 'headword');
            $builder->where('headword.id = :headword');
            $builder->setParameter('headword', $headword);

            /** @var Content[] $contentList */
            $contentList = $builder->getQuery()->getResult();

            foreach ($contentList as $content) {
                $content->getHeadwords()->removeElement($headword);
            }

            $this->entityManager->remove($headword);

            $this->addFlash('success',
                sprintf('Du hast das Schlagwort "%s" erfolgreich gelöscht.', $headword->getHeadwordName()));

            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('delete_headword'));
        }

        return ['headword' => $headword,
            "deleteConfirmationForm" => $deleteConfirmationForm->createView()
        ];
    }


    /**
     * @Template()
     * @param Request $request
     * @return array
     */
    public function getHeadwords(Request $request)
    {
        $campaignId = $request->attributes->get('campaignId');

        $repository = $this->entityManager->getRepository(Headword::class);
        $headwords = $repository->findHeadwordsByCampaign($campaignId);

        return [
            'headwords' => $headwords
        ];
    }

}
