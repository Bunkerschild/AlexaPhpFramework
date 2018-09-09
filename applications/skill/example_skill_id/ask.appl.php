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


namespace AlexaPhpFramework\applications\skill\example_skill_id;

final class ask
{
    private $skill_request = null;
    private $skill_response = null;
    
    function __construct($skill_request, $skill_response)
    {
        $this->skill_request = $skill_request;
        $this->skill_response = $skill_response;
    }    
    
    public function exec()
    {   
        if ($this->skill_request->request->type != "IntentRequest")
        {
            $this->skill_response->response->set_outputSpeech("Sorry, aber diese Anfrage kann ich nicht bearbeiten.");
        }
        else
        {
            $intent = $this->skill_request->request->intent;
            
            if ($intent->name == "doSwitching")
            {
                $device = $intent->slots->device->value;
                
                $this->skill_response->response->set_outputSpeech("Ich schalte das Geraet ".$device);
            }
            else
            {
                $this->skill_response->response->set_outputSpeech("Sorry, aber diese Anfrage ist ungueltig.");
            }
        }
        
        $this->skill_response->response->render();
        
        return json_encode($this->skill_response);
    }
}
