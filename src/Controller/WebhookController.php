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
        $verifyToken = 'sublime1234';
        if (
            $_GET['hub.mode'] === 'subscribe' &&
            $_GET['hub.verify_token'] == $verifyToken
        ) {
            return new Response($_GET['hub.challenge'], 200, [
                'content-type' => 'text/plain',
            ]);
        } else {
            return new Response('essaye encore', 403);
        }
    }
}