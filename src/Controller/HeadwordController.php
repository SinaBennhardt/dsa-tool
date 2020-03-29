<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\Headword;
use App\Form\AddHeadwordType;
use App\Form\ChangeHeadwordType;
use App\Form\DeleteHeadwordConfirmationType;
use App\Form\DeleteHeadwordType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/headword/change", name="change_headword")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function changeHeadwordAction(Request $request)
    {
        $repository = $this->entityManager->getRepository(Headword::class);
        $headwords = $repository->findBy([], [
            'headwordName' => 'ASC'
        ]);

        $changeHeadwordForm = $this->createForm(ChangeHeadwordType::class, [
            'headwords' => $headwords
        ]);
        $changeHeadwordForm->handleRequest($request);


        if ($changeHeadwordForm->isSubmitted() && $changeHeadwordForm->isValid()) {

            $this->entityManager->flush();
            return new RedirectResponse($this->router->generate('change_headword'));

        }

        return ['headwords' => $headwords,
            "changeHeadwordForm" => $changeHeadwordForm->createView()];
    }

    /**
     * @Route("/headword/add", name="add_headword")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function addHeadwordAction(Request $request)
    {
        $headword = new Headword;

        $addHeadwordForm = $this->createForm(AddHeadwordType::class, $headword);
        $addHeadwordForm->handleRequest($request);

        if ($addHeadwordForm->isSubmitted() && $addHeadwordForm->isValid()) {

            $this->entityManager->persist($headword);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('add_headword'));
        }

        $repository = $this->entityManager->getRepository(Headword::class);
        $headwords = $repository->findBy([], [
            'headwordName' => 'ASC'
        ]);

        return ["addHeadwordForm" => $addHeadwordForm->createView(),
            'headwords' => $headwords];

    }


    /**
     * @Route("/headword/delete", name="delete_headword")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function deleteHeadwordAction(Request $request)
    {
        $repository = $this->entityManager->getRepository(Headword::class);
        $headwords = $repository->findBy([], [
            'headwordName' => 'ASC'
        ]);

        return ['headwords' => $headwords
        ];
    }

    /**
     * @Route("/headword/{id}/delete/", name="delete_headword_confirmation")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */

    public function deleteHeadwordConfirmationAction(Request $request)
    {
        $headwordId = $request->attributes->get('id');

        $repository = $this->entityManager->getRepository(Headword::class);
        $headword = $repository->find($headwordId);

        $deleteHeadwordConfirmationForm = $this->createForm(DeleteHeadwordConfirmationType::class);
        $deleteHeadwordConfirmationForm->handleRequest($request);


        if ($deleteHeadwordConfirmationForm->isSubmitted() && $deleteHeadwordConfirmationForm->isValid()) {

            $builder = $this->entityManager->createQueryBuilder();
            $builder->select('content');
            $builder->from(Content::class, 'content');
            $builder->join('content.headwords', 'headword');
            $builder->where('headword.id = :headword');
            $builder->setParameter('headword', $headword);

            /** @var Content[] $contentList */
            $contentList = $builder->getQuery()->getResult();

            foreach ($contentList as $content) {
                $content->headwords->removeElement($headword);
            }

            $this->entityManager->remove($headword);

            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('delete_headword'));
        }

        return ['headword' => $headword,
            "deleteHeadwordConfirmationForm" => $deleteHeadwordConfirmationForm->createView()
        ];
    }


    /**
     * @Template()
     */
    public function getHeadwords()
    {
        $repository = $this->entityManager->getRepository(Headword::class);
        $headwords = $repository->findBy([], [
            'headwordName' => 'ASC'
        ]);

        return ['headwords' => $headwords];
    }

}