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
     * Just used by facebook to verify the webhook
     * @Route("/webhook", name="webhook", methods={"GET", "POST"})
     */
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $verifyToken = 'sublime1234';
            if (
                $_GET['hub_mode'] === 'subscribe' &&
                $_GET['hub_verify_token'] == $verifyToken
            ) {
                return new Response($_GET['hub_challenge'], 200);
            } else {
                return new Response('essaye encore', 403);
            }
        } else {
            $requestBody = file_get_contents('php://input');
            file_put_contents(
                'logs.log',
                print_r($requestBody, true),
                FILE_APPEND
            );
            return new Response('', 200);
        }
    }
}