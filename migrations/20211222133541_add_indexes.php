<?php

use Phoenix\Exception\InvalidArgumentValueException;
use Phoenix\Migration\AbstractMigration;

class AddIndexes extends AbstractMigration
{
    /**
     * @throws InvalidArgumentValueException
     */
    protected function up(): void
    {
        $this->table("users")->addIndex('mail');
        $this->table("applications")->addIndex('key');
    }

    protected function down(): void
    {
        $this->table("users")->dropIndex('mail');
        $this->table("applications")->dropIndex('key');
    }
}
