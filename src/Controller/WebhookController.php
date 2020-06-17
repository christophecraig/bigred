<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebhookController extends AbstractController
{
    /**
     * @Route("/webhook", name="webhook", methods={"GET","POST"})
     */
    public function index(Request $request)
    {
        $verifyToken = 'sublime1234';

        if (
            $request->get('hub.mode') === 'subscribe' &&
            $request->get('hub.verify_token') === $verifyToken
        ) {
            echo $request->get('hub.challenge');
            http_response_code(200);
        } else {
            http_response_code(403);
        }
    }
}