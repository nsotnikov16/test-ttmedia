<?
$scriptPath = __FILE__;
$dir = '';
if (strpos($scriptPath, 'bitrix') !== false) {
    $dir = '/bitrix';
} elseif (strpos($scriptPath, 'local') !== false) {
    $dir = '/local';
}
require($_SERVER["DOCUMENT_ROOT"] . $dir . "/modules/nsotnikov.currency/admin/currency_rates.php");
