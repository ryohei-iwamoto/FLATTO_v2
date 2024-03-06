<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けられているテーブル名。
     *
     * @var string
     */
    protected $table = 'favorites';

    /**
     * テーブルの主キー
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 代入可能な属性。
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'url',
        'start',
        'end',
        'status',
    ];

    /**
     * モデルの日付カラムの保存形式
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * 日付を扱う属性。
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
    
    // ユーザーモデルとのリレーションを定義する場合
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
