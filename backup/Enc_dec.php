<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Encoderen en Decoderen zonder rare karakters
 * rtrim — Strip whitespace (or other characters) from the end of a string
 * strtr — Translate characters or replace substrings 
 * str_pad — Pad a string to a certain length with another string
 * strlen — Get string length
 */
class End_dec
{
    /**
     * Url veilige codering
     */
    function base64url_encode($data) 
    { 
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
    } 

    /**
     * Decodering van een url veilige codering
     */
    function base64url_decode($data) 
    { 
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
    } 
}