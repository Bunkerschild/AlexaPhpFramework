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

if (defined("ALEXA_DEFINES"))
    die("You may not include this file, twice\n");
else
    define("ALEXA_DEFINES", true);
    
if (!defined("DIRECTORY_SEPARATOR"))
    define("DIRECTORY_SEPARATOR", "/");
    
if (!defined("DS"))
    define("DS", DIRECTORY_SEPARATOR);
    
define("PATH_INCLUDES",	realpath(__DIR__));
define("PATH_ROOT", realpath(PATH_INCLUDES.DS."..".DS));
define("PATH_CLASSES", realpath(PATH_ROOT.DS."classes"));
define("PATH_TRAITS", realpath(PATH_ROOT.DS."traits"));
define("PATH_APPLICATIONS", realpath(PATH_ROOT.DS."applications"));
