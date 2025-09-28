<?php

namespace App\Models\Pc;

use CodeIgniter\Model;

class PcHistoryModel extends Model
{
    protected $table = 'pc_t_detail';   
        protected $primaryKey = 'id';      
    protected $allowedFields = [
        'pc_id',
        'date_update',
        'detail',
        'userid',
        'last_update'
    ];

    public $useTimestamps = false; 
}
