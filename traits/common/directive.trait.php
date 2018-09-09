<?php

 /***************************************************************************************************************\
 *                                                                                                               *
 * THIS FILE IS PART OF THE ALEXA-PHP-FRAMEWORK AND IS PUBLISHED UNDER THE CC BY-NC-ND 4.0 LICENSE               * 
 *                                                                                                               * 
 * AUTHOR, LICENSOR AND COPYRIGHT OWNER (C)2018 Oliver Welter <contact@verbotene.zone>                           *
 *                                                                                                               * 
 * ************************************************************************************************************* *
 *                                                                                                               *
 * THE CC BY-NC-ND 4.0 LICENSE:                                                                                  *
 * For details see also: https://creativecommons.org/licenses/by-nc-nd/4.0/                                      *
 *                                                                                                               *
 * By exercising the Licensed Rights, defined in ./LICENSE/LICENSE.EN                                            *
 * (or in other languages LICENSE.<AR|DE|FI|FR|HR|ID|IT|JA|MI|NL|NO|PL|SV|TR|UK>),                               *
 * You accept and agree to be bound by the terms and conditions of this                                          *
 *                                                                                                               *
 * Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International Public License ("Public License"). * 
 *                                                                                                               *
 * To the extent this Public License may be interpreted as a contract, You are granted the Licensed Rights in    *
 * consideration of Your acceptance of these terms and conditions, and the Licensor grants You such rights in    *
 * consideration of benefits the Licensor receives from making the Licensed Material available under these       *
 * terms and conditions.                                                                                         *
 *                                                                                                               *
 \***************************************************************************************************************/


namespace AlexaPhpFramework\traits\common;

trait directive
{
    private $header = null;
    private $endpoint = null;
    private $payload = null;

    function __construct($directive)
    {
        $this->header = new \AlexaPhpFramework\common\header($directive->header);

        if (class_exists("AlexaPhpFramework\\interfaces\\authorization\\alexa_request", false))
        {
            $payload = "\\AlexaPhpFramework\\common\\payload\\grant";
            unset($this->endpoint);
        }
        elseif (class_exists("AlexaPhpFramework\\interfaces\\discovery\\alexa_request", false))
        {
            $payload = "\\AlexaPhpFramework\\common\\payload\\scope";
            unset($this->endpoint);
        }
        elseif (class_exists("AlexaPhpFramework\\interfaces\\brightnesscontroller\\alexa_request", false))
        {
            $payload = "\\AlexaPhpFramework\\common\\payload\\brightness";
            $this->endpoint = new \AlexaPhpFramework\common\endpoint($directive->endpoint);
        }
        else
        {
            $payload = null;
            unset($this->endpoint);
        }

        if ($payload)
            $this->payload = new $payload($directive->payload);
    }
}
