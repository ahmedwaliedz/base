<?php
namespace App\Traits\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use Spatie\MediaLibrary\InteractsWithMedia;

trait BaseModelTrait {

    use GeneralTrait, InteractsWithMedia, FilterableTrait, HasDynamicRelations, ModelHasCacheTrait;

}
