<?php

namespace AppBundle\DependencyInjection;

use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;

class CatNamerInterface implements NamerInterface
{
    public function name(FileInterface $file)
    {
		return $file->getClientOriginalName();
        //return sprintf('%s.%s', $file->getClientOriginalName(), $file->getExtension());
    }  
}
