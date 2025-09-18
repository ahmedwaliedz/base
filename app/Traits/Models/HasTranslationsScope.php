<?php
namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

trait HasTranslationsScope {
    public function scopeSelectWithTrans(Builder $query, array $fields) {
        $table            = $this->getTable();             // ex: roles
        $translationTable = $this->getTranslationsTable(); // ex: role_translations
        $locale           = App::getLocale();

        $selects = [];

        foreach ($fields as $field) {
            $parts   = preg_split('/\s+as\s+/i', $field);
            $colName = $parts[0];
            $alias   = $parts[1] ?? null;

            if (in_array($colName, $this->translatedAttributes ?? [])) {
                $selects[] = $alias
                ? "$translationTable.$colName as $alias"
                : "$translationTable.$colName as $colName";
            } else {
                $selects[] = $alias
                ? "$table.$colName as $alias"
                : "$table.$colName";
            }
        }

        return $query
            ->without('translations') // remove relation eager load
            ->join(
                $translationTable,
                "$table.id",
                '=',
                "$translationTable.{$this->getForeignKey()}"
            )
            ->where("$translationTable.locale", $locale)
            ->select($selects)
            ->addSelect([])      // force only selected
            ->setEagerLoads([]); // prevent auto eager loads
    }

}
