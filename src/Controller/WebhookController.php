<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebhookController extends AbstractController
{
    /**
     * @Route("/webhook", name="webhook", methods={"GET"})
     */
    public function index()
    {
        $response = new Response();
        $verifyToken = 'sublime1234';
        // $logger->debug('$_GET: ', $_GET);
        if (
            $_GET['hub.mode'] === 'subscribe' &&
            $_GET['hub.verify_token'] === $verifyToken
        ) {
            // $logger->debug('yessss');
            $response->setContent($_GET['hub.challenge']);
            $response->setStatusCode(200);
            return $response;
        } else {
            return $response->setStatusCode(403);
        }
    }
}