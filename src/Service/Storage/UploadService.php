<?php

declare(strict_types=1);

namespace RashinMe\Service\Storage;

use RashinMe\Entity\File;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Storage\Repository\StorageRepositoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService
{
    public function __construct(
        private readonly StorageRepositoryInterface $storageRepository,
        private readonly SluggerInterface $slugger,
        private readonly string $storagePublicPath,
        private readonly string $storagePublicUrl,
    ) {
    }

    /**
     * Upload file to storage
     *
     * @param UploadedFile $file
     *
     * @return File|ErrorInterface
     */
    public function execute(UploadedFile $file): File|ErrorInterface
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

        $fileDirectory = $this->storagePublicPath . date('d-m-Y') . DIRECTORY_SEPARATOR;
        $filePath = $this->storagePublicUrl . date('d-m-Y') . DIRECTORY_SEPARATOR;
        $mime = $file->getMimeType() ?? '';
        $size = $file->getSize() ?: 0 ;

        $file->move(
            $fileDirectory,
            $newFilename
        );

        $file = new File(
            $newFilename,
            $filePath,
            $mime,
            $size,
        );

        $this->storageRepository->save($file);

        return $file;
    }
}
