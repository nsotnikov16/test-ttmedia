<?php

if (!method_exists($USER, "CanDoOperation"))
    return false;

IncludeModuleLangFile(__FILE__);

$aMenu = array();

if ($APPLICATION->GetGroupRight("nsotnikov.currency") >= "R") {
    $aMenu[] = array(
        "parent_menu" => "global_menu_services",
        "section" => "currency_module",
        "sort" => 100,
        "text" => GetMessage("CURRENCY_MODULE_ADMIN_TITLE"),
        "title" => GetMessage("CURRENCY_MODULE_ADMIN_TITLE"),
        "url" => "currency_rates.php?lang=" . LANGUAGE_ID,
        "more_url" => ["currency_rates.php", "currency_rates_edit.php"],
        "icon" => 'fileman_sticker_icon'
    );
}

return $aMenu;
