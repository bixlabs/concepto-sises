<?php
/**
 * Copyright © 2014 Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 *
 * This file is part of concepto-sises.
 *
 * concepto-sises
 * can not be copied and/or distributed without the express
 * permission of Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 */

namespace Concepto\Sises\ApplicationBundle\Archivos\Event;


use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oneup\UploaderBundle\Uploader\File\FileInterface;

/**
 * Class UploadListener
 * @package Concepto\Sises\ApplicationBundle\Archivos\Event
 * @Service()
 * @Tag(
 *  name="kernel.event_listener",
 *  attributes={"event"="oneup_uploader.post_persist.documentable", "method"="onUpload"}
 * )
 */
class UploadListener {
    public function onUpload(PostPersistEvent $event)
    {
        $response = $event->getResponse();
        /** @var FileInterface $file */
        $file = $event->getFile();

        $response['file'] = $file->getBasename();
    }
}