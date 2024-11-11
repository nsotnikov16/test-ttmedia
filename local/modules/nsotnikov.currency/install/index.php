<?php

use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class nsotnikov_currency extends CModule
{

    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . "/version.php");

        $this->MODULE_ID = "nsotnikov.currency";
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("MODULE_DESCRIPTION");

        $this->PARTNER_NAME = Loc::getMessage("PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("PARTNER_URI");
    }

    public function DoInstall()
    {
        global $APPLICATION;

        if ($this->isVersionD7()) {
            ModuleManager::registerModule($this->MODULE_ID);
            $this->installDB();
            $this->installFiles();
            $this->installEvents();
            $this->registerAgent();
        } else {
            $APPLICATION->ThrowException(Loc::getMessage('MODULE_D7_REQUIRED'));
        }
    }

    public function DoUninstall()
    {
        $this->unregisterAgent();
        $this->uninstallEvents();
        $this->uninstallFiles();
        $this->uninstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installDB()
    {
        global $DB, $APPLICATION;
        $connection = Application::getConnection();
        $errors = false;

        if (!$DB->TableExists('currency_rates')) {
            $errors = $DB->RunSQLBatch(__DIR__ . '/db/' . $connection->getType() . '/install.sql');
        }

        if ($errors !== false) {
            $APPLICATION->ThrowException(implode("", $errors));
            return false;
        }

        return true;
    }

    public function uninstallDB($arParams = array())
    {
        global $DB, $APPLICATION;
        $connection = Application::getConnection();
        $errors = false;

        if (!array_key_exists("savedata", $arParams) || ($arParams["savedata"] != "Y") || $DB->TableExists('currency_rates')) {
            $errors = $DB->RunSQLBatch(__DIR__ . '/db/' . $connection->getType() . "/uninstall.sql");
        }

        if ($errors !== false) {
            $APPLICATION->ThrowException(implode("", $errors));
            return false;
        }

        return true;
    }

    public function installFiles()
    {
        CopyDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin", true, true);
        CopyDirFiles(__DIR__ . "/components", $_SERVER["DOCUMENT_ROOT"] . "/local/components", true, true);
    }

    public function uninstallFiles()
    {
        DeleteDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
        DeleteDirFilesEx("/local/components/nsotnikov");
    }

    public function installEvents() {}

    public function uninstallEvents() {}

    public function registerAgent()
    {
        \CAgent::AddAgent(
            "\\NSotnikov\\Currency\\CurrencyRateUpdater::updateRates();",
            $this->MODULE_ID,
            "N",
            86400,
            "",
            "Y"
        );
    }

    public function unregisterAgent()
    {
        \CAgent::RemoveAgent("\\NSotnikov\\Currency\\CurrencyRateUpdater::updateRates();", $this->MODULE_ID);
    }

    private function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion("main"), "14.00.00");
    }
}
