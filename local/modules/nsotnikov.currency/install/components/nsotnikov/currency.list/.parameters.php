<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arComponentParameters = [
    "PARAMETERS" => [
        "USE_FILTER" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("USE_FILTER"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
            "REFRESH" => "Y",
        ],
        "COLUMNS" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("COLUMNS"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => [
                "CODE" => Loc::getMessage("COLUMN_CODE"),
                "DATE" => Loc::getMessage("COLUMN_DATE"),
                "COURSE" => Loc::getMessage("COLUMN_COURSE"),
            ],
            "DEFAULT" => ["CODE", "DATE", "COURSE"],
        ],
        "PAGE_SIZE" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("PAGE_SIZE"),
            "TYPE" => "STRING",
            "DEFAULT" => "10",
        ],
    ],
];
