<?php

declare(strict_types=1);

namespace League\Glide\Api;

interface ApiInterface
{
    /**
     * Perform image manipulations.
     *
     * @param string $source Source image binary data.
     * @param array  $params The manipulation params.
     *
     * @return string Manipulated image binary data.
     */
    public function run(string $source, array $params): string;

    /**
     * Collection of API parameters.
     *
     * @return list<string>
     */
    public function getApiParams(): array;
}
