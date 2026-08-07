---
name: Database Migration Safety
description: Skill for creating safe, tenant-aware database migrations with proper indexing, foreign keys, rollback plans, and backup procedures for the Taalimu single-database multi-tenancy system.
---

# Database Migration Safety — مهارة إنشاء Migration آمن

## متى تُستخدم هذه المهارة؟
عند إنشاء أو تعديل جداول قاعدة البيانات، إضافة أعمدة، أو تعديل علاقات.

---

## القواعد الحديدية

1. **لا تعدل الـ Schema مباشرة** — استخدم Laravel Migrations فقط
2. **لا تعدل Migration قديم** تم تشغيله — أنشئ Migration جديد
3. **كل Migration يجب أن يكون قابلاً للتراجع** (`down()` method)
4. **اختبر في بيئة تجريبية أولاً** قبل النشر

---

## قالب Migration — إنشاء جدول جديد (مع Tenant)

```php
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
        Schema::create('table_name', function (Blueprint $table) {
            $table->id();

            // ⚠️ إلزامي للجداول الخاصة بالمستأجر
            $table->foreignId('tenant_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // الأعمدة الأساسية
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->decimal('amount', 10, 2)->default(0);

            $table->timestamps();
            $table->softDeletes(); // إذا كان الحذف الناعم مطلوبًا

            // ⚠️ Indexes إلزامية
            $table->index(['tenant_id', 'created_at']);
            $table->index(['tenant_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_name');
    }
};
```

---

## قالب Migration — إضافة عمود لجدول موجود

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('existing_table', function (Blueprint $table) {
            $table->string('new_column')->nullable()->after('existing_column');
            $table->index(['tenant_id', 'new_column']); // إذا كان يُستخدم في WHERE
        });
    }

    public function down(): void
    {
        Schema::table('existing_table', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'new_column']);
            $table->dropColumn('new_column');
        });
    }
};
```

---

## قالب Migration — إضافة Foreign Key

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('child_table', function (Blueprint $table) {
            $table->foreignId('parent_id')
                  ->nullable()
                  ->after('tenant_id')
                  ->constrained('parent_table')
                  ->nullOnDelete(); // أو cascadeOnDelete()
        });
    }

    public function down(): void
    {
        Schema::table('child_table', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
```

---

## قواعد الـ Indexing

### متى تضيف Index؟
| الحالة | نوع الـ Index |
|---|---|
| عمود `tenant_id` (دائمًا) | `index(['tenant_id', 'created_at'])` |
| عمود يُستخدم في `WHERE` | `index('column')` |
| عمود يُستخدم في `ORDER BY` | `index('column')` |
| تركيبة أعمدة في بحث | `index(['col1', 'col2'])` |
| عمود فريد | `unique('column')` |
| عمود فريد داخل المستأجر | `unique(['tenant_id', 'column'])` |

### متى لا تضيف Index؟
- أعمدة `text` أو `longText`
- جداول بأقل من 1000 سطر
- أعمدة نادرًا ما يُبحث فيها

---

## إنشاء Model المطابق

بعد إنشاء الـ Migration، أنشئ Model مطابق:

```php
<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NewModel extends Model
{
    use BelongsToTenant, HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'status',
        // ... باقي الأعمدة
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'settings' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
```

---

## قائمة التحقق قبل تشغيل الـ Migration

- [ ] الـ `down()` method يعكس كل تغييرات الـ `up()` بشكل صحيح
- [ ] كل جدول خاص بمستأجر يحتوي على `tenant_id` مع `constrained()->cascadeOnDelete()`
- [ ] الـ Indexes موجودة على الأعمدة المستخدمة في البحث والفرز
- [ ] لا يوجد تعارض مع Migrations موجودة
- [ ] الـ Model المطابق يستخدم `BelongsToTenant` trait
- [ ] تم تحديث `docs/11_DATABASE.md` و `docs/12_MODELS.md`

---

## أمر التشغيل

```bash
# إنشاء Migration
php artisan make:migration create_table_name_table

# تشغيل في بيئة التطوير
php artisan migrate

# التراجع عن آخر Migration
php artisan migrate:rollback --step=1

# التحقق من الحالة
php artisan migrate:status
```
