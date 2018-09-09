#!/usr/bin/php -q
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


define("BOOTSTRAP", realpath(__DIR__."/../bootstrap.php"));

require_once(BOOTSTRAP);

if ($argc != 2)
    die("Usage: ".$argv[0]." <filename>\n");
    
$filename = $argv[1];

if (!file_exists($filename))
    die("File not found: ".$filename."\n");
    
$json = json_decode(implode("", file($filename)));

if (!$json)
    die("Invalid file format\n");
    
$request = $alexa->create_request($json);
$request_type = $alexa->get_request_type();

$app = $request->create_application();

echo $app->exec();

//$response = $request->create_response();

//$response->value(42);
//$response->error("VALUE_OUT_OF_RANGE", "Dont know", 20, 50);
//$response->discover();
