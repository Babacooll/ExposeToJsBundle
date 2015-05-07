<?php

namespace Babacooll\ExposeToJsBundle\Exposer;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AbstractExposer
 *
 * @package Babacooll\ExposeToJsBundle\Exposer
 */
abstract class AbstractExposer implements ExposerInterface
{
    /** @var array */
    protected $variables = array();

    /** @var array */
    protected $serializedVariables = array();

    /** @var \Twig_Environment */
    protected $twig;

    /** @var SerializerInterface */
    protected $serializer;

    /**
     * @param \Twig_Environment   $twig
     * @param SerializerInterface $serializer
     */
    public function __construct(\Twig_Environment $twig, SerializerInterface $serializer = null)
    {
        $this->twig       = $twig;

        if ($serializer) {
            $this->serializer = $serializer;
        } else {
            $encoders = array(new JsonEncoder());
            $normalizers = array(new PropertyNormalizer(), new GetSetMethodNormalizer());
            $this->serializer = new Serializer($normalizers, $encoders);
        }
    }

    /**
     * @param string              $name
     * @param mixed               $data
     * @param SerializerInterface $serializer
     *
     * @return $this
     */
    public function add($name, $data, SerializerInterface $serializer = null)
    {
        $serializer = $serializer ?: $this->serializer;

        if (is_object($data) || is_array($data)) {
            $this->serializedVariables[$name] = $serializer->serialize($data, 'json');
        } else {
            $this->variables[$name] = $data;
        }

        return $this;
    }

    /**
     * @param Response $response
     * @param string   $toAdd
     *
     * @return Response
     */
    protected function addBeforeEndBody(Response $response, $toAdd)
    {
        $content = $response->getContent();
        $pos = strripos($content, '</body>');

        if (false !== $pos) {
            $content = substr($content, 0, $pos) . $toAdd . substr($content, $pos);
            $response->setContent($content);
        }

        return $response;
    }
}
