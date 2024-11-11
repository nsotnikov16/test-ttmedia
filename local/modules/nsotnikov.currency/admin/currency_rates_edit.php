<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use NSotnikov\Currency\Orm\CurrencyRateTable;
use Bitrix\Main\Type\DateTime;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

Loader::includeModule('nsotnikov.currency');

$POST_RIGHT = $APPLICATION->GetGroupRight("nsotnikov.currency");

if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));

$ID = isset($_REQUEST['ID']) ? (int)$_REQUEST['ID'] : 0;
$isEdit = $ID > 0;
$message = null;
$arError = [];
$APPLICATION->SetTitle(Loc::getMessage($isEdit ? "CURRENCY_EDIT" : "CURRENCY_ADD_NEW"));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!empty($_POST['save']) || !empty($_POST['apply']))) {

    $fields = [
        'CODE' => $_POST['CODE'],
        'COURSE' => $_POST['COURSE'] ? (float)$_POST['COURSE'] : '',
        'DATE' => $_POST['DATE'] ? new DateTime($_POST['DATE']) : '',
    ];

    if ($isEdit) {
        $res = CurrencyRateTable::update($ID, $fields);
    } else {
        $find = CurrencyRateTable::getList(['filter' => ['=CODE' => $fields['CODE']]])->fetch();
        if ($find) {
            $arError[] = ['id' => '', 'text' => Loc::getMessage("ERROR_FIND_CODE")];
        } else {
            $res = CurrencyRateTable::add($fields);
            if ($res->isSuccess()) $ID = $res->getId();
        }
    }

    if (isset($res) && !empty($res->getErrors())) {
        foreach ($res->getErrors() as $err) {
            $arError[] = ['id' => '', 'text' => $err->getMessage()];
        }
    }

    if (!empty($arError)) {
        $e = new CAdminException($arError);
        $message = new CAdminMessage(Loc::getMessage("ERROR"), $e);
    } elseif (!empty($_POST['save'])) {
        LocalRedirect("/bitrix/admin/currency_rates.php?lang=" . LANGUAGE_ID);
    }
}

$currencyData = $ID ? CurrencyRateTable::getById($ID)->fetch() : [];

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");


$aMenu = array(
    array(
        "TEXT" => Loc::getMessage("BACK"),
        "LINK" => "/bitrix/admin/currency_rates.php?lang=" . LANG . "&" . GetFilterParams("filter_", false),
        "ICON" => "btn_list",
    )
);

$context = new CAdminContextMenu($aMenu);
$context->Show();

if (isset($message) && $message)
    echo $message->Show();

$aTabs = array(
    array("DIV" => "edit1", "TAB" => Loc::getMessage("TAB"), "TITLE" => Loc::getMessage("TAB"))
);

$form = new CAdminForm("currency_rates_edit_form", $aTabs);

$form->Begin([
    "FORM_ACTION" => $_SERVER['REQUEST_URI'],
]);

$form->BeginNextFormTab();

if ($isEdit) {
    $form->BeginCustomField("ID", "ID:"); ?>
    <tr>
        <td width="40%"><? echo $form->GetCustomLabelHTML() ?></td>
        <td width="60%"><? echo htmlspecialchars($ID) ?></td>
    </tr>
<?
    $form->EndCustomField("ID", '');
}

$form->AddEditField("CODE", Loc::getMessage("CURRENCY_CODE"), true, ["size" => 3], $currencyData['CODE'] ?? $fields['CODE']);
$form->AddCalendarField("DATE", Loc::getMessage("CURRENCY_DATE"),  $currencyData['DATE'] ?? $fields['DATE'], true);
$form->AddEditField("COURSE", Loc::getMessage("CURRENCY_COURSE"), true, ["size" => 20], $currencyData['COURSE'] ?? $fields['COURSE']);

$form->Buttons(["back_url" => "currency_rates.php?lang=" . LANGUAGE_ID]);
$form->Show();
$form->EndTab();
$form->End();

?>
<script>
    //CAdminForm в методе AddCalendarField выдает калькулятор только с датой (без времени), поэтому поправим через JS
    document.addEventListener('DOMContentLoaded', () => {
        const calendar = document.querySelector('#tr_DATE .adm-calendar-icon');
        if (!calendar) return;
        calendar.setAttribute('onclick', "BX.calendar({node:this, field:'DATE', form: '', bTime: true, bHideTime: false});")
    })
</script>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
