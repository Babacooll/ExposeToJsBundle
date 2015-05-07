<?php

namespace Babacooll\ExposeToJsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ExposeController
 *
 * @package Babacooll\ExposeToJsBundle\Controller
 */
class RemoteExposeController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function exposeAction(Request $request)
    {
        $exposer = $this->get('babacooll.exposer');

        $exposer->retrieveFromToken($request->query->get('t'));

        return new Response($exposer->expose());
    }
}
