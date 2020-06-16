<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WebhookController extends AbstractController
{
    /**
     * @Route("/webhook", name="webhook", method={"POST"})
     */
    public function index()
    {
        $verify_token =
            'xP3MYTyT4+?/8.E]?@@aDxJyjp]J=QW&dINu=:XYbG.BCXsOc{:bHpZ~vW!eo{M4!eal_/[dRAlb)J:W+kQV5F6ZT;Qn3zZGmxACHinR8kiYN°5GX{%K-d3uxo4bCtt?(og+7tKC0&°K{SjX)[CVIuav_C8BYexK#81(%^1Q:S-YNE>-&VcmV~;eQFk.°T7J8<;r%ak0Nga4rj_$?b&&k:3Z)p1yX=^o$-NVqu.pe]koFQb|e29|(]FZHT=U&?)GaO/nJ)A.7UbYE7dZ*;6dMF6yTeD4C8{]R]!#g2+°CPTZ(SO,cRWQK:Y/lCo,WhEM';
        $fb_token =
            'EAAmZCJ9U8z1YBABGvqrw23FD5jjJmoFf3NRrj7LIkwqk5hCcWOIcfKq7KXtcE2GcHZBf7eE6aRIdvRXz6H97OlQv26oW06VEdHNZBmJoXtJ7VljR5ohdkvJQYCo6n1WZBorJlSa3A6YoRD8aYdqVTvbbuS3zIjnSWWVNH0OiuNk4PQVAZAmSWjI96JUdQxh8ZD';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Write code to listen webhook request
        } else {
            $VERIFY_TOKEN = 'sublime1234';
            $mode = $_REQUEST['hub_mode'];
            $token = $_REQUEST['hub_verify_token'];
            $challenge = $_REQUEST['hub_challenge'];

            if ($mode && $token) {
                // Checks the mode and token sent is correct
                if ($mode === 'subscribe' && $token === $VERIFY_TOKEN) {
                    // Responds with the challenge token from the request
                    // console . log('WEBHOOK_VERIFIED');
                    echo $challenge;
                    http_response_code(200);
                } else {
                    // Responds with '403 Forbidden' if verify tokens do not match
                    http_response_code(403);
                }
            } else {
                http_response_code(403);
            }
        }
    }
}