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
        return $this->render('webhook/index.html.twig', []);
    }
}