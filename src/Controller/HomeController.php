<?php

namespace App\Controller;

use App\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getRepository(Orders::class);
        $orders = $em->findAll();
        $data = $serializer->serialize($orders, 'json');
        return $this->render('home/index.html.twig', [
            'orders' => $data,
        ]);
    }
}