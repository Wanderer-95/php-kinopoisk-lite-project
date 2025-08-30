<?php

namespace Kernel\Uploaded;

class UploadedFile implements UploadedFileInterface
{
    public function __construct(
        private string $filename,
        private string $tmpName,
        private int $size,
        private int $error,
        private string $mimeType,
        private string $path
    ) {}

    public function getError(): int
    {
        return $this->error;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getTmpName(): string
    {
        return $this->tmpName;
    }

    public function getFileExtension(): string
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    public function move(string $path, ?string $filename = null): string|false
    {
        $storagePath = APP_PATH."/storage/$path";

        if (!is_dir($storagePath)) {
            if (!mkdir($storagePath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $storagePath));
            }
        }

        $fileName = $filename ?? $this->generateUniqueFilename();
        $fileName .= ".{$this->getFileExtension()}";

        if (file_exists($storagePath . "/$fileName"))
        {
            $fileName = $this->generateUniqueFilename() . ".{$this->getFileExtension()}";
        }

        if (move_uploaded_file($this->tmpName, $storagePath . "/$fileName")) {
            return "$path/$fileName";
        }

        return false;
    }

    private function generateUniqueFilename()
    {
        return md5(uniqid(mt_rand(), true));
    }
}
