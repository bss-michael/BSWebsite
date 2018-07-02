<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LoginController extends Controller
{




    /**
     * @Route("/login", name="login")
     */
    public function index(Request $req, AuthenticationUtils $authUtils)
    {

        $err = $authUtils->getLastAuthenticationError();
        $username = $authUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'lastUsername' => $username,
            'error' => $err
        ]);
    }
}
