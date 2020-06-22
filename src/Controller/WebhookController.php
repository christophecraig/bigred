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
            $fb = new Facebook([
                'app_id' => $_ENV['FACEBOOK_APP_ID'],
                'app_secret' => $_ENV['FACEBOOK_APP_SECRET'],
                'default_graph_version' => 'v7.0',
                'default_access_token' => $_ENV['FACEBOOK_APP_TOKEN'],
                'persistent_data_handler' => 'session',
            ]);
            $psid = $requestBody->entry[0]->messaging[0]->sender->id;
            if ($session->has('fb_access_token')) {
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
                            'message' => '{
                          "text": "Merci beaucoup"
                        }',
                        ],
                        $session->get('fb_access_token')
                    );
                } catch (FacebookResponseException $e) {
                    echo 'Graph returned an error: ' . $e->getMessage();
                    exit();
                } catch (FacebookSDKException $e) {
                    echo 'Facebook SDK returned an error: ' . $e->getMessage();
                    exit();
                }
                $graphNode = $response->getGraphNode();
            }

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