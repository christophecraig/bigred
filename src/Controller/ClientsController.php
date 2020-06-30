<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Form\ClientsType;
use App\Repository\ClientsRepository;
use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/clients")
 */
class ClientsController extends AbstractController
{
    /**
     * @Route("/", name="clients_index", methods={"GET"})
     */
    public function index(ClientsRepository $clientsRepository): Response
    {
        return $this->render('clients/index.html.twig', [
            'clients' => $clientsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="clients_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $client = new Clients();
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('clients_index');
        }

        return $this->render('clients/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/", name="show_account", methods={"GET"})
     */
    public function show(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');
        $client = $this->getUser();
        return $this->render('clients/show.html.twig', [
            'client' => $client,
        ]);
    }

    /**
     * @Route("/edit", name="clients_edit", methods={"GET","POST"})
     */
    public function edit(Request $request): Response
    {
        $client = $this->getUser();
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('show_account');
        }

        return $this->render('clients/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="clients_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Clients $client): Response
    {
        if (
            $this->isCsrfTokenValid(
                'delete' . $client->getId(),
                $request->request->get('_token')
            )
        ) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('clients_index');
    }

    /**
     * @Route("/change-password", name="change_password", methods={"GET","POST"})
     */
    public function changePassword(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');
        $user = $this->getUser();

        $form = $this->createFormBuilder($user)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'New Password'],
                'second_options' => ['label' => 'Repeat New Password'],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'button'],
            ])
            ->getForm();

        $form->handleRequest($request);
        // TODO : process form

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $user->setPassword(
        //         $passwordEncoder->encodePassword($form->get('password'), $user)
        //     );
        // }
        return $this->render('clients/change_password.html.twig', [
            'client' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/orders", name="my_orders", methods={"GET"})
     */
    public function getOrders(
        Request $request,
        OrdersRepository $ordersRepository
    ) {
        $client = $this->getUser();
        $orders = $ordersRepository->findByClient($client);
        return $this->render('orders/index.html.twig', [
            'orders' => $orders,
        ]);
    }
}