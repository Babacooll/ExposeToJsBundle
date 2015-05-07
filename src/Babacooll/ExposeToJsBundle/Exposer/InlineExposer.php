<?php

namespace Babacooll\ExposeToJsBundle\Exposer;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class Exposer
 *
 * @package Babacooll\ExposeToJsBundle
 */
class InlineExposer extends AbstractExposer
{
    /**
     * @return string
     */
    public function expose()
    {
        return $this->twig->render(
            'BabacoollExposeToJsBundle:Expose:inline_expose.html.twig',
            [
                'formattedVariables'     => $this->variables,
                'jsonFormattedVariables' => $this->serializedVariables
            ]
        );
    }

    /**
     * @param Response $response
     *
     * @return Response
     */
    public function updateResponse(Response $response)
    {
        return $this->addBeforeEndBody($response, $this->expose());
    }
}
