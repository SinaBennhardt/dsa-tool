<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Content;
use App\Entity\Headword;
use App\Form\ContentType;
use App\Form\DeleteConfirmationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContentController extends AbstractController
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
     * @Route("/campaign/{campaignId}/content/add", name="add_content")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param $campaignId
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function addContentAction(Request $request, $campaignId)
    {
        $content = new Content();
        $repository = $this->entityManager->getRepository(Campaign::class);
        $campaign = $repository->find(
            [
            'id' => $campaignId
            ]
        );

        $ContentForm = $this->createForm(ContentType::class, $content);
        $ContentForm->handleRequest($request);

        if ($ContentForm->isSubmitted() && $ContentForm->isValid()) {
            $user = $this->getUser();
            $content->setAuthor($user);
            $content->setCampaign($campaign);

            $this->entityManager->persist($content);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('view_content'));
        }

        return ["ContentForm" => $ContentForm->createView()
        ];
    }

    /**
     * @Route("/campaign/{campaignId}/content/view", name="view_content")
     * @param Request $request
     * @param $campaignId
     * @return array
     * @Template()
     */
    public function listContentAction(Request $request, $campaignId)
    {
        $repository = $this->entityManager->getRepository(Content::class);
        $contents = $repository->findBy([
            'campaign' => $campaignId
        ]);

        $user = $this->getUser();

        return ['contents' => $contents,
            'user' => $user];
    }

    /**
     * @Route("/campaign/{campaignId}/content/view/headword/{headwordId}", name="content_sorted_by_headword")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param $campaignId
     * @param $headwordId
     * @param Request $request
     * @return array
     */

    public function listContentByHeadwordAction(Request $request, $campaignId, $headwordId)
    {
        $repository = $this->entityManager->getRepository(Headword::class);
        $headword = $repository->findBy([
            'id' => $headwordId,
            'campaign' => $campaignId
        ]);

        $builder = $this->entityManager->createQueryBuilder();
        $builder->select('c');
        $builder->from(Content::class, 'c');
        $builder->join('c.headwords', 'h');
        $builder->where('h.id = :headword');
        $builder->setParameter('headword', $headwordId);

        $contents = $builder->getQuery()->getResult();

        $user = $this->getUser();

        return ['contents' => $contents,
            'headword' => $headword[0],
            'campaignId' => $campaignId,
            'user' => $user];
    }

    /**
     * @Route("campaign/{campaignId}/content/change/{contentId}", name="change_content")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param $campaignId
     * @param $contentId
     * @return array|RedirectResponse
     */
    public function changeContentAction(Request $request, $contentId, $campaignId)
    {
        $repository = $this->entityManager->getRepository(Content::class);
        $content = $repository->find($contentId);

        $ContentForm = $this->createForm(ContentType::class, $content);
        $ContentForm->handleRequest($request);

        if ($ContentForm->isSubmitted() && $ContentForm->isValid()) {
            $this->addFlash(
                'success',
                sprintf('Du hast den Eintrag "%s" geändert.', $content->getTitle())
            );

            $this->entityManager->persist($content);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('view_content'));
        }

        return ["ContentForm" => $ContentForm->createView(),
            "content" => $content,
            "contentId" => $contentId,
            'campaignId' => $campaignId
        ];
    }

    /**
     * @Route("/campaign/{campaignId}/content/delete/{contentId}", name="delete_content")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param $contentId
     * @param $campaignId
     * @return array|RedirectResponse
     */

    public function deleteContentConfirmationAction(Request $request, $contentId, $campaignId)
    {
        $repository = $this->entityManager->getRepository(Content::class);
        $content = $repository->find($contentId);

        $deleteConfirmationForm = $this->createForm(DeleteConfirmationType::class);
        $deleteConfirmationForm->handleRequest($request);

        if ($deleteConfirmationForm->isSubmitted() && $deleteConfirmationForm->isValid()) {
            $this->addFlash(
                'success',
                sprintf('Du hast den Eintrag "%s" gelöscht.', $content->getTitle())
            );

            $this->entityManager->remove($content);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('view_content'));
        }


        return [
            'deleteConfirmationForm' => $deleteConfirmationForm->createView(),
            'content' => $content,
            'contentId' => $contentId
        ];
    }
}
