<?php
namespace App\Services\Admin\Export\Contracts;

interface ExporterInterface {
    public function export($query, array $options = []);
}
