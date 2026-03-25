<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    protected $table = 'backup_logs';

    protected $fillable = [
        'category',
        'type',
        'status',
        'file_path',
        'file_size',
        'message',
        'performed_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
