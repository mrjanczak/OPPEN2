<?php

namespace Oppen\ProjectBundle;

use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;

class CatNamer implements NamerInterface
{
    public function name(FileInterface $file)
    {
        return sprintf('%s.%s', $file->getBasename(), $file->getExtension());
        //return $file->getClientOriginalName();
    }
}
