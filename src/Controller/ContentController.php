<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Content;
use App\Entity\Headword;
use App\Entity\User;
use App\Form\AddContentType;
use App\Form\ChangeContentType;
use App\Form\DeleteContentConfirmationType;
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
        $campaign = $repository->find( [
            'id' => $campaignId
            ]
        );

        $addContentForm = $this->createForm(AddContentType::class, $content);
        $addContentForm->handleRequest($request);

        if ($addContentForm->isSubmitted() && $addContentForm->isValid()) {

            $user = $this->getUser();
            $content->setAuthor($user);
            $content->setCampaign($campaign);

            $this->entityManager->persist($content);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('view_content'));
        }

        return ["addContentForm" => $addContentForm->createView()
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
     * @param Request $request
     * @return array
     */

    public function listContentByHeadwordAction(Request $request, $campaignId)
    {
        $headwordId = $request->attributes->get('headwordId');

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

        $changeContentForm = $this->createForm(ChangeContentType::class, $content);
        $changeContentForm->handleRequest($request);

        if ($changeContentForm->isSubmitted() && $changeContentForm->isValid()) {

            $this->addFlash('success',
                sprintf('Du hast den Eintrag "%s" geändert.', $content->getTitle()));

            $this->entityManager->persist($content);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('view_content'));
        }

        return ["changeContentForm" => $changeContentForm->createView(),
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

    public function deleteContentAction(Request $request, $contentId, $campaignId)
    {
        $repository = $this->entityManager->getRepository(Content::class);
        $content = $repository->find($contentId);

        $deleteContentConfirmationForm = $this->createForm(DeleteContentConfirmationType::class);
        $deleteContentConfirmationForm->handleRequest($request);

        if ($deleteContentConfirmationForm->isSubmitted() && $deleteContentConfirmationForm->isValid()) {

            $this->addFlash('success',
                sprintf('Du hast den Eintrag "%s" gelöscht.', $content->getTitle()));

            $this->entityManager->remove($content);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('view_content'));
        }


        return [
            'deleteContentConfirmationForm' => $deleteContentConfirmationForm->createView(),
            'content' => $content,
            'contentId' => $contentId
        ];
    }

}