<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderService
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }


    public function uploadFile(UploadedFile $file)
    {
        $filename =  md5(uniqid()).'.'.$file->guessClientExtension();
        $file->move($this->parameterBag->get('shopItemDir'),$filename);
        return $filename;
    }
}