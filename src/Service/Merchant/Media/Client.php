<?php

namespace MuCTS\Laravel\WeChatPayV3\Service\Merchant\Media;

use MuCTS\Laravel\WeChatPayV3\Kernel\BaseClient;

/**
 * Class Client.
 */
class Client extends BaseClient
{
    /**
     * @param $fileName
     * @param $content
     * @param $mimeType
     * @param array $options
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Throwable
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upload($fileName, $content, $mimeType, array $options = [])
    {
        $signPayload = json_encode([
            'filename' => $fileName,
            'sha256' => hash('sha256', $content),
        ]);

        $multipart = [
            [
                'name' => 'meta',
                'contents' => $signPayload,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ],
            [
                'name' => 'file',
                'filename' => $fileName,
                'contents' => $content,
                'headers' => [
                    'Content-Type' => $mimeType,
                ],
            ],
        ];

        $url = self::classUrl().'upload';
        $opts = $options + ['multipart' => $multipart, 'sign_payload' => $signPayload];

        return $this->request('POST', $url, $opts);
    }

}
