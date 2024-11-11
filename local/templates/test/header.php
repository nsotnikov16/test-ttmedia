<?

use Bitrix\Main\Page\Asset;

 if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);

?>
<html>

<head>
	<? $APPLICATION->ShowHead();
	Asset::getInstance()->addCss('//cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
	Asset::getInstance()->addJs('//cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js');
	?>
	<title><? $APPLICATION->ShowTitle() ?></title>
</head>

<body>
	<? $APPLICATION->ShowPanel() ?>
	<div class="container">
		<h1 class="text-center"><? $APPLICATION->ShowTitle() ?></h1>
	</div>