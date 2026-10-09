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
        // 1. Add points to users table
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('points')->default(0)->after('balance');
        });

        // 2. Tasks table
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // like_post, like_reel, follow_account, comment, story_view
            $table->string('target_url'); // Link to Instagram post/reel/profile
            $table->unsignedInteger('points_reward')->default(50);
            $table->unsignedInteger('max_completions')->nullable(); // Total times this task can be done globally
            $table->unsignedInteger('current_completions')->default(0);
            $table->text('instructions')->nullable();
            $table->boolean('is_daily')->default(true); // Can be repeated every day
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. User Task Completions table
        Schema::create('task_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->unsignedInteger('points_earned');
            $table->date('completed_date'); // To track daily repeats
            $table->string('status')->default('completed'); // completed, pending_review
            $table->timestamps();

            $table->unique(['user_id', 'task_id', 'completed_date'], 'user_task_daily_unique');
        });

        // 4. Points to Balance Conversions
        Schema::create('point_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('points_spent');
            $table->decimal('balance_credited', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_conversions');
        Schema::dropIfExists('task_completions');
        Schema::dropIfExists('tasks');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('points');
        });
    }
};
