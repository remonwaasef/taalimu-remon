<?php

namespace App\Traits;

/**
 * @deprecated استخدم BelongsToTenant بدلاً من هذا الـ Trait.
 * تم الإبقاء عليه مؤقتاً للتوافق مع الكود القديم.
 */
trait IdentifyTenant
{
    use BelongsToTenant;
}
