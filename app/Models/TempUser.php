<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TempUser extends Model
{
    use HasFactory, Notifiable;

    /**
     * モデルに関連付けられているテーブル名
     *
     * @var string
     */
    protected $table = 'temp_users';

    /**
     * テーブルの主キー
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'verify_code',
        'status',
    ];

    /**
     * モデルの日付カラムの保存形式
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * モデルの日付カラム
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * 属性のデフォルト値
     *
     * @var array
     */
    protected $attributes = [
        'status' => 1,
    ];

    /**
     * 非表示にする属性
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'verify_code',
    ];
}
