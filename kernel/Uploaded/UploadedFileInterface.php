<?php

namespace Kernel\Uploaded;

interface UploadedFileInterface
{
    public function getError(): int;

    public function getMimeType(): string;

    public function getPath(): string;

    public function getSize(): int;

    public function getFilename(): string;

    public function getTmpName(): string;

    public function getFileExtension(): string;

    public function move(string $path, ?string $filename = null): string|false;
}
