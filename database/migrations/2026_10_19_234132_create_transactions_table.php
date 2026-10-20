
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Informasi transaksi
            $table->string('transaction_number')->unique();
            $table->string('description');
            $table->decimal('amount', 15, 2);

            // Status alur persetujuan
            $table->enum('status', [
                'draft',
                'pending_check',
                'pending_approval',
                'approved',
                'rejected',
            ])->default('draft');

            // Pengguna yang terlibat
            $table->foreignId('inputer_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('checker_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('approver_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Alasan jika transaksi ditolak
            $table->text('rejection_note')->nullable();

            // Waktu proses persetujuan
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
