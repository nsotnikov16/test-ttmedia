<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use NSotnikov\Currency\Orm\CurrencyRateTable;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Type\DateTime as BXDateTime;

class CurrencyListComponent extends CBitrixComponent
{
    private function getFilter()
    {
        if ($this->arParams['USE_FILTER'] === 'Y') {
            $filter = $_REQUEST['FILTER'];
            if (is_array($filter)) {
                foreach ($filter as $key => $value) {
                    if (!$value) {
                        unset($filter[$key]);
                        continue;
                    }
                    if (str_contains($key, 'DATE',)) {
                        $formattedDate = DateTime::createFromFormat('Y-m-d\TH:i', $value)->format('d.m.Y H:i:s');
                        $filter[$key] = new BXDateTime($formattedDate);
                    }
                    if (str_contains($key, 'COURSE')) $filter[$key] = (float) $value;
                }

                return $filter;
            }
        }

        return [];
    }

    private function getSelectedColumns()
    {
        return $this->arParams['COLUMNS'] ?? ['*'];
    }

    private function prepareData()
    {
        $this->arResult = [];

        $filter = $this->getFilter();
        $pageSize = (int) $this->arParams['PAGE_SIZE'];
        if ($pageSize <= 0) $pageSize = 10;

        $nav = new PageNavigation("currency_rates");
        $nav->allowAllRecords(true)
            ->setPageSize($pageSize)
            ->initFromUri();

        $dbResult = CurrencyRateTable::getList([
            'select' => $this->getSelectedColumns(),
            'filter' => $filter,
            'order' => ['DATE' => 'DESC'],
            'count_total' => true,
            'offset' => $nav->getOffset(),
            'limit' => $nav->getLimit(),
        ]);

        $this->arResult['ITEMS'] = [];
        while ($currency = $dbResult->fetch()) {
            $this->arResult['ITEMS'][] = $currency;
        }

        $nav->setRecordCount($dbResult->getCount());
        $this->arResult['NAV'] = $nav;
    }

    public function executeComponent()
    {
        if (!Loader::includeModule('nsotnikov.currency')) {
            ShowError(GetMessage('MODULE_IS_NOT_INSTALLED'));
            return;
        }

        $this->prepareData();
        $this->includeComponentTemplate();
    }
}
