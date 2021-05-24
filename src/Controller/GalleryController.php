<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
// use Facebook\Facebook;
// use Facebook\Exceptions\FacebookSDKException;
// use Facebook\Exceptions\FacebookResponseException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GalleryController extends AbstractController
{
    /**
     * @Route("/gallery", name="gallery")
     */
    public function index(SessionInterface $session)
    {
        $session->start();


        // $fb = new Facebook([
        //     'app_id' => $_ENV['FACEBOOK_APP_ID'],
        //     'app_secret' => $_ENV['FACEBOOK_APP_SECRET'],
        //     'default_graph_version' => 'v7.0',
        //     'default_access_token' => $_ENV['FACEBOOK_APP_TOKEN'],
        //     'persistent_data_handler' => 'session',
        // ]);
        // try {
        //     $response = $fb->get(
        //         '/283620859105921/photos?fields=images&type=uploaded',
        //         $_ENV['FACEBOOK_PAGE_ACCESS_TOKEN']
        //     );
        // } catch (FacebookResponseException $e) {
        //     echo 'Graph returned an error: ' . $e->getMessage();
        //     exit();
        // } catch (FacebookSDKException $e) {
        //     echo 'Facebook SDK returned an error: ' . $e->getMessage();
        //     exit();
        // }
        // $photos = $response->getGraphEdge()->asArray();



        // Images to be displayed on this page will go in the /public/assets/gallery folder
        $img_folder = __DIR__.'/../../public/assets/gallery';

        $finder = new Finder();
        $finder->in($img_folder);
        if ($finder->hasResults()) {
            $photos = [];
            foreach ($finder as $file) {
                $fileName = $file->getRelativePathname();
                $photos[]["images"][0]["source"] = '/assets/gallery/'.$fileName;
            }
        }

        return $this->render('gallery/index.html.twig', [
            'controller_name' => 'GalleryController',
            'photos' => (isset($photos) ? $photos : []),
        ]);
    }
}