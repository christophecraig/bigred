<?php

namespace App\Controller;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Facebook\Facebook;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AboutController extends AbstractController
{
    const BASE_URL = 'https://dev.christophecraig.com';
    // const BASE_URL = 'https://localhost:8000';
    /**
     * @Route("/about", name="about")
     */
    public function index(SessionInterface $session)
    {
        $session->start();
        $fb = new Facebook([
            'app_id' => $_ENV['FACEBOOK_APP_ID'],
            'app_secret' => $_ENV['FACEBOOK_APP_SECRET'],
            'default_graph_version' => 'v7.0',
            'default_access_token' => $_ENV['FACEBOOK_APP_TOKEN'],
            'persistent_data_handler' => 'session',
        ]);
        if (!$session->has('fb_access_token')) {
            $helper = $fb->getRedirectLoginHelper();

            $permissions = ['email', 'public_profile', 'pages_messaging']; // Optional permissions
            $callbackUrl = htmlspecialchars(self::BASE_URL . '/facebook');
            $loginUrl = $helper->getLoginUrl($callbackUrl, $permissions);
        } else {
            try {
                // Returns a `FacebookFacebookResponse` object
                $response = $fb->get(
                    '/me?fields=name,id,email,picture',
                    $session->get('fb_access_token')
                );
            } catch (FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit();
            } catch (FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit();
            }
            $permissions = $fb->get(
                '/me/permissions',
                $session->get('fb_access_token')
            );
            $graphNode = $response->getGraphNode();
        }

        return $this->render('about/index.html.twig', [
            'controller_name' => 'AboutController',
            'fb' => isset($graphNode) ? $graphNode : [],
            'login_url' => $loginUrl,
        ]);
    }
}