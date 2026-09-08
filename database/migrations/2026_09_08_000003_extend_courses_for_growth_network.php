<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->string('level')->nullable()->after('description');
            $table->string('category')->nullable()->after('level');
            $table->string('delivery_mode')->default('offline')->after('category');
            $table->integer('capacity')->nullable()->after('sessions_count');
            $table->integer('enrolled_count')->default(0)->after('capacity');
            $table->date('start_date')->nullable()->after('enrolled_count');
            $table->date('end_date')->nullable()->after('start_date');
            $table->boolean('published')->default(false)->after('end_date');
            $table->text('short_description')->nullable()->after('published');
            $table->json('tags')->nullable()->after('short_description');

            $table->unique(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'published']);
            $table->index(['tenant_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // The up() used unique() not index(), so we must use dropUnique()
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = collect($sm->listTableIndexes('courses'))->keys();

            if ($indexes->contains('courses_tenant_id_slug_unique')) {
                $table->dropUnique(['tenant_id', 'slug']);
            }
            if ($indexes->contains('courses_tenant_id_published_index')) {
                $table->dropIndex(['tenant_id', 'published']);
            }
            if ($indexes->contains('courses_tenant_id_category_index')) {
                $table->dropIndex(['tenant_id', 'category']);
            }

            $table->dropColumn([
                'slug', 'level', 'category', 'delivery_mode',
                'capacity', 'enrolled_count', 'start_date', 'end_date',
                'published', 'short_description', 'tags',
            ]);
        });
    }
};
