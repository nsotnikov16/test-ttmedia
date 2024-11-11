<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use NSotnikov\Currency\Orm\CurrencyRateTable;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

Loader::includeModule('nsotnikov.currency');
$POST_RIGHT = $APPLICATION->GetGroupRight("nsotnikov.currency");

if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));

$APPLICATION->SetTitle(Loc::getMessage("CURRENCY_MODULE_ADMIN_TITLE"));

$sTableID = "currency_rate_list";
$oAdminSort = new CAdminSorting($sTableID, "ID", "desc");
$oAdminList = new CAdminList($sTableID, $oAdminSort);


$filterFields = [
    ["id" => "CODE", "name" => Loc::getMessage('CURRENCY_CODE'), "type" => "string", "filterable" => "="],
    ["id" => "DATE", "name" => Loc::getMessage('CURRENCY_DATE'), "type" => "date", "filterable" => "="],
    ["id" => "COURSE", "name" => Loc::getMessage('CURRENCY_COURSE'), "type" => "string", "filterable" => "="],
];

$filter = [];
$oFilter = new CAdminFilter($sTableID . "_filter", array_column($filterFields, "name"));

foreach ($filterFields as $field) {
    $value = isset($_GET[$field["id"]]) ? $_GET[$field["id"]] : '';
    if ($value) {
        $filter[$field["filterable"] . strtoupper($field["id"])] = $value;
    }
}

if ($oAdminList->EditAction()) {
    foreach ($FIELDS as $ID => $fields) {
        $ID = intval($ID);

        $fields['DATE'] = new DateTime($fields['DATE']);

        if (!$oAdminList->IsUpdated($ID)) continue;

        $res = CurrencyRateTable::update($ID, $fields);
    }
}

if (($arID = $oAdminList->GroupAction())) {
    if ($_REQUEST['action_target'] == 'selected') {
        $rsData = CurrencyRateTable::getList(["filter" => $filter]);
        while ($data = $rsData->fetch()) {
            $arID[] = $data['ID'];
        }
    }

    foreach ($arID as $ID) {
        $ID = intval($ID);
        if ($ID <= 0) continue;

        switch ($_REQUEST['action']) {
            case "delete":
                CurrencyRateTable::delete($ID);
                break;
        }
    }
}

$rsData = CurrencyRateTable::getList([
    "filter" => $filter,
    "order" => [$by => $order],
]);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();
$oAdminList->NavText($rsData->GetNavPrint(Loc::getMessage('CURRENCY_COURSES')));
$oAdminList->AddGroupActionTable([
    "delete" => Loc::getMessage("CURRENCY_DELETE")
]);

// Определяем заголовки таблицы
$oAdminList->AddHeaders([
    ["id" => "ID", "content" => "ID", "sort" => "ID", "default" => true],
    ["id" => "CODE", "content" => Loc::getMessage('CURRENCY_CODE'), "sort" => "CODE", "default" => true],
    ["id" => "DATE", "content" => Loc::getMessage('CURRENCY_DATE'), "sort" => "DATE", "default" => true],
    ["id" => "COURSE", "content" => Loc::getMessage('CURRENCY_COURSE'), "sort" => "COURSE", "default" => true],
]);

// Заполнение строк таблицы
while ($data = $rsData->NavNext(true, "f_")) {
    $row = &$oAdminList->AddRow($f_ID, $data);

    $row->AddInputField("CODE", ["size" => 3]);
    $row->AddCalendarField("DATE", ["size" => "15"], true);
    $row->AddInputField("COURSE", ["size" => 20]);

    $actions = [
        [
            "ICON" => "edit",
            "TEXT" => Loc::getMessage("CURRENCY_EDIT"),
            "ACTION" => $oAdminList->ActionRedirect("currency_rates_edit.php?ID=" . $f_ID),
            "DEFAULT" => true,
        ],
        [
            "ICON" => "delete",
            "TEXT" => Loc::getMessage("CURRENCY_DELETE"),
            "ACTION" => "if(confirm('Удалить запись?')) " . $oAdminList->ActionDoGroup($f_ID, "delete"),
        ],
    ];
    $row->AddActions($actions);
}

$aContext = [
    [
        "TEXT" => Loc::getMessage("CURRENCY_ADD_NEW"),
        "LINK" => "currency_rates_edit.php?lang=" . LANGUAGE_ID,
        "TITLE" => Loc::getMessage("CURRENCY_ADD_NEW"),
        "ICON" => "btn_new"
    ],
];
$oAdminList->AddAdminContextMenu($aContext);

$oAdminList->CheckListMode();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
?>
<form method="GET" action="<?= $APPLICATION->GetCurPage() ?>" name="find_form">
    <? $oFilter->Begin(); ?>
    <tr>
        <td><?= Loc::getMessage('CURRENCY_CODE') ?>:</td>
        <td><input type="text" name="CODE" value="<?= htmlspecialchars($CODE) ?>"></td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('CURRENCY_DATE') ?>:</td>
        <td><? echo CalendarDate("DATE", $DATE, "find_form", "10") ?></td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('CURRENCY_COURSE') ?>:</td>
        <td><input type="text" name="COURSE" value="<?= htmlspecialchars($COURSE) ?>"></td>
    </tr>
    <? $oFilter->Buttons(["table_id" => $sTableID, "url" => $APPLICATION->GetCurPage(), "form" => "find_form"]); ?>
    <? $oFilter->End(); ?>
</form>

<?
$oAdminList->DisplayList();
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
?>