<?php

namespace App\Controller;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FacebookController extends AbstractController
{
    /**
     * @Route("/facebook", name="facebook")
     */
    public function index(Request $request, SessionInterface $session)
    {
        $session->start();
        $fb = new Facebook([
            'app_id' => $_ENV['FACEBOOK_APP_ID'],
            'app_secret' => $_ENV['FACEBOOK_APP_SECRET'],
            'default_graph_version' => 'v7.0',
            'default_acess_token' => $_ENV['FACEBOOK_APP_TOKEN'],
            'persistent_data_handler' => 'session',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken(
                AboutController::BASE_URL . '/facebook'
            );
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit();
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit();
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo 'Error: ' . $helper->getError() . "\n";
                echo 'Error Code: ' . $helper->getErrorCode() . "\n";
                echo 'Error Reason: ' . $helper->getErrorReason() . "\n";
                echo 'Error Description: ' .
                    $helper->getErrorDescription() .
                    "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit();
        }

        // Logged in
        echo '<h3>Access Token</h3>';
        var_dump($accessToken->getValue());

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($_ENV['FACEBOOK_APP_ID']); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken(
                    $accessToken
                );
                $fb->setDefaultAccessToken($accessToken);
            } catch (FacebookSDKException $e) {
                echo '<p>Error getting long-lived access token: ' .
                    $e->getMessage() .
                    "</p>\n\n";
                exit();
            }

            echo '<h3>Long-lived</h3>';
            var_dump($accessToken);
        }

        // $this->$_SESSION['fb_access_token'] = (string) $accessToken;
        $session->set('fb_access_token', $accessToken);
        // User is logged in with a long-lived access token.
        // You can redirect them to a members-only page.

        return $this->redirectToRoute('about');
        return $this->render('facebook/index.html.twig', [
            'controller_name' => 'FacebookController',
        ]);
    }
}