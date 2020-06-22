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
            $requestBody = json_decode(file_get_contents('php://input'));
            $psid = $requestBody->entry[0]->messaging[0]->sender->id;
            file_put_contents('logs.log', print_r($psid, true), FILE_APPEND);

            // This in a post to /me/messages worked in the fb explorer
            // {
            //     "messaging_type": "UPDATE",
            //     "recipient": {
            //       "id": "2754187091352898"
            //     },
            //     "message": {
            //       "text": "superrrr"
            //     }
            //   }
            return new Response('', 200);
        }
    }
}