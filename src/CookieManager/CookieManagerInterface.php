<?php

declare(strict_types=1);

namespace Setono\SyliusGoogleOptimizePlugin\CookieManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface CookieManagerInterface
{
    /**
     * Reads the experiments from the experiments cookie
     *
     * If not $request is provided, it uses the main request from the request stack
     */
    public function read(Request $request = null): Experiments;

    /**
     * Stores the experiment cookie on the given response
     */
    public function store(Response $response, Experiments $experiments): void;
}
