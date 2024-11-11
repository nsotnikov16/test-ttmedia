<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arComponentParameters = [
    "PARAMETERS" => [
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
    ],
];
