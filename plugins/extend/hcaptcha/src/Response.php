<?php declare(strict_types=1);

namespace HCaptcha;

class Response
{
    /** @var bool */
    private $success = false;

    /** @var array */
    private $errors = [];

    public function __construct($response)
    {
        if ($response === null) {
            $this->errors[] = 'json-parse-failure';
        } else {
            $this->success = $response['success'];
            $this->errors = $response['error-codes'];
        }
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}