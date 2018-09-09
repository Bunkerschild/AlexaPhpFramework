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


namespace AlexaPhpFramework\traits;

trait alexa
{
    private static $global_object_storage = null;
    private static $global_object_session = null;
    
    function __construct()
    {
        self::global_object_initialize();
    }
    
    public function get_request_type()
    {
        global $__AUTOLOAD;
        
        foreach ($__AUTOLOAD["success"] as $key => $val)
        {
            if (strstr($key, "AVS_SKILLS_REQUEST"))
                return "skill";
        }
        
        return "exec";
    }
    
    public static function global_object_initialize()
    {
        self::$global_object_storage = null;
        self::$global_object_session = null;
        
        self::global_object_storage_create();
    }
    
    public static function global_object_storage_create()
    {
        if (!is_array(self::$global_object_storage))
            self::$global_object_storage = array();
            
        while (true)
        {
            self::$global_object_session = md5(uniqid(microtime(true)));
            
            if (!isset(self::$global_object_storage[self::$global_object_session]))
                break;
                
            usleep(25);
        }
        
        self::$global_object_storage[self::$global_object_session] = array();
    }
    
    public static function global_object_storage_destroy()
    {
        unset(self::$global_object_storage[self::$global_object_session]);
        
        self::global_object_storage_create();
    }
    
    public static function global_object_set($key, $val)
    {
        self::$global_object_storage[self::$global_object_session][$key] = $val;        
    }
    
    public static function global_object_get($key)
    {
        if (isset(self::$global_object_storage[self::$global_object_session][$key]))
            return self::$global_object_storage[self::$global_object_session][$key];
        
        return null;
    }
    
    public static function global_object_free($key)
    {
        unset(self::$global_object_storage[self::$global_object_session][$key]);
    }

    public static function global_object_iterate()
    {
        return self::$global_object_storage[self::$global_object_session];
    }

    public function create_request($json)
    {
        if (!is_object($json))
        {
            throw new \exception("JSON has to be an object");
            return null;
        }
        
        if (strlen(json_encode($json)) < 3)
        {
            throw new \exception("Request is empty");
            return null;
        }
        
        if (isset($json->directive))
        {
            if (!isset($json->directive->header))
            {
                throw new \exception("Missing directive header");
                return null;
            }
            
            if (!isset($json->directive->header->payloadVersion))
            {
                throw new \exception("Missing payload version");
                return null;
            }
            
            if ($json->directive->header->payloadVersion != self::ALEXA_PAYLOAD_VERSION_REQUIRED)
            {
                throw new \exception("Alexa payload version has to be ".self::ALEXA_PAYLOAD_VERSION_REQUIRED);
                return null;
            }
            
            if (!isset($json->directive->header->namespace))
            {
                throw new \exception("Missing namespace");
                return null;
            }
            
            if (!isset($json->directive->header->name))
            {
                throw new \exception("Missing name");
                return null;
            }
        
            if (!isset($json->directive->header->messageId))
            {
                throw new \exception("Missing message ID");
                return null;
            }    

            $namespace = "\\AlexaPhpFramework\\interfaces\\".str_replace("alexa_", "", strtolower(str_replace(".", "_", $json->directive->header->namespace)))."\\alexa_request";
        }
        elseif (isset($json->request))
        {
            if (!isset($json->session))
            {
                throw new \exception("Missing session");
                return null;
            }
            
            if (!isset($json->version))
            {
                throw new \exception("Missing version");
                return null;
            }
            
            $namespace = "\\AlexaPhpFramework\\skills\\alexa_request";
        }
        else
        {    
            throw new \exception("Missing directive or request");
            return null;
        }
        
        
        return new $namespace($json);
    }

    private function get_request(&$json = null, &$header = null, &$request_size = 0)
    {
        $json = null;
        $header = new stdClass;
        $request_size = 0;
        
        foreach ($_SERVER as $key => $val)
        {
            if (substr($_SERVER[$key], 0, 5) == "HTTP_")
                $header->$key = $val;
        }
    
        $fd = @fopen("php://input", "r");
        
        if (!is_resource($fd))
        {
            throw new exception("Unable to get request");
            return false;
        }
        
        $request = "";
        
        while (!feof($fd))
        {
            $request .= @fgets($fd, 4096);
        }
        
        @fclose($fd);
        
        $request_size = strlen($request);
        $json = json_decode($request);
        
        if (!$json)
        {
            throw new exception("No valid JSON input data");
            return false;
        }
        
        return true;
    }

    private function validate_origin($required_region = "eu-west-1", $required_domain = "compute.amazonaws.com")
    {
        if (!isset($_SERVER["REMOTE_ADDR"]))
        {
            if ($required_region == "local")
                return true;
                
            throw new \exception("Missing remote address. Cannot validate origin.");
            return false;
        }
        
        $hostname = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
        
        if ((!$hostname) || ($hostname == $_SERVER["REMOTE_ADDR"]))
        {
            throw new \exception("Missing reverse DNS");
            return false;
        }
        
        $fqdnparts = explode(".", $hostname, 4);
        
        if (count($fqdnparts) != 4)
        {
            throw new \exception("Number of fqdn parts has to be 4");
            return false;
        }
        
        $reverse_host = $fqdnparts[0];
        $region = $fqdnparts[1];
        $domain = $fqdnparts[2].".".$fqdnparts[3];
        $sldtld = $fqdnparts[3];
        
        if ($required_region)
        {
            if (!strstr($region, $required_region))
            {
                throw new \exception("Region '".$region."' does not match required region '".$required_region."'");
                return false;
            }
        }
        
        if ($required_domain)
        {
            if (!strstr($domain, $required_domain))
            {
                throw new \exception("Domain '".$domain."' does not match required domain '".$required_domain."'");
                return false;
            }
        }
        
        $match = false;
        $result = null;
        
        $google = new \Net_DNS2_Resolver(['nameservers' => ['8.8.8.8', '8.8.4.4']]);
        $google->dnssec = true;

        try 
        {
            $result = $google->query($hostname, ((strstr($_SERVER["REMOTE_ADDR"], ":")) ? 'AAAA' : 'A'));
        } 
        catch(\Net_DNS2_Exception $ex) 
        {
            throw new \exception($ex->getMessage());
            return false;
        }
        
        foreach ($result->answer as $answer)
        {
            if ($answer->type == ((strstr($_SERVER["REMOTE_ADDR"], ":")) ? 'AAAA' : 'A'))
            {
                if (($answer->name == $hostname) && ($answer->address == $_SERVER["REMOTE_ADDR"]))
                {
                    $match = true;
                    break;
                }
            }
        }
        
        if (!$match)
        {
            throw new \exception("Neither hostname nor IP address matches DNS records");
            return false;
        }
        
        $match = false;
        $result = null;
        
        try 
        {
            $result = $google->query($sldtld, 'SOA');
        } 
        catch(\Net_DNS2_Exception $ex) 
        {
            throw new \exception($ex->getMessage());
            return false;
        }
        
        foreach ($result->answer as $answer)
        {
            if ($answer->type == "SOA")
            {
                if ($answer->name == $sldtld)
                {
                    $match = true;
                    break;
                }
            }
        }
        
        if (!$match)
        {
            throw new \exception("Unable to validate source of authority");
            return false;
        }        

        return true;        
    }
}
