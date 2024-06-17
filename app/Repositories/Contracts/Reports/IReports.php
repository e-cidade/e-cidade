<?php

namespace App\Repositories\Contracts\Reports;

interface IReports
{
    public function setShowFile(bool $showFile): self;

    public function setBase64Format(bool $base64Format): self;

    public function getFile(): string;

    public function setFile(string $file): self;

    public function generate(): void;
}
