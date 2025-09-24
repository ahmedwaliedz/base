<?php
namespace App\Traits\Models;

use App\Traits\GeneralTrait;
use App\Traits\Upload\BaseFilesTrait;
use Illuminate\Notifications\Notifiable;

trait BaseAuthModelTrait  {

    use Notifiable ,BaseFilesTrait ,GeneralTrait ,HasDynamicRelations;



}
