<?php

declare(strict_types=1);

namespace App\Error;

class ErrorCodes
{
    public static function urlForCode(int $code): string
    {
        $urls = [
            100 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-100-continue',
            101 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-101-switching-protocols',
            200 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-200-ok',
            201 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-201-created',
            202 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-202-accepted',
            203 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-203-non-authoritative-infor',
            204 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-204-no-content',
            205 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-205-reset-content',
            206 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-206-partial-content',
            300 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-300-multiple-choices',
            301 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-301-moved-permanently',
            302 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-302-found',
            303 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-303-see-other',
            304 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-304-not-modified',
            305 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-305-use-proxy',
            307 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-307-temporary-redirect',
            400 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-400-bad-request',
            401 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-401-unauthorized',
            402 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-402-payment-required',
            403 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-403-forbidden',
            404 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-404-not-found',
            405 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-405-method-not-allowed',
            406 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-406-not-acceptable',
            407 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-407-proxy-authentication-re',
            408 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-408-request-timeout',
            409 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-409-conflict',
            410 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-410-gone',
            411 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-411-length-required',
            412 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-412-precondition-failed',
            413 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-413-content-too-large',
            414 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-414-uri-too-long',
            415 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-415-unsupported-media-type',
            416 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-416-range-not-satisfiable',
            417 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-417-expectation-failed',
            421 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-421-misdirected-request',
            422 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-422-unprocessable-content',
            426 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-426-upgrade-required',
            500 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-500-internal-server-error',
            501 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-501-not-implemented',
            502 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-502-bad-gateway',
            503 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-503-service-unavailable',
            504 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-504-gateway-timeout',
            505 => 'https://datatracker.ietf.org/doc/html/rfc9110#name-505-http-version-not-suppor',
        ];

        return $urls[$code] ?? 'https://datatracker.ietf.org/doc/html/rfc9110';
    }
}
