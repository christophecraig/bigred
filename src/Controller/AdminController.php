<?php

namespace App\Controller;

use App\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getRepository(Orders::class);
        $orders = $em->findAll();
        return $this->render('admin/index.html.twig', [
            'orders' => $orders,
        ]);
    }
}
