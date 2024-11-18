<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Topic extends Model implements Auditable 
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;

    protected $auditInclude = [
        'name',
        'description',
        'status',
    ];

    public function documents(){
        return $this->hasMany(Document::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
