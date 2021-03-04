<?php declare(strict_types=1);

namespace HCaptcha;

class HCaptcha
{
    public const URL_SCRIPT = "https://hcaptcha.com/1/api.js";
    public const URL_VERIFY = "https://hcaptcha.com/siteverify";

    /** @var string */
    protected $siteKey;

    /** @var string */
    protected $secretKey;

    /**
     * HCaptcha constructor.
     *
     * @param string $siteKey
     * @param string $secretKey
     */
    public function __construct(string $siteKey, string $secretKey)
    {
        $this->siteKey = $siteKey;
        $this->secretKey = $secretKey;
    }

    /**
     * @return string
     */
    public function getSiteKey(): string
    {
        return $this->siteKey;
    }

    /**
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * @param string $responseToken
     * @return Response
     */
    public function validate(string $responseToken): Response
    {
        $data = [
            'secret' => $this->getSecretKey(),
            'response' => $responseToken
        ];

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\n",
                'content' => http_build_query($data),
                'ssl' => [
                    'verify_peer' => true,
                ],
                'ignore_errors' => true
            ]
        ]);
        $result = json_decode(file_get_contents(self::URL_VERIFY, false, $context), true);

        return new Response($result);
    }
}