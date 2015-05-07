<?php

namespace Babacooll\ExposeToJsBundle\Exposer;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class Exposer
 *
 * @package Babacooll\ExposeToJsBundle
 */
class TokenExposer extends AbstractExposer
{
    /** @var Router */
    protected $router;

    /**
     * @param \Twig_Environment   $twig
     * @param Router              $router
     * @param SerializerInterface $serializer
     */
    public function __construct(\Twig_Environment $twig, Router $router, SerializerInterface $serializer = null)
    {
        parent::__construct($twig, $serializer);

        $this->router = $router;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return base64_encode(serialize([$this->variables, $this->serializedVariables]));
    }

    /**
     * @param string $token
     */
    public function retrieveFromToken($token)
    {
        $data = unserialize(base64_decode($token));

        if (!is_array($data)) {
            throw new \InvalidArgumentException();
        }

        $this->variables           = $data[0];
        $this->serializedVariables = $data[1];
    }

    /**
     * @return string
     */
    public function expose()
    {
        return $this->twig->render(
            'BabacoollExposeToJsBundle:Expose:expose.js.twig',
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
        return $this->addBeforeEndBody($response, $toolbar = '<script src="' . $this->router->generate('babacooll_expose_to_js_homepage', array('t' => $this->getToken())) . '"></script>');
    }
}
