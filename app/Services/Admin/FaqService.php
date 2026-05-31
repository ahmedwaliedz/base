<?php

namespace App\Services\Admin;

use App\Models\Faq;
use App\Services\Admin\Base\CrudBaseService;

class FaqService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(Faq::class);
    }

    public function index($request, $where = [])
    {
        return parent::index($request, $where)->with('translations');
    }

    public function switchIsActive(int|string $id): bool
    {
        $faq = Faq::query()->findOrFail($id);
        $faq->update(['is_active' => ! $faq->is_active]);

        return (bool) $faq->fresh()->is_active;
    }
}