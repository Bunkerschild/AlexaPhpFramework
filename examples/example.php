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


$json = json_decode(implode("", file("php://input")));

    $fd = @fopen("request.log", "a");
    @fwrite($fd, "=== NEUE ANFRAGE: ".$_SERVER["REMOTE_ADDR"]." ".date("Y-m-d H:I:s")." ===\n");
    @fwrite($fd, print_r($json,1));
    @fclose($fd);

define("BOOTSTRAP", realpath(__DIR__."/bootstrap.php"));

require_once(BOOTSTRAP);

if (!$json)
{
    Header("HTTP/1.1 400 BAD REQUEST");
    die("Invalid file format\n");
}

$request = $alexa->create_request($json);

if ($alexa->get_request_type() == "skill")
{
    $app = $request->create_application();    
    $response = $app->exec();

    $x = json_decode($response);
    unset($x->response->outputSpeech->ssml);
    unset($x->sessionAttributes);
    $x->response->reprompt = new stdClass;
    $x->response->reprompt->outputSpeech = new stdClass;
    $x->response->reprompt->outputSpeech->type = "PlainText";
    $x->response->reprompt->outputSpeech->text = null;
    $x->response->shouldEndSession = false;
    $x->response->directives = array( new stdClass);
    $x->response->directives[0]->type = "Dialog.ConfirmIntent";
    $x->response->directives[0]->updatedIntent = new stdClass;
    $x->response->directives[0]->updatedIntent->name = "doSwitching";
    $x->response->directives[0]->updatedIntent->confirmationStatus = "CONFIRMED";
    $x->response->directives[0]->updatedIntent->slots = new stdClass;
    $x->response->directives[0]->updatedIntent->slots->device = new stdClass;
    $x->response->directives[0]->updatedIntent->slots->device->name = "device";
    $x->response->directives[0]->updatedIntent->slots->device->value = "licht";
    $x->response->directives[0]->updatedIntent->slots->device->confirmationStatus = "CONFIRMED";
    $response = json_encode($x);        
    
    // WORKAROUND FOR NGINX, WHICH ALWAYS SENDS A 404
    Header("HTTP/1.1 200 OK");
    Header("Content-Type: application/json;charset=UTF-8");
    Header("Content-Length: ".strlen($response));

    
    $fd = @fopen("request.log", "a");
    @fwrite($fd, "=== NEUE ANTWORT: ".$_SERVER["REMOTE_ADDR"]." ".date("Y-m-d H:I:s")." ===\n");
    @fwrite($fd, print_r(json_decode($response),1));
    @fclose($fd);

    echo $response;
}
else
{
    Header("HTTP/1.1 405 DONT KNOW");
    exit;
}

