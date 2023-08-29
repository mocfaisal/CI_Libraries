<?php

/**
 *
 * Created by Mochammad Faisal
 * Created at
 * Updated at
 *
 */

class Example extends CI_Controller {
    function __construct() {
        parent::__construct();
    }

    public function export_excel() {
        // $this->load->library("excel");
        $this->load->library("salz_spreadsheet");

        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');

        $excel_formating = $this->salz_spreadsheet->excel_formating;
        // print_r($excel_formating);exit;

        $data_body = $this->member->getDataMember();

        $spreadsheet = $this->salz_spreadsheet->init();
        $sheet       = $spreadsheet->getActiveSheet();
        $table_columns = array("No.", "Member Name", "Address", "Email", "Phone Number", "Joined Date Time", "member Status");
        $column = 'A';
        $no = 1;

        foreach ($table_columns as $field) {
            $sheet->setCellValue($column . '1', $field);
            $column++;
        }


        $excel_column = 'A';
        $excel_row = 2;

        foreach ($data_body as $row) {
            $sheet->setCellValue('A' . $excel_row, $no++);
            $sheet->setCellValue('B' . $excel_row, $row->first_name . " " . $row->last_name);
            $sheet->setCellValue('C' . $excel_row, $row->address);
            $sheet->setCellValue('D' . $excel_row, $row->email);
            $sheet->setCellValue('E' . $excel_row, $row->phone_number);
            $sheet->setCellValue('F' . $excel_row, $row->joined_dt);
            $sheet->setCellValue('G' . $excel_row, $row->member_status);
            $sheet->getStyle('E' . $excel_row)->getNumberFormat()->setFormatCode($excel_formating['stringFormat']['text']);
            $sheet->getStyle('F' . $excel_row)->getNumberFormat()->setFormatCode($excel_formating['dateFormat']['datetime']);

            $excel_row++;
        }

        $filename = "Data Member ($now).xlsx";

        // $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        // header('Content-Type: application/vnd.ms-excel');
        // header('Content-Disposition: attachment;filename="Product (' . $now . ').xls"');
        // $object_writer->save('php://output');



        // Process Export Final
        // $writer = new Xlsx($spreadsheet);
        $writer = $this->salz_spreadsheet->init_writer($spreadsheet, 'Xlsx');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // download file
        $writer->save('php://output');
    }

    // end of class
}
