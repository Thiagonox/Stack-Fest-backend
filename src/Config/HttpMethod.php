<?php
declare(strict_types=1);

namespace Stack\Fest\Config;

class HttpMethod
{

    public string $name;

    public function __construct(string $name)
    {
        $this->name = $this->validateName(strtoupper($name));
    }

    protected function validateName(string $name): string
    {
        if (!in_array($name, ["GET", "POST", "PUT", "DELETE", "PATCH", "OPTIONS", "HEAD"])) {
            throw new RuntimeException("Invalid http method");
        }
        return $name;
    }
}