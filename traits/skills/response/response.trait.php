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


namespace AlexaPhpFramework\traits\skills\response;

trait response
{
    private $outputSpeech = null;
    private $card = null;
    private $reprompt = null;
    private $directives = null;
    private $shouldEndSession = null;
    
    function __construct()
    {
        $this->initialize();
    }
    
    public function initialize($shouldEndSession = true)
    {
        $this->outputSpeech = new \stdClass;
        $this->outputSpeech->type = "";
        $this->outputSpeech->text = "";
        $this->outputSpeech->ssml = "";
        
        $this->card = new \stdClass;
        $this->card->type = "";
        $this->card->title = "";
        $this->card->content = "";
        $this->card->text = "";
        $this->card->image = new \stdClass;
        $this->card->image->smallImageUrl = "";
        $this->card->image->largeImageUrl = "";
        
        $this->reprompt = new \stdClass;
        $this->reprompt->outputSpeech = new \stdClass;
        $this->reprompt->outputSpeech->type = "";
        $this->reprompt->outputSpeech->text = "";
        $this->reprompt->outputSpeech->ssml = "";
        
        $this->directives = new \stdClass;
        $this->directives->type = "";
        
        $this->shouldEndSession = (($shouldEndSession) ? true : false);
    }
    
    public function set_outputSpeech($text = null, $type = "PlainText", $ssml = null)
    {
        $this->outputSpeech->text = $text;
        $this->outputSpeech->type = $type;
        $this->outputSpeech->ssml = $ssml;
    }    
    
    public function render()
    {
        if ($this->outputSpeech->type == "")
            unset($this->outputSpeech);
            
        if ($this->card->type == "")
            unset($this->card);
            
        if ($this->reprompt->outputSpeech->type == "")
            unset($this->reprompt);
            
        if ($this->directives->type == "")
            unset($this->directives);
    }
}