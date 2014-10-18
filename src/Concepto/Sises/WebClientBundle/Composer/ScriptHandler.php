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

namespace Concepto\Sises\WebClientBundle\Composer;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ScriptHandler {
    public static function installAssets()
    {
        $event = func_get_arg(0);
        $options = $event->getComposer()->getPackage()->getExtra();

        $public = "{$options['symfony-web-dir']}";

        $fs = new Filesystem();
        $fs->mkdir("{$public}/css", 0755);

        $finder = new Finder();
        $files = $finder->files()
            ->in(__DIR__ . '/../Resources/bower_components')
            ->name('chosen*.png');

        /** @var \SplFileInfo $file */
        foreach($files as $file) {
            $target = "{$public}/css/{$file->getFilename()}";
            if ($fs->exists($target)) {
                $fs->remove($target);
            }
            echo "{$file->getRealPath()} >> {$target}" . PHP_EOL;
            $fs->symlink($file->getRealPath(), $target);
        }
    }
}