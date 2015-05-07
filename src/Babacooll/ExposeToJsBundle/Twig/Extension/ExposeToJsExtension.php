<?php

namespace Babacooll\ExposeToJsBundle\Twig\Extension;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class ExposeToJsExtension
 *
 * @package Babacooll\ExposeToJsBundle\Twig\Extension
 */
class ExposeToJsExtension extends \Twig_Extension
{
    /**
     * 
     */
    public function __construct()
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new PropertyNormalizer(), new GetSetMethodNormalizer());
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('exposeToJs', [$this, 'exposeToJsFunction'], ['is_safe' => ['html'], 'needs_environment' => true])
        ];
    }

    /**
     * @param \Twig_environment $twig
     * @param array             $variables
     *
     * @return string
     */
    public function exposeToJsFunction(\Twig_Environment $twig, array $variables = array())
    {
        $formattedVariables = [];
        $jsonFormattedVariables = [];

        foreach ($variables as $key => $value) {
            if (is_object($value) || is_array($value)) {
                $jsonFormattedVariables[$key] = $this->serializer->serialize($value, 'json');
            } else {
                $formattedVariables[$key] = $value;
            }
        }

        return $this->converToJsFormat($twig, $formattedVariables, $jsonFormattedVariables);
    }

    /**
     * @param \Twig_environment $twig
     * @param array             $formattedVariables
     * @param array             $jsonFormattedVariables
     *
     * @return string
     */
    protected function converToJsFormat(\Twig_Environment $twig, array $formattedVariables, array $jsonFormattedVariables)
    {
        return $twig->render(
            'BabacoollExposeToJsBundle:Extension:expose.html.twig',
            ['formattedVariables' => $formattedVariables, 'jsonFormattedVariables' => $jsonFormattedVariables]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'expose_variable_to_js_extension';
    }
}
