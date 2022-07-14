<?php

return [
    [
        'name' => 'Stock',
        'flag' => 'plugins.stock',
    ],
    [
        'name'        => 'Stocks',
        'flag'        => 'stock.index',
        'parent_flag' => 'plugins.stock',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'stock.create',
        'parent_flag' => 'stock.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'stock.edit',
        'parent_flag' => 'stock.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'stock.destroy',
        'parent_flag' => 'stock.index',
    ],

    [
        'name'        => 'Category',
        'flag'        => 'category.index',
        'parent_flag' => 'plugins.stock',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'category.create',
        'parent_flag' => 'category.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'category.edit',
        'parent_flag' => 'category.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'category.destroy',
        'parent_flag' => 'category.index',
    ],

];