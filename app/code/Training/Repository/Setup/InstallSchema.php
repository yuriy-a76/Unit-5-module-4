<?php
namespace Training\Repository\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table as DdlTable;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(
            SchemaSetupInterface $setup,
            ModuleContextInterface $context
    )
    {
        $setup->startSetup();
        $tableName = $setup->getTable('training_repository_example');
        $ddlTable = $setup->getConnection()->newTable($tableName);
        $ddlTable->addColumn(
                'example_id',
                DdlTable::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ]
            )->addColumn(
                'name',
                DdlTable::TYPE_TEXT,
                255,
                ['nullable' => false]
            )->addColumn(
                'created_at',
                DdlTable::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => DdlTable::TIMESTAMP_INIT]
            )->addColumn(
                'updated_at',
                DdlTable::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => DdlTable::TIMESTAMP_INIT]
            )->addIndex(
                $setup->getIdxName(
                    $tableName,
                    ['name'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['name'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            );

        $setup->getConnection()->createTable($ddlTable);
        $setup->endSetup();
    }
}
