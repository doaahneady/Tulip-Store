<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('attendance')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        try {
            $rows = DB::select('SHOW INDEX FROM attendance');
            $byKey = [];
            foreach ($rows as $r) {
                if ((int) ($r->Non_unique ?? 1) !== 0) {
                    continue;
                }
                $key = (string) ($r->Key_name ?? '');
                $col = (string) ($r->Column_name ?? '');
                if ($key === '' || $col === '') {
                    continue;
                }
                $byKey[$key][] = $col;
            }

            foreach ($byKey as $key => $cols) {
                $cols = array_values(array_unique($cols));
                sort($cols);
                if ($cols === ['date', 'employee_id']) {
                    DB::statement('ALTER TABLE attendance DROP INDEX `'.$key.'`');
                }
            }
        } catch (\Throwable $e) {
        }
    }

    public function down(): void {}
};
