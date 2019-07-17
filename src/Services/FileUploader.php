<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectoryPerf;
    
    public function __construct($targetDirectoryPerf)
    {
        $this->targetDirectoryPerf = $targetDirectoryPerf;
    }

    public function uploadImgPerf(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate(
            'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
            $originalFilename
        );
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectoryPerf(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    /**
     * @return mixed
     */
    public function getTargetDirectoryPerf()
    {
        return $this->targetDirectoryPerf;
    }
}