<?php

declare(strict_types=1);

namespace App\Database\Migration;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Invoices migration.
 */
final class Version20260326204337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Invoices migration';
    }

    public function up(Schema $schema): void
    {
        // Addresses
        $table = $schema->createTable('addresses');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('address_line_1', 'string', ['length' => 255]);
        $table->addColumn('address_line_2', 'string', ['length' => 255, 'notnull' => false]);
        $table->addColumn('city', 'string', ['length' => 255]);
        $table->addColumn('state', 'string', ['length' => 255, 'notnull' => false]);
        $table->addColumn('postal_code', 'string', ['length' => 20]);
        $table->addColumn('country', 'string', ['length' => 255]);

        $table->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create()
        );

        // Invoices
        $table = $schema->createTable('invoices');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('invoice_number', 'string', ['length' => 10]);
        $table->addColumn('status', 'integer');
        $table->addColumn('shipping_address_id', 'integer');
        $table->addColumn('billing_address_id', 'integer');
        $table->addColumn('due_date', 'datetime');
        $table->addColumn('raised_at', 'datetime');
        $table->addColumn('raised_by', 'integer');

        $table->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create()
        );

        // Invoice Number Sequence
        $table = $schema->createTable('invoice_number_sequence');
        $table->addColumn('year', 'integer');
        $table->addColumn('number', 'integer');

        $table->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('year', 'number')->create()
        );

        // Invoice Items
        $table = $schema->createTable('invoice_items');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('invoice_id', 'integer');
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('price_per_unit', 'decimal', ['precision' => 10, 'scale' => 2]);
        $table->addColumn('currency', 'string', ['length' => 3]);
        $table->addColumn('quantity', 'integer');
        $table->addColumn('total_price', 'decimal', ['precision' => 10, 'scale' => 2]);

        $table->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create()
        );

        // Invoice Audit Logs
        $table = $schema->createTable('invoice_audit_logs');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('invoice_id', 'integer');
        $table->addColumn('field', 'string', ['length' => 255]);
        $table->addColumn('old_value', 'string', ['length' => 255]);
        $table->addColumn('new_value', 'string', ['length' => 255]);
        $table->addColumn('changed_at', 'datetime');
        $table->addColumn('changed_by', 'integer');

        $table->addPrimaryKeyConstraint(
            PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create()
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('addresses');
        $schema->dropTable('invoices');
        $schema->dropTable('invoice_number_sequence');
        $schema->dropTable('invoice_items');
        $schema->dropTable('invoice_audit_logs');
    }
}
