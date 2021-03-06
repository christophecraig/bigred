<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Repository\ClientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        // Needed for webhook verification
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
            file_put_contents('logs.log', 'test', FILE_APPEND);
            $requestBody = json_decode(file_get_contents('php://input'));
            file_put_contents(
                'logs.log',
                print_r($requestBody, true),
                FILE_APPEND
            );
            // Just logging the body of the request
            $fb = new Facebook([
                'app_id' => $_ENV['FACEBOOK_APP_ID'],
                'app_secret' => $_ENV['FACEBOOK_APP_SECRET'],
                'default_graph_version' => 'v7.0',
                'default_access_token' => $_ENV['FACEBOOK_APP_TOKEN'],
                'persistent_data_handler' => 'session',
            ]);
            // $psid = $requestBody->entry[0]->messaging[0]->sender->id;
            $user_ref = $requestBody->entry[0]->messaging[0]->optin->user_ref;
            if (isset($requestBody->entry[0]->messaging[0]->optin)) {
                $em = $this->getDoctrine()->getManager();
                file_put_contents(
                    'logs.log',
                    print_r($requestBody->entry[0]->messaging[0]->optin, true),
                    FILE_APPEND
                );
                // Associate the PSID to the client
                $client = $em
                    ->getRepository(Clients::class)
                    ->find($requestBody->entry[0]->messaging[0]->optin->ref);
                $client->setFbPSID($user_ref);
                $em->flush();

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
                          "user_ref": "' .
                            $user_ref .
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
            return new Response('', 200);
        }
    }
}