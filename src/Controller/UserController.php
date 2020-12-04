<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Entity\User;
use App\Form\RegisterType;

class UserController extends AbstractController
{
    /**
     * con esto ya no es necesario poner la ruta en el routes.yaml
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        //crear formulario
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        //rellenar el objeto con los datos del form
        $form->handleRequest($request);

        //comrobar si el form se ha enviado
        if ($form->isSubmitted() && $form->isValid()) {
            //modificar el objeto para guardarlo
            $user->setRole('ROLE_USER');
            $date_now = (new \DateTime(null, new \DateTimeZone('America/Mexico_City')));//indispensable poner la zona
            $user->setCreatedAt($date_now);

            //cifrar contraseña
            $encoded = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            //guardar usuario
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('tasks');

        }


        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    public function login(AuthenticationUtils $authenticationUtils){
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig',[
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }
}
