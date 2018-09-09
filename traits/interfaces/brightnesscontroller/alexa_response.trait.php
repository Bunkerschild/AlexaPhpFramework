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


namespace AlexaPhpFramework\traits\interfaces\brightnesscontroller;

trait alexa_response
{
    private $context;
    private $event;

    function __construct($request)
    {
        $this->context = new \AlexaPhpFramework\common\context($request);
        $this->event = new \AlexaPhpFramework\common\event($request->directive);                
    }

    public function value($value, $uncertaintyInMilliseconds = 1000, $timeOfSample = null)
    {
        $this->context->properties->set_value($value, $uncertaintyInMilliseconds, $timeOfSample);
        $this->event->header->set_name("Response");
        $this->event->header->set_namespace("Alexa");
        
        return true;
    }
    
    public function error($type = "INTERNAL_ERROR", $message = null, $minimumValue = null, $maximumValue = null, $temperatureScale = "CELSIUS")
    {
        $this->event->header->set_name("ErrorResponse");
        $this->event->payload->set_error($type, $message, $minimumValue, $maximumValue, $temperatureScale);    
    }
}
