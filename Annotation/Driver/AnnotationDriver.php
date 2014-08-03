<?php

namespace Pulpmedia\SerializedResponseBundle\Annotation\Driver;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Pulpmedia\SerializedResponseDriver\Annotations;
//use SomeNamespace\SomeBundle\Security\Permission;//In this class I check correspondence permission to user
use Symfony\Component\HttpFoundation\Response;

//use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AnnotationDriver{

    protected $container;
		private $reader;
		private $isSerializedResponse = false;

		private $configuration;

    public function __construct(ContainerInterface $container,$reader)
				{
						$this->container = $container;
						$this->reader = $reader;
				}
    public function onKernelController(FilterControllerEvent $event)
    {

        if (!is_array($controller = $event->getController())) { //return if no controller
            return;
        }

        $object = new \ReflectionObject($controller[0]);
        $method = $object->getMethod($controller[1]);

        foreach ($this->reader->getMethodAnnotations($method) as $configuration) { //Start of annotations reading
            if(isset($configuration->format)){//Found our annotation
							$this->configuration = $configuration;
                $this->isSerializedResponse = true;

             }
         }
    }

		public function onKernelView(GetResponseForControllerResultEvent $event)
		{
				$request = $event->getRequest();
				$parameters = $event->getControllerResult();

				if($this->isSerializedResponse){

                $serializer = $this -> container -> get('jms_serializer');
								$format= $this->configuration->format;
								$response = $serializer -> serialize($parameters, $format);
								$status = $this->configuration->status;
								$event->setResponse(new Response($response,$status, array('Content-Type' => 'application/json')));

		}



		}
}
