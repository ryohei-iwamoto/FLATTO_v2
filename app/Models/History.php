<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けられているテーブル名
     *
     * @var string
     */
    protected $table = 'histories';

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
        'user_id',
        'url',
        'start',
        'via',
        'end',
        'travel_method',
        'distance',
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
    
    // 他のモデルへのリレーションを定義する場合があります
    // 例: HistoryモデルがUserモデルに所属している場合
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
