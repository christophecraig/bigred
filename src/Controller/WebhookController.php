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
            $_GET['hub_mode'] === 'subscribe' &&
            $_GET['hub_verify_token'] == $verifyToken
        ) {
            return new Response($_GET['hub_challenge'], 200);
        } else {
            return new Response('essaye encore', 403);
        }
    }
}