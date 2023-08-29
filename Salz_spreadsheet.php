<?php

/**
 *
 * Created by Mochammad Faisal
 * Created at 2023-05-12 09:05:11
 * Updated at
 *
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\IOFactory;
// use PhpOffice\PhpSpreadsheet\Writer\IWriter;
// use PhpOffice\PhpSpreadsheet\Reader\IReader;

class Salz_spreadsheet {

    public $excel_formating;
    public $file_mimes;
    private $file_ext;

    function __construct() {
        $this->excel_formating = [
            'stringFormat' => [
                'text' => '@',
            ],
            'numberFormat' => [
                'commaStyle' => '_-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-',
                'percentage' => [
                    'default' => '0%',
                    '1dec'    => '0.0%',
                    '2dec'    => '0.00%',
                ],
            ],
            'dateFormat' => [
                'date' => 'yyyy-mm-dd',
                'time' => 'hh:mm:ss',
                'datetime' => 'yyyy-mm-dd hh:mm:ss',
            ]
        ];

        $this->file_mimes = [
            'text/x-comma-separated-values',
            'text/comma-separated-values',
            'application/octet-stream',
            'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            'application/excel',
            'application/vnd.msexcel',
            'text/plain',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $this->file_ext = [
            'reader' => [
                'Xlsx',
                'Xls',
                'Xml',
                'Ods',
                'Gnumeric',
                'Html',
                'Slk',
                'Csv'
            ],
            'writer' => [
                'Xlsx',
                'Xls',
                'Ods',
                'Html',
                'Pdf',
                'Csv'
            ]
        ];
    }

    function init() {
        return new Spreadsheet();
    }

    function init_writer($spreadsheet, $file_ext) {
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $file_ext);
        return $writer;
    }

    function init_reader($filename, $file_ext = '') {
        if (!empty($file_ext)) {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_ext);
            $data = $reader->load($filename);
        } else {
            $data = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
        }

        return $data;
    }

    // end of class
}
