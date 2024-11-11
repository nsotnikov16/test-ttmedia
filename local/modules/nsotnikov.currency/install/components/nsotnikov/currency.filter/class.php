<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;

class CurrencyFilterComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }

    public function onPrepareComponentParams($arParams)
    {
        if ($arParams['COLUMNS']) {
            $key = array_search('ID', $arParams['COLUMNS']);
            if ($key !== false) unset($arParams['COLUMNS'][$key]);
        }
        return $arParams;
    }
}
