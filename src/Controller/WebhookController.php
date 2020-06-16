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
        $response = new Response();
        if ($request->getMethod() == 'POST') {
            //Write code to listen webhook request
            $response->create('EVENT_RECEIVED', 200);
            $response->send();
        } else {
            $VERIFY_TOKEN = 'sublime1234';
            $mode = $request->get('hub.mode');
            $token = $request->get('hub.verify_token');
            $challenge = $request->get('hub.challenge');

            if ($mode && $token) {
                // Checks the mode and token sent is correct
                if ($mode === 'subscribe' && $token === $VERIFY_TOKEN) {
                    // Responds with the challenge token from the request

                    // console . log('WEBHOOK_VERIFIED');

                    $response->create($challenge, 200);
                    $response->send();
                } else {
                    // Responds with '403 Forbidden' if verify tokens do not match
                    return new Response('', 403);
                }
            } else {
                return new Response('', 403);
            }
        }
        return $this->render('webhook/index.html.twig', []);
    }
}