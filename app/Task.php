<?php

/**
 * Task
 *
 * @author Fernando Tholl <contato@fernandotholl.net>
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
 
    protected $fillable = ['title', 'status', 'sequence', 'description'];
    protected $dates = ['deleted_at', 'finished_at'];

}
