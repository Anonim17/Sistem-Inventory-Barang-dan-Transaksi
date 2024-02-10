<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\IncomeTransaction;
use App\Models\ExpenditureTransaction;
use App\Models\Category;
use App\Models\Brand;
use App\Models\UnitOfMeasurement;
use Illuminate\Support\Facades\DB;
 
class DashboardController extends Controller
{
    /**
     * Show the dashboard page.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data['itemTotal'] = currency(Item::count());

        $data['incomeTransactionTotal'] = currency(IncomeTransaction::count());

        $data['expenditureTransactionTotal'] = currency(ExpenditureTransaction::count());

        $query = '
            select
                cabang.keterangan_cabang,
                a.*, 
                x.income_transaction_items_amount,
                y.expenditure_transaction_items_amount,
                (IFNULL(x.income_transaction_items_amount, 0) -
                IFNULL(y.expenditure_transaction_items_amount, 0))
                as stock
            from
                items as a 
            left join
                ( SELECT
                SUM(amount) AS income_transaction_items_amount,
                item_id, id_cabang
            FROM
                income_transaction_items
            INNER JOIN
                income_transactions
            ON
                income_transaction_items.income_transaction_id = income_transactions.id
            WHERE
                income_transactions.remarks = "1"
            GROUP BY
                item_id, id_cabang
                ) as x 
            on
                a.id = x.item_id
            left join
                ( SELECT
                SUM(amount) AS expenditure_transaction_items_amount,
                item_id, id_cabang
            FROM
                expenditure_transaction_items
            INNER JOIN
                expenditure_transactions
            ON
                expenditure_transaction_items.expenditure_transaction_id = expenditure_transactions.id
            WHERE
                expenditure_transactions.remarks = "1"
            GROUP BY
                item_id, id_cabang
                ) as y 
            on
                a.id = y.item_id AND x.id_cabang = y.id_cabang
            LEFT JOIN (
                SELECT id_cabang, keterangan_cabang
                FROM cabang
            ) AS cabang
            ON x.id_cabang = cabang.id_cabang
            HAVING stock <= 5;
        ';

        $data['items'] = DB::select($query);
       
        return view('pages.dashboard.index', $data);
    }
}