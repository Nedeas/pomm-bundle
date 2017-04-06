<?php

namespace AppBundle\Controller;

use \AppBundle\Model\Config;
use \PommProject\Foundation\Session\Session;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use \Symfony\Component\Serializer\Serializer;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\Templating\EngineInterface;
use \Symfony\Component\PropertyInfo\PropertyInfoExtractor;

class IndexController
{
    private $pomm;
    private $templating;
    private $serializer;
    private $property;

    public function __construct(
        EngineInterface $templating,
        Session $pomm,
        Serializer $serializer,
        PropertyInfoExtractor $property
    ) {
        $this->pomm = $pomm;
        $this->templating = $templating;
        $this->serializer = $serializer;
        $this->property = $property;
    }

    public function indexAction()
    {
        $result = $this->pomm->getQueryManager()
            ->query('select 1');

        return new Response(
            $this->templating->render(
                'AppBundle:Front:index.html.twig'
            )
        );
    }

    public function getAction(Config $config = null)
    {
        return new Response(
            $this->templating->render(
                'AppBundle:Front:get.html.twig',
                compact('config')
            )
        );
    }

    /**
     * Get data with default session
     *
     * @ParamConverter("config", options={"model": "\AppBundle\Model\ConfigModel"})
     */
    public function getDefaultSessionAction(Config $config)
    {
        return new Response(
            $this->templating->render(
                'AppBundle:Front:get.html.twig',
                compact('config')
            )
        );
    }

    /**
     * Get data with session 1
     *
     * @ParamConverter("config", options={"session": "my_db", "model": "\AppBundle\Model\ConfigModel"})
     */
    public function getSessionAction(Config $config)
    {
        return new Response(
            $this->templating->render(
                'AppBundle:Front:get.html.twig',
                compact('config')
            )
        );
    }

    /**
     * Get data with session 2
     *
     * @ParamConverter("config", options={"session": "my_db2", "model": "\AppBundle\Model\ConfigModel"})
     */
    public function getSession2Action(Config $config)
    {
        return new Response(
            $this->templating->render(
                'AppBundle:Front:get.html.twig',
                compact('config')
            )
        );
    }

    public function failAction()
    {
        $this->pomm->getQueryManager()
            ->query('select 1 from');
    }

    public function serializeAction()
    {
        $results = $this->pomm->getQueryManager()
            ->query('select point(1,2)');

        return new Response(
            $this->serializer->serialize($results, 'json'),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    public function propertyAction()
    {
        $info = $this->property->getTypes('AppBundle\Model\Config', 'name');

        return new Response(
            $this->templating->render(
                'AppBundle:Front:property.html.twig',
                compact('info')
            )
        );
    }
}
