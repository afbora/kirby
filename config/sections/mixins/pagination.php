<?php

use Kirby\Toolkit\Pagination;

return [
    'props' => [
        /**
         * Sets the number of items per page. If there are more items the pagination navigation will be shown at the bottom of the section.
         */
        'limit' => function (int $limit = 20) {
            return $limit;
        },
        /**
         * Sets the default page for the pagination. This will overwrite default pagination.
         */
        'page' => function (int $page = null) {
            return get('page', $page);
        },
    ],
    'computed' => [
        /**
         * prevent conflicting pagination with frontend
         * while calling blueprint sections
         * sp is specific variable for sections as section page
         */
        'variable' => function () {
            return 'sp';
        }
    ],
    'methods' => [
        'pagination' => function () {
            $pagination = new Pagination([
                'limit'    => $this->limit,
                'page'     => $this->page,
                'total'    => $this->total,
                'variable' => $this->variable
            ]);

            return [
                'limit'  => $pagination->limit(),
                'offset' => $pagination->offset(),
                'page'   => $pagination->page(),
                'total'  => $pagination->total(),
            ];
        },
    ]
];
