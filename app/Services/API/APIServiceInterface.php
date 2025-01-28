<?php

namespace App\Services\API;

interface APIServiceInterface
{
    /**
     * Fetch news articles from the external API.
     *
     * @return void
     */
    public function fetchNews(): void;
}
