<?

$localDir = $_SERVER["DOCUMENT_ROOT"] . "/local/modules/nsotnikov.currency/admin/currency_rates_edit.php";
$bitrixDir = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/nsotnikov.currency/admin/currency_rates_edit.php";
if (file_exists($localDir)) {
    require $localDir;
} else {
    require $bitrixDir;
}
