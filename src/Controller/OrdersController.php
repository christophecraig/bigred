<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Orders;
use App\Form\OrdersType;
use App\Repository\OrdersRepository;
use DateTimeZone;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orders")
 */
class OrdersController extends AbstractController
{
    /**
     * @Route("/", name="orders_index", methods={"GET"})
     */
    public function index(OrdersRepository $ordersRepository): Response
    {
        return $this->render('orders/index.html.twig', [
            'orders' => $ordersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/all", name="orders_all", methods={"GET"})
     */
    public function showAll(OrdersRepository $ordersRepository): Response
    {
        return $this->json(['orders' => $ordersRepository->findAll()]);
    }

    /**
     * @Route("/my-orders", name="orders_byclient", methods={"GET"})
     */
    public function showById(OrdersRepository $ordersRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');
        return $this->render('orders/index.html.twig', [
            'orders' => $ordersRepository->findByClient($this->getUser()),
        ]);
    }

    /**
     * @Route("/new", name="orders_new", methods={"GET","POST"})
     */
    public function new(Request $request, MailerInterface $mailer): Response
    {
        $order = new Orders();
        if ($request->get('date') && $request->get('time')) {
            $order->setDeliveryDate(new \DateTime($request->get('date')));
            $order->setDeliveryTime($request->get('time'));
        }
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setClient($this->getUser());
            $order->setOrderDate(
                new \DateTime('now', new DateTimeZone('Pacific/Auckland'))
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            $email = (new TemplatedEmail())
                ->from(new Address('bigred@christophecraig.com', 'Big Red'))
                ->to($this->getUser()->getUsername())
                ->subject('Your order is now waiting for confirmation!')
                ->htmlTemplate('email/placedOrder.html.twig')
                ->context([
                    'order' => $order,
                    'user' => $this->getUser(),
                ]);
            $mailer->send($email);

            return $this->redirectToRoute('home');
        }

        return $this->render('orders/new.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="orders_show", methods={"GET"})
     */
    public function show(Orders $order): Response
    {
        return $this->render('orders/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="orders_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Orders $order): Response
    {
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('orders_index');
        }

        return $this->render('orders/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="orders_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Orders $order): Response
    {
        if (
            $this->isCsrfTokenValid(
                'delete' . $order->getId(),
                $request->request->get('_token')
            )
        ) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('orders_index');
    }
}
