<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\DataTables;

class OrdersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            // ->addColumn('action', 'orders.action')
            ->editColumn('action', function($model){// 編輯欄位顯示的資料長甚麼樣子
                $html = '<a class="btn btn-success" href="'.$model->id.'">查看</a>';
                return $html;
            }) 
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('orders-table')
                    ->columns($this->getColumns())
                    ->orderBy(0, 'desc') // 由新排到舊
                    ->parameters([
                        'pageLength' => 30, // 每頁30筆資料
                        'language' => config('datatables.i18n.tw'), // 繁中化，撈出(config/datatable.php)中的'i18n' => ['tw' =>[] ]
                    ]); 
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            new Column([ // 此處會將DataTable中的is_shipped欄位，改名成'是否運送'
                'title' => '是否運送',
                'data' => 'is_shipped', // 資料來源於is_shipped欄位
                'attributes' => [ // 滑鼠對瀏覽器中的欄位標題'是否運送'點擊右鍵 -> 選擇"檢查" 就可以靠到HTML中有 data-try = "test data"
                    'data-try' => 'test data'
                ]
            ]),
            Column::make('is_shipped'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::make('user_id'),
            new Column([ // 此處會將DataTable中的action欄位，改名成'功能'
                'title' => '功能',
                'data' => 'action', // 資料來源於action欄位
                'searchable' => false, // 此項目可否被搜尋(此處為不可被搜尋)
            ]),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Orders_' . date('YmdHis');
    }
}
