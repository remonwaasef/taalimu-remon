<?php

namespace App\Models\Modules\Center\Models;

/**
 * @deprecated The Asset model was accidentally created under this anomalous
 *             namespace. It now lives at \App\Models\Asset. This subclass is
 *             kept temporarily for backward compatibility (e.g. serialized
 *             morph types or queued jobs referencing the old FQCN) and can be
 *             deleted once nothing references it anymore.
 */
class Asset extends \App\Models\Asset
{
    /** Keep the original table despite the subclass name inference. */
    protected $table = 'assets';
}
