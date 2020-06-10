<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(Request $request, OrdersRepository $ordersRepository)
    {
        $page = 1;
        $limit = 10;
        if (null !== $request->get('page')) {
            $page = $request->get('page');
        }

        if (null !== $request->get('filter')) {
            $orders = $ordersRepository->findByStatus(
                $request->get('filter'),
                $page,
                $limit
            );
        } else {
            $orders = $ordersRepository->findAllPaginated($page, $limit);
        }
        $nbPages = ceil($orders->count() / $limit);

        return $this->render('admin/index.html.twig', [
            'orders' => $orders,
            'filter' => $request->get('filter'),
            'currentPage' => $page,
            'nbPages' => $nbPages,
            'url' => 'admin',
        ]);
    }

    /**
     * @Route("/admin/orders/{id}/update-status", name="orders_update_status", methods={"GET","POST"})
     */
    public function updateStatus(Request $request, Orders $order): Response
    {
        $this->getUser()->getRoles();
        $order->setStatus($request->get('status'));
        $params = [];
        if (null !== $request->get('filter')) {
            $params = ['filter' => $request->get('filter')];
        }
        $this->getDoctrine()
            ->getManager()
            ->flush();

        // This will work only for admin
        return $this->redirectToRoute('admin', $params);
    }
}
