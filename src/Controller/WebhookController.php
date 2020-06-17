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
    public function index(LoggerInterface $logger)
    {
        $verifyToken = 'sublime1234';
        $logger->debug('$_GET: ', $_GET);
        if (
            $_GET['hub.mode'] === 'subscribe' &&
            $_GET['hub.verify_token'] === $verifyToken
        ) {
            $logger->debug('yessss');
            return new Response($_GET['hub.challenge'], 200);
        } else {
            http_response_code(403);
        }
    }
}