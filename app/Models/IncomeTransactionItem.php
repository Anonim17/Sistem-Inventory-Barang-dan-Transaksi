<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IncomeTransactionItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'income_transaction_id',
        'item_id',
        'amount',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function getWithSession($session)
    {
        if (empty($session)) {
            return null;
        }

        $query = DB::table('items as a')
                    ->select('a.id', 'a.part_number', 'a.description', 'a.price')
                    ;

        foreach ($session as $key => $value) {
            if ($key < 1) {
                $query->where('a.id', '=', $value['item_id']);
                continue;
            }

            $query->orWhere('a.id', '=', $value['item_id']);
        }

        $data = $query->orderBy('a.description', 'asc')
            ->get();

        if (empty($data)) {
            return null;
        }

        $changeKey = 0;

        foreach ($session as $key2 => $value2) {
            $itemExists = 0;

            foreach ($data as $key => $value) {
                if ($value->id == $value2['item_id']) {
                    $data[$key]->amount = $value2['amount'];
                    $itemExists = 1;
                }
            }

            if (!$itemExists) {
                unset($session[$key2]);
                $changeKey = 1;
            }
        }

        if ($changeKey) {
            $newSession = [];

            foreach ($session as $key => $value) {
                $newSession[] = $value;
            }

            session()->put('create-income-transaction-item', $newSession);
        }

        return $data;
    }

    /**
     * Get the income transaction that owns
     * the income transaction item.
     */

      public static function getByIncomeTransactionIdAndItemId($income_transaction_id, $item_id)
    {
        return self::where('income_transaction_id', '=', $income_transaction_id)
                    ->where('item_id', '=', $item_id)
                    ->first();
    }
    public function incomeTransaction()
    {
        return $this->belongsTo(IncomeTransaction::class);
    }

     /**
     * Get the item that owns the income transaction item.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
