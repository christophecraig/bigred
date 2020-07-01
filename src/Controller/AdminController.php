<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Clients;
use App\Repository\OrdersRepository;
use App\Form\OrdersType;
use App\Repository\ClientsRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(Request $request, OrdersRepository $ordersRepository)
    {
        $page = 1;
        $limit = 10;
        $sort = [];
        $groupBy = '';
        if (null !== $request->get('page')) {
            $page = $request->get('page');
        }
        if (null !== $request->get('sort')) {
            $sort['criteria'] = $request->get('sort');
        }
        // if (null !== $request->get('groupBy')) {
        //     $groupBy = $request->get('groupBy');
        // }

        if (null !== $request->get('filter')) {
            $orders = $ordersRepository->findByStatus(
                $request->get('filter'),
                $page,
                $limit,
                $sort,
                $groupBy
            );
        } else {
            $orders = $ordersRepository->findAllPaginated(
                $page,
                $limit,
                $sort,
                $groupBy
            );
        }
        $nbPages = ceil($orders->count() / $limit);

        return $this->render('admin/index.html.twig', [
            'orders' => $orders,
            'filter' => $request->get('filter'),
            'sort' => $sort,
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
        $fb = new Facebook([
            'app_id' => $_ENV['FACEBOOK_APP_ID'],
            'app_secret' => $_ENV['FACEBOOK_APP_SECRET'],
            'default_graph_version' => 'v7.0',
            'default_access_token' => $_ENV['FACEBOOK_APP_TOKEN'],
            'persistent_data_handler' => 'session',
        ]);
        $client = $order->getClient();
        $params = [];
        if (null !== $request->get('filter')) {
            $params = ['filter' => $request->get('filter')];
        }
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if (
            'confirmed' === $request->get('status') ||
            'delivered' === $request->get('status') ||
            ($form->isSubmitted() && $form->isValid())
        ) {
            $order->setStatus($request->get('status'));
            $order->setConfirmationDate(new DateTime());
            $this->getDoctrine()
                ->getManager()
                ->flush();

            if ('rescheduled' === $order->getStatus()) {
                $message =
                    'Hi, we will not be able to deliver on the date you chose. We can reschedule on the ' .
                    $order->getDeliveryDate()->format('d/m/y') .
                    ' in the ' .
                    $order->getDeliveryTime() .
                    '. Would that be ok for you ?';
            } elseif ('confirmed' === $order->getStatus()) {
                $message =
                    'Hi, your order has been confirmed on the ' .
                    $order->getDeliveryDate()->format('d/m/y') .
                    ' in the ' .
                    $order->getDeliveryTime() .
                    '. Add it to your calendar!';
            } else {
                return $this->redirectToRoute('admin', $params);
            }
            try {
                $fb->post(
                    '/me/messages',
                    [
                        'messaging_type' => 'UPDATE',
                        'recipient' =>
                            '{
                              "id": "' .
                            $client->getFbPSID() .
                            '"
                            }',
                        'message' =>
                            '{
                              "text": "' .
                            $message .
                            '"
                            }',
                    ],
                    (string) $_ENV['FACEBOOK_PAGE_ACCESS_TOKEN']
                );
            } catch (FacebookSDKException $e) {
                $this->addFlash(
                    'Facebook Messenger Error',
                    'The message has not been sent to the customer\'s Facebook Messenger account, you should contact him to let him know any updates on his order. An email has been sent instead.'
                );
                return $this->redirectToRoute('admin', $params);
            } catch (FacebookResponseException $e) {
                $this->addFlash('error', 'FacebookResponseException');
                return $this->redirectToRoute('admin', $params);
            }

            return $this->redirectToRoute('admin', $params);
        }

        return $this->render('orders/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);

        // This will work only for admin
        return $this->redirectToRoute('admin', $params);
    }

    /**
     * @Route("/admin/clients/{id}", name="admin_show_client", methods={"GET"})
     */
    public function showClient(
        Clients $client,
        OrdersRepository $ordersRepository
    ) {
        $orders = $ordersRepository->findByClient($client);
        // Show newest to oldest
        usort($orders, function ($a, $b) {
            return $b['deliveryDate'] <=> $a['deliveryDate'];
        });
        return $this->render('admin/show_client.html.twig', [
            'client' => $client,
            'orders' => $orders,
            'page' => 1,
            'nbPages' => 1,
        ]);
    }
}