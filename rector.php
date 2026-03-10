<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
// لاحظ الـ Namespace الجديد بدون سبيس بين Rector و Laravel
use RectorLaravel\Set\LaravelSetList; 

return static function (RectorConfig $rectorConfig): void {
    
    // تأكد من أن المسار يشير إلى مجلد src داخل البكج
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    // القواعد الأساسية لـ PHP 8.1
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
    ]);

    // استدعاء قواعد لارافل بشكل آمن
    if (class_exists(LaravelSetList::class)) {
        $rectorConfig->sets([
            LaravelSetList::LARAVEL_90
        ]);
    }
};