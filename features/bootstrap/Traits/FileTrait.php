<?php


namespace BehatTest\Traits;

trait FileTrait
{
    /**
     * Путь к каталогу features/bootstrap/Files/ResponseJson
     * @return string
     */
    private function getResponseJsonFolder(): string
    {
        return __DIR__ . $_ENV['TEST_FILES_FOLDER_RESPONSE_JSON'];
    }

    /**
     * Путь к каталогу features/bootstrap/Files/NasaApiResponse
     * @return string
     */
    private function getNasaApiResponseFolder(): string
    {
        return __DIR__ . $_ENV['TEST_FILES_FOLDER_NASA_API_RESPONSE'];
    }

    /**
     * Путь к каталогу features/bootstrap/Files/HttpClientResponse
     * @return string
     */
    private function getHttpClientResponseFolder(): string
    {
        return __DIR__ . $_ENV['TEST_FILES_FOLDER_HTTP_CLIENT_RESPONSE'];
    }
}