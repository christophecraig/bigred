<?php

namespace App\Controller;

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
        $request = Request::createFromGlobals();
        $verifyToken = 'sublime1234';

        if (
            $request->get('hub.mode') === 'subscribe' &&
            $request->get('hub.verify_token') === $verifyToken
        ) {
            return $this->json($request->get('hub.challenge'), 200);
        } else {
            http_response_code(403);
        }
    }
}