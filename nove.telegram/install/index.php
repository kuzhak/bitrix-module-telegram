<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\DB\SqlHelper;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\IO;
use Bitrix\Main\Application;
use Bitrix\Main\DB\Connection;
use Bitrix\Main\DB\MysqliSqlHelper;

Loc::loadMessages(__FILE__);

class nove_telegram extends CModule
{
    public $MODULE_ID = 'nove.telegram';
    public $MODULE_GROUP_RIGHTS = 'Y';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    public function __construct()
    {
        $arModuleVersion = [];

        include(__DIR__ . '/version.php');

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('NOVE_TELEGRAM_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('NOVE_TELEGRAM_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('NOVE_TELEGRAM_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('NOVE_TELEGRAM_PARTNER_URI');
    }

    public function doInstall(): void
    {
        RegisterModule($this->MODULE_ID);
        $this->installDB();
        $this->installFiles();
        $GLOBALS['APPLICATION']->includeAdminFile(
            Loc::getMessage('NOVE_TELEGRAM_INSTALL_TITLE'),
            __DIR__ . '/step.php'
        );
    }

    public function doUninstall(): void
    {
        $this->unInstallDB();
        $this->unInstallFiles();
        UnRegisterModule($this->MODULE_ID);
        $GLOBALS['APPLICATION']->includeAdminFile(
            Loc::getMessage('NOVE_TELEGRAM_UNINSTALL_TITLE'),
            __DIR__ . '/unstep.php'
        );
    }

    public function installFiles($arParams = []): bool
    {
        $dir = new IO\Directory($this->GetPath() . '/admin/');
        if ($dir->isExists()) {
            foreach ($dir->getChildren() as $item) {
                if (
                    !$item->isFile()
                    || in_array($item->getName(), $this->getExcludedAdminFiles(), true)
                ) {
                    continue;
                }

                $file = new IO\File(
                    Application::getDocumentRoot()
                    . '/bitrix/admin/'
                    . $this->MODULE_ID
                    . '_'
                    . $item->getName()
                );

                $file->putContents(
                    '<'
                    . '?php require($_SERVER["DOCUMENT_ROOT"]."'
                    . str_replace('\\', '/', $this->GetPath(true))
                    . '/admin/'
                    . $item->getName()
                    . '");?'
                    . '>'
                );
            }
        }

        return true;
    }

    public function unInstallFiles(): void
    {
        $dir = new IO\Directory($this->GetPath() . '/admin/');
        if ($dir->isExists()) {
            foreach ($dir->getChildren() as $item) {
                if (
                    !$item->isFile()
                    || in_array($item->getName(), $this->getExcludedAdminFiles(), true)
                ) {
                    continue;
                }

                IO\File::deleteFile(
                    Application::getDocumentRoot()
                    . '/bitrix/admin/'
                    . $this->MODULE_ID
                    . '_'
                    . $item->getName()
                );
            }
        }
    }

    public function getPath($notDocumentRoot = false)
    {
        if ($notDocumentRoot) {
            return str_ireplace(realpath(Application::getDocumentRoot()), '', dirname(__DIR__));
        }

        return dirname(__DIR__);
    }

    protected function getExcludedAdminFiles(): array
    {
        return [
            'menu.php'
        ];
    }

    public function installDB(): void
    {
        Loader::includeModule($this->MODULE_ID);
        $connection = Application::getConnection();
        $sqlHelper = self::create($connection);

        foreach ($this->getModels() as $model) {
            $className = $this->getModelClassName($model);
            $tableName = $className::getTableName();
            if (!$connection->isTableExists($tableName)) {
                $entity = $className::getEntity();
                $entity->createDbTable();
                if ($sqlHelper) {
                    foreach ($entity->getScalarFields() as $field) {
                        if ($field) {
                            $sql = 'ALTER TABLE '
                                . $sqlHelper->quote($field->getEntity()->getDBTableName())
                                . ' MODIFY '
                                . $field->getName()
                                . ' '
                                . $field->getDataType()
                            ;
                            if ($field->isRequired()) {
                                $sql .= ' NOT NULL';
                            }
                            $connection->query($sql);
                        }
                    }
                }
                $indexFields = [];
                foreach ($className::getMap() as $field) {
                    if ($field->getParameter('index')) {
                        $indexFields[] = $field->getName();
                    }
                }
                if (empty($indexFields)) {
                    continue;
                }
                $connection->createIndex(
                    $tableName,
                    $tableName . '_index',
                    $indexFields
                );
            }
        }
    }

    public function unInstallDB(): void
    {
        Loader::includeModule($this->MODULE_ID);
        $connection = Application::getConnection();
        foreach (array_reverse($this->getModels()) as $model) {
            $className = $this->getModelClassName($model);
            $tableName = $className::getTableName();
            if ($connection->isTableExists($tableName)) {
                $connection->dropTable($tableName);
            }
        }
        Option::delete($this->MODULE_ID);
    }

    private function getModels(): array
    {
        return ["Event"];
    }

    private function getModelClassName(string $model): string
    {
        return 'Nove\\Telegram\\Infrastructure\\Model\\' . $model . 'Table';
    }

    private static function create(Connection $connection): SqlHelper
    {
        $sqlHelper = $connection->getSqlHelper();
        if ($sqlHelper instanceof MysqliSqlHelper) {
            return new MysqliSqlHelper($connection);
        }
        return $sqlHelper;
    }
}
