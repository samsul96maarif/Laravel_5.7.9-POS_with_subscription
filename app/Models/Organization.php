<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at', 'expire_date'];

  // fungsi untuk mengecek apakakah user memiliki store
  public function owner()
  {
    // mengecek bila kondisi role "1" berarti true berarti dia admin
    return $this->user_id;
  }
}
