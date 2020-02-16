<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangeEmailType;
use App\Form\ChangeNameType;
use App\Form\ChangePasswordType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProfileController extends AbstractController
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

    private $encoder;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, RouterInterface $router, UserPasswordEncoderInterface $encoder)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->router = $router;
        $this->encoder = $encoder;
    }


    /**
     * @Route("/logout", name="logout")
     * @Template()
     */
    public
    function logoutAction()
    {

    }

    /**
     * @Route("/login", name="login")
     * @Template()
     */
    public
    function loginAction()
    {

    }

    /**
     * @Route("/profile/options", name="profile_options")
     * @Template()
     * @param Request $request
     * @return array
     */

    public
    function profileOptionsAction(Request $request)
    {

        return [];
    }

    /**
     * @Route("/registration", name="registration")
     * @Template()
     * @param Request $request
     * @return array
     */
    public
    function registrationAction(Request $request)
    {
        $user = new User;
        $registerForm = $this->createForm(RegisterType::class, $user);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {

            $user->role = "user";                                   //noch besser machen irgendwie?

            $password = $this->encoder->encodePassword($user, $user->password);
            $user->password = $password;

            $this->entityManager->persist($user);
            $this->entityManager->flush();

        }
        return ["registerForm" => $registerForm->createView()];

    }


    /**
     * @Route("/name/change", name="change_name")
     * @Template()
     * @param Request $request
     * @return array
     */

    public
    function changeNameAction(Request $request)
    {
        $user = $this->getUser();

        $changeNameForm = $this->createForm(ChangeNameType::class, []);
        $changeNameForm->handleRequest($request);

        if ($changeNameForm->isSubmitted() && $changeNameForm->isValid()) {

            $data = $changeNameForm->getData();
            $user->name = $data["name"];

            $this->entityManager->persist($user);
            $this->entityManager->flush();

        }
        return ["changeNameForm" => $changeNameForm->createView()
        ];
    }

    /**
     * @Route("/email/change", name="change_email")
     * @Template()
     * @param Request $request
     * @return array
     */
    public
    function changeEmailAction(Request $request)
    {
        $changeEmailForm = $this->createForm(ChangeEmailType::class, []);
        $changeEmailForm->handleRequest($request);

        if ($changeEmailForm->isSubmitted() && $changeEmailForm->isValid()) {

            $user = $this->getUser();
            $this->entityManager->persist($user);
            $this->entityManager->flush();

        }

        return ["changeEmailForm" => $changeEmailForm->createView()];

    }


    /**
     * @Route("/password/change", name="change_password")
     * @Template()
     * @param Request $request
     * @return array
     */
    public
    function changePasswordAction(Request $request)
    {
        $user = $this->getUser();

        $changePasswordForm = $this->createForm(ChangePasswordType::class, []);
        $changePasswordForm->handleRequest($request);

        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {

            $data = $changePasswordForm->getData();

            $password = $this->encoder->encodePassword($user, $data["password"]);
            $user->password = $password;

            $user = $this->getUser();
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        return ['changePasswordForm' => $changePasswordForm->createView()
        ];

    }

}