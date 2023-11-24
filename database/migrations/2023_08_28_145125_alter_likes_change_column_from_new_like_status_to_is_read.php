<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLikesChangeColumnFromNewLikeStatusToIsRead extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasColumn('likes', 'new_like_status')) {
            Schema::table('likes', function (Blueprint $table) {
                $table->dropColumn('new_like_status');
            });
        }
        if (!Schema::hasColumn('likes', 'is_read')) {
            Schema::table('likes', function (Blueprint $table) {
                $table->integer('is_read')->default(1);
            });
        }

    }
}