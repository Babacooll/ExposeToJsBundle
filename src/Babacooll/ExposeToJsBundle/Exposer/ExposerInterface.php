<?php

namespace Babacooll\ExposeToJsBundle\Exposer;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Interface ExposerInterface
 *
 * @package Babacooll\ExposeToJsBundle
 */
interface ExposerInterface
{
    /**
     * @param string              $name
     * @param mixed               $data
     * @param SerializerInterface $serializer
     *
     * @return mixed
     */
    public function add($name, $data, SerializerInterface $serializer = null);

    /**
     * @return string
     */
    public function expose();

    /**
     * @param Response $response
     *
     * @return Response
     */
    public function updateResponse(Response $response);
}
