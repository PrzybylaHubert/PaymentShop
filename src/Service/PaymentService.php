<?php

namespace App\Service;

class PaymentService
{
    private const DOTPAY_PIN = ''; // your dotpay pin here
    private const LANGUAGE = 'pl';
    private const SHOP_ID = ''; // your shop id here

    public function GenerateChk($DotpayPin, $ParametersArray) 
    {
        ksort($ParametersArray);

        $paramList = implode(';', array_keys($ParametersArray));

        $ParametersArray['paramsList'] = $paramList;

        ksort($ParametersArray);

        $json = json_encode($ParametersArray, JSON_UNESCAPED_SLASHES);

        return hash_hmac('sha256', $json, self::DOTPAY_PIN, false);
    }

    public function getOrderedParameters($price)
    {
        $ParametersArray = array(
            "id" => self::SHOP_ID,
            "amount" => "$price",
            "currency" => "PLN",
            "description" => "Test order",
            "lang" => self::LANGUAGE,
        );

        $ParametersArray['chk'] = $this->GenerateChk(self::DOTPAY_PIN, $ParametersArray);

        return $ParametersArray;
    }
}