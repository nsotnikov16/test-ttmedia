<?

use Bitrix\Main\Loader;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Тестовое задание"); ?>
<div class="container">
    <?
    $APPLICATION->IncludeComponent(
        "nsotnikov:currency.list",
        ".default",
        array(
            "USE_FILTER" => "Y",
            "COLUMNS" => array(
                0 => "ID",
                1 => "CODE",
                2 => "DATE",
                3 => "COURSE",
            ),
            'PAGE_SIZE' => 30
        ),
        false
    );

    ?>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>