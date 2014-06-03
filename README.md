SerializedResponseBundle
========================

A simple bundle to provide an easy way to send out json/xml/yaml responses of serialized objects with annotations.

##Introduction
This Bundle allows you to directly return serializable objects as from within a controller to receive a serialized response in json/xml/yaml format.

##Installation
###Step 1
This library can be easily installed via composer

  composer pulpmedia/serialized-response-bundle

or just add it to your composer.json file directly. This will install the **pulpmedia/serialized-response-bundle** and **jms/serializer** if not already installed.

###Step 2
Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Pulpmedia\SerializedResponseBundle\PulpmediaSerializedResponseBundle(),
    );
}
```

##Usage
Using the Bundle is as easy as it comes. By returning the Object that should be serialized and using the corresponding annotation;

``` php
<?php
namespace My\Bundle\Controller

use Pulpmedia\SerializedResponseBundle\Annotation\SerializedResponse;

// ...

class MyController extents Controller{

  /**
   * @Route("/get/{id}")
   * @SerializedResponse(responsetype="json/xml/yaml")
   */
  public function getAction($id){
    $em = $this->getDoctrine()->getManager();

    if($object = $em->getRepository('MyBundle:Entity')->find($id)){
        return $object;
    }
    return null;
  }
}
```
