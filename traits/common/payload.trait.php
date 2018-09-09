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

trait payload
{
    public function set_error($type, $message = null, $minimumValue = null, $maximumValue = null, $temperatureScale = "CELSIUS")
    {
        if (method_exists($this, "clear_all"))
            $this->clear_all();
            
        $validRange = new \stdClass;
        $validRange->minimumValue = new \stdClass;
        $validRange->maximumValue = new \stdClass;
        
        switch ($type)
        {
            case "BRIDGE_UNREACHABLE":
            case "ENDPOINT_BUSY":
            case "ENDPOINT_LOW_POWER":
            case "ENDPOINT_UNRECHABLE":
            case "EXPIRED_AUTHORIZATION_CREDENTIAL":
            case "FIRMWARE_OUT_OF_DATE":
            case "HARDWARE_MALFUNCTION":
            case "INVALID_AUTHORIZATION_CREDENTIAL":
            case "INVALID_DIRECTIVE":
            case "INVALID_VALUE":
            case "NO_SUCH_ENDPOINT":
            case "NOT_SUPPORTED_IN_CURRENT_MODE":
            case "RATE_LIMIT_EXCEEDED":
                break;
            case "TEMPERATURE_VALUE_OUT_OF_RANGE":
                if ($temperatureScale == "FAHRENHEIT")
                    $scale = "FAHRENHEIT";
                else
                    $scale = "CELSIUS";
                    
                if ($minimumValue !== null)
                {
                    $validRange->minimumValue->value = $minimumValue;
                    $validRange->minimumValue->scale = $scale;
                }
                
                if ($maximumValue !== null)
                {
                    $validRange->maximumValue->value = $maximumValue;
                    $validRange->maximumValue->scale = $scale;
                }                
                break;
            case "VALUE_OUT_OF_RANGE":
                if ($minimumValue !== null)
                {
                    $validRange->minimumValue->value = $minimumValue;
                }
                
                if ($maximumValue !== null)
                {
                    $validRange->maximumValue->value = $maximumValue;
                }                
                break;
            case "ACCEPT_GRANT_ERROR":
                if (!$message)
                    $message = "Access denied";
                break;
            default:
                $type = "INTERNAL_ERROR";
                
                if (!$message)
                    $message = "An internal unhandled error occured";
                    
                break;
        }

        $this->type = $type;
        $this->message = $message;
        
        if ((isset($validRange->minimumValue->value)) || (isset($validRange->maximumValue->value)))
            $this->validRange = $validRange;
    
        return;
    }
}