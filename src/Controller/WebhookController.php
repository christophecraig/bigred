<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WebhookController extends AbstractController
{
    /**
     * Just used by facebook to verify the webhook
     * @Route("/webhook", name="webhook", methods={"GET", "POST"})
     */
    public function index(SessionInterface $session)
    {
        $session->start();
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
            // Just logging the body of the request
            file_put_contents(
                'logs.log',
                print_r($requestBody, true),
                FILE_APPEND
            );
            $fb = new Facebook([
                'app_id' => $_ENV['FACEBOOK_APP_ID'],
                'app_secret' => $_ENV['FACEBOOK_APP_SECRET'],
                'default_graph_version' => 'v7.0',
                'default_access_token' => $_ENV['FACEBOOK_APP_TOKEN'],
                'persistent_data_handler' => 'session',
            ]);
            $psid = $requestBody->entry[0]->messaging[0]->sender->id;
            if (isset($requestBody->entry[0]->messaging[0]->option)) {
                $message =
                    'Thank you for subscribing with messenger, you will now receive your order updates directly in this conversation.';
            } else {
                $message =
                    'Thank you for contacting us, we will get in touch with you very soon!';
            }
            // if ($session->has('fb_access_token')) {
            try {
                // Returns a `FacebookFacebookResponse` object
                $response = $fb->post(
                    '/me/messages',
                    [
                        'messaging_type' => 'UPDATE',
                        'recipient' =>
                            '{
                          "id": "' .
                            $psid .
                            '"
                        }',
                        'message' =>
                            '{
                          "text": "' .
                            $message .
                            '"
                        }',
                    ],
                    'EAAmZCJ9U8z1YBAF63doj3ZACfZAcgoplwDbiDZCcetZAeofZBXwTDhox8ZCxBcmJPmPwZAPZBU3Oy3q4FZABCkZCnlFwo4UmEEZCfKfhMtdtndAP0YYfQ1Ad6kErmkyuTrlo5ftSA1i8GqpjgZCk0TaWWEmodlAaYoibdzkZAR9w56iKe8xJSGlT40nkqv8qghX3HzL9AZD'
                );
                return new Response('', 200);
            } catch (FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit();
            } catch (FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit();
            }
            $graphNode = $response->getGraphNode();
            // }

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