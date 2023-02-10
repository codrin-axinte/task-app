<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignIdFor(\App\Models\User::class);
            $table->foreignIdFor(\App\Models\Task::class, 'parent_id')->nullable();
            $table->text('title')->fulltext();
            $table->text('content')->fulltext()->nullable();
            $table->dateTime('due_date')->nullable();
            $table->unsignedTinyInteger('priority')->default(\App\Enums\Priority::Medium->value);
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('order_column')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
