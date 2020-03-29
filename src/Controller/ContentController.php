<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\Headword;
use App\Entity\User;
use App\Form\AddContentType;
use App\Form\ChangeContentType;
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
     * @Route("/content/add", name="add_content")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function addContentAction(Request $request)
    {
        $content = new Content;

        $addContentForm = $this->createForm(AddContentType::class, $content);
        $addContentForm->handleRequest($request);

        if ($addContentForm->isSubmitted() && $addContentForm->isValid()) {

            $user = $this->getUser();
            $content->author = $user;

            $this->entityManager->persist($content);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('view_content'));
        }

        return ["addContentForm" => $addContentForm->createView()
        ];
    }

    /**
     * @Route("/content/view", name="view_content")
     * @Template()
     */
    public function listContentAction()
    {
        $repository = $this->entityManager->getRepository(Content::class);
        $contents = $repository->findAll();

        $user = $this->getUser();

        return ['contents' => $contents,
            'user' => $user];
    }

    /**
     * @Route("/content/view/headword/{id}", name="content_sorted_by_headword")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return array
     */

    public function listContentByHeadwordAction(Request $request)
    {
        $headwordId = $request->attributes->get('id');

        $repository = $this->entityManager->getRepository(Headword::class);
        $headword = $repository->findBy( [
            'id' => $headwordId
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
            'user' => $user];
    }

    /**
     * @Route("/content/change/{id}", name="change_content")
     * @Template()
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function changeContentAction(Request $request)
    {
        $repository = $this->entityManager->getRepository(Content::class);
        $id = $request->attributes->get('id');
        $content = $repository->find($id);

        $changeContentForm = $this->createForm(ChangeContentType::class, $content);
        $changeContentForm->handleRequest($request);


        if ($changeContentForm->isSubmitted() && $changeContentForm->isValid()) {

            $this->entityManager->persist($content);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('view_content'));

        }

        return ["changeContentForm" => $changeContentForm->createView(),
            "content" => $content
        ];

    }
}