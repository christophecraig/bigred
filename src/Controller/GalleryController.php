<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GalleryController extends AbstractController
{
    /**
     * @Route("/gallery", name="gallery")
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
        try {
            $response = $fb->get(
                '/283620859105921/photos?fields=images&type=uploaded',
                $_ENV['FACEBOOK_PAGE_ACCESS_TOKEN']
            );
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit();
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit();
        }
        $graphNode = $response->getGraphEdge();

        return $this->render('gallery/index.html.twig', [
            'controller_name' => 'GalleryController',
            'photos' => $graphNode,
        ]);
    }
}