<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\Headword;
use App\Form\AddHeadwordType;
use App\Form\ChangeHeadwordType;
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
        $headwords = $repository->findAll();

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
        $headwords = $repository->findAll();

        return [ "addHeadwordForm" => $addHeadwordForm->createView(),
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
        $headwords = $repository->findAll();

        $deleteHeadwordForm = $this->createForm(DeleteHeadwordType::class, [
            'headwords' => $headwords
        ]);
        $deleteHeadwordForm->handleRequest($request);

        if ($deleteHeadwordForm->isSubmitted() && $deleteHeadwordForm->isValid()) {

          dump($headwords);
          die;

            return new RedirectResponse($this->router->generate('delete_headword'));
        }

        return [ "deleteHeadwordForm" => $deleteHeadwordForm->createView(),
            'headwords' => $headwords];
    }

    /**
     * @Template()
     */
    public function getHeadwords() {
        $repository = $this->entityManager->getRepository(Headword::class);
        $headwords = $repository->findAll();

        return ['headwords' => $headwords];
    }

}