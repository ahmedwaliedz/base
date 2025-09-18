<?php
namespace App\Models;

use App\Traits\GeneralTrait;
use App\Traits\Upload\BaseFilesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class BaseModel extends Model implements HasMedia {

   
}