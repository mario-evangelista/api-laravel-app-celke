<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \OwenIt\Auditing\Auditable as AuditingAuditable; 
use OwenIt\Auditing\Contracts\Auditable;

class Bill extends Model implements Auditable
{
    use HasFactory, AuditingAuditable;

    // Indicar o nome da tabela
    protected $table = 'bills';

    // Indicar quais colunas podem ser cadastrada
    protected $fillable = ['name', 'bill_value', 'due_date'];

    // Ocultar as colunas
    protected $hidden = [
        // 'bill_value',
    ];
}