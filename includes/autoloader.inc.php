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


if (!defined("ALEXA_BOOTSTRAP"))
    die("You may not access this file directly\n");

if (defined("ALEXA_AUTOLOADER"))
    die("You may not include this file, twice\n");
else
    define("ALEXA_AUTOLOADER", true);
    
$__AUTOLOAD = array(
    "success" => new stdClass,
    "not_found" => new stdClass,
    "invalid" => new stdClass
);

spl_autoload_register
(
    function($class)
    {
        global $__AUTOLOAD;
        
        $part = explode("\\", $class);
        $defname = strtoupper(implode("_", $part));

        if (defined($defname))		// Class already loaded
            return false;
        
        if ($part[0] != "AlexaPhpFramework")
        {
            $__AUTOLOAD["invalid"]->$defname = array("name" => $class, "timestamp" => microtime(true));
            return false;
        }
            
        unset($part[0]);
        
        switch ($part[1])
        {
            case "traits":
                $path = PATH_TRAITS.DS;
                unset($part[1]);
                $ext = "trait";
                break;
            case "applications":
                $path = PATH_APPLICATIONS.DS;
                unset($part[1]);
                $ext = "appl";
                break;
            default:
                $path = PATH_CLASSES.DS;
                $ext = "class";
                break;
        }
        
        $filename = $path.implode(DS, $part).".".$ext.".php";

        if (!file_exists($filename))	// Class or trait not found
        {
            $__AUTOLOAD["success"]->$defname = array($ext => $class, "filename" => $filename, "timestamp" => microtime(true));
            return false;
        }
        
        require_once($filename);    
        define($defname, microtime(true));
        
        $__AUTOLOAD["success"]->$defname = array($ext => $class, "filename" => $filename, "timestamp" => microtime(true));
        
        return true;
    }
);
