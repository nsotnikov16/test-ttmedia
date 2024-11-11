<?php

namespace NSotnikov\Currency;

use Bitrix\Main\Type\DateTime;
use NSotnikov\Currency\Orm\CurrencyRateTable;

class CurrencyRateUpdater
{
    public static function updateRates(): string
    {
        $ratesData = self::fetchRatesFromCBR();
        if (is_array($ratesData) && count($ratesData)) {
            foreach ($ratesData as $currencyCode => $rate) {
                self::saveRate($currencyCode, $rate);
            }
        }

        return '\\NSotnikov\\Currency\\CurrencyRateUpdater::updateRates();';
    }

    private static function fetchRatesFromCBR(): array | null
    {
        $url = 'https://www.cbr.ru/scripts/XML_daily.asp';

        $xmlData = file_get_contents($url);
        if ($xmlData === false) {
            return null;
        }

        $xml = simplexml_load_string($xmlData);
        if ($xml === false) {
            return null;
        }

        $rates = [];
        foreach ($xml->Valute as $valute) {
            $code = (string) $valute->CharCode;
            $rate = (float) str_replace(',', '.', $valute->Value);
            $rates[$code] = $rate;
        }

        return $rates;
    }

    private static function saveRate($currencyCode, $rate)
    {
        $date = new DateTime();
        $existingRate = CurrencyRateTable::getList([
            'filter' => [
                '=CODE' => $currencyCode,
            ]
        ])->fetch();

        if ($existingRate) {
            CurrencyRateTable::update($existingRate['ID'], [
                'COURSE' => $rate,
                'DATE' => $date
            ]);
        } else {
            CurrencyRateTable::add([
                'CODE' => $currencyCode,
                'DATE' => $date,
                'COURSE' => $rate,
            ]);
        }
    }
}
