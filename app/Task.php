<?php

/**
 * Task
 *
 * @author Fernando Tholl <contato@fernandotholl.net>
 */

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
 
    protected $fillable = ['title', 'status', 'sequence', 'user_id', 'description'];
    protected $dates = ['deleted_at', 'finished_at'];

    public function order($tasks)
    {
    	foreach ($tasks as $task => $sequence) {
    		DB::table('tasks')->where('id', $task)->update(['sequence' => $sequence]);
    	}
    }

    public function user() 
    {
        return $this->belongsTo('App\User');
    }

 }
