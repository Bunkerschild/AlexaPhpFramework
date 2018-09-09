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


namespace AlexaPhpFramework\traits\skills;

trait alexa_request
{
    private $session = null;
    private $request = null;
    private $context = null;
    private $version = null;

    function __construct($json)
    {
        $this->session = new \AlexaPhpFramework\skills\request\session($json->session);
        $this->request = new \AlexaPhpFramework\skills\request\request($json->request);
        $this->context = new \AlexaPhpFramework\skills\request\context($json->context);
        $this->version = $json->version;
    }
    
    public function get_application_id(&$amzId = null, &$applicationType = null, &$applicationClass = null)
    {
        $amzId = null;
        $applicationType = null;
        $applicationClass = null;
        
        $appl = explode(".", $this->context->System->application->applicationId);
        
        if (count($appl) != 4)
            return false;
        
        $amzId = $appl[0];
        $applicationType = $appl[1];
        $applicationClass = $appl[2];
        
        return str_replace("-", "_", $appl[3]);
    }
    
    public function create_application(&$response = null)
    {
        $amzId = null;
        $applicationType = null;
        $applicationClass = null;
        
        $response = new \AlexaPhpFramework\skills\alexa_response($this);
        
        $applicationId = $this->get_application_id($amzId, $applicationType, $applicationClass);
        
        if (!file_exists(PATH_APPLICATIONS.DS.$applicationClass.DS."amz_".$applicationId.DS.$applicationType.".appl.php"))
        {
            throw new \exception("Application ".$amzId.".".$applicationType.".".$applicationClass.".".$applicationId." not found in ".PATH_APPLICATIONS.DS.$applicationClass.DS."amz_".$applicationId.DS.$applicationType.".appl.php");
            return null;
        }

        $application = "\\AlexaPhpFramework\\applications\\".$applicationClass."\\amz_".$applicationId."\\".$applicationType;

        return new $application($this, $response);
    }
}
