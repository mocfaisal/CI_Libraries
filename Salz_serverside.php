<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 *

DataTables ServerSide Library

Salz ServerSide Library v1.6.2
Created By Mochammad Faisal

Created Time    : 2019-02-28 01:22:00
Last Edited     : 2021-11-24 14:56:51


- Hanya untuk single database

To do :
- add where or

 */
class Salz_serverside
{
    private $is_record_total = true;

	private $whereCond = array();

    public function __construct($config = array())
    {
        $this->CI = &get_instance();
        // $this->CI->load->database();

        // $this->CI->dbcore = $this->CI->load->database('core',TRUE);
        if (!empty($config)) {
            if (isset($config['database']) && !empty($config['database'])) {
                $this->CI->db = $this->CI->load->database($config['database'], true);
            } else {
                $this->CI->db = $this->CI->load->database('default', true);
            }
        } else {
            $this->CI->db = $this->CI->load->database('default', true);
        }


        $this->CI->load->helper('form');
    }

    public function initialize($config = array())
    {
        if (!empty($config)) {
            if (isset($config['database']) && !empty($config['database'])) {
                $this->CI->db = $this->CI->load->database($config['database'], true);
            } else {
                $this->CI->db = $this->CI->load->database('default', true);
            }
        } else {
            $this->CI->db = $this->CI->load->database('default', true);
        }
    }

    /*

=========== description ===========

initialize with params

$this->load->library('salz_serverside', array('database'=>'inventory'), 'dtbl');



$select            String  => default '*'
$table             String  => must be filled
$joinTable         Array   => default empty
$joinCond          Array   => default empty
$column_search     Array   => must be set with alias like -> a.nama // field who wants to search
$column_order      Array   => must be set with alias like -> a.nama

// $where             Array   => default empty // oldest not used

$where = array(
                    'where' => array($key=>$value),
                    'or_where' => array($key=>$value),
                );

$group             Array   => default empty
$order             Array   => default empty
$is_json           Boolean => true //default, if false will direct the result to process data
manipulation

=========== How To Use ===========

$select         => 'a.name, b.time',
$table          => 'tbl_name as a',
$joinTable      => array('tbl_title as b' => 'a.id = b.id'),
$joinCond       => array('left', 'right'), // options : left, right, outer, inner, left outer, and right outer.
$column_search  => array('user_nama','user_email','user_alamat'),
$column_order   => array(null, 'user_nama','user_email','user_alamat'),
//$where          => array('id' => 1),
^^ now  $whereCond => array('where'=>array('id' => 1 )),
$group          => array('user_id'=>'asc'),
$order          => array('user_id'=>'asc'),
$is_json        => true / false

 */

    /*

    Important function !!
    Don't changes everything !!

     */

    private function _get_datatables_query($select = array(), $table = '', $joinTable = array(), $joinCond = array(), $column_search = array(), $column_order = array(), $whereCond = array(), $group = array(), $orderz = array())
    {
        $columns_count = count($column_search);

        if (is_array($select)) {
            // option for set select case feature
            // set array(field, False)
            $this->CI->db->select($select[0], $select[1]);
        } else {
            if ($select != '*') {
                $this->CI->db->select($select);
            }
        }

        $this->CI->db->from($table);

        if (!empty($joinTable)) {
            $countJoin = (count($joinTable) > 0 ? count($joinTable) : count($joinTable) + 1);
            $i = 0;
            foreach ($joinTable as $key => $value) {
                if (!empty($joinCond)) {
                    $this->CI->db->join($key, $value, $joinCond[$i]);
                } else {
                    $this->CI->db->join($key, $value);
                }

                $i++;
            }
        }


        if (!empty($whereCond)) {
            if (isset($whereCond['where']) && !empty($whereCond['where'])) {
                foreach ($whereCond['where'] as $key => $value) {
                    $this->CI->db->where($key, $value);
                }
            }

            if (isset($whereCond['or_where']) && !empty($whereCond['or_where'])) {
                foreach ($whereCond['or_where'] as $key => $value) {
                    $this->CI->db->or_where($key, $value);
                }
            }

            if (isset($whereCond['whereOrGroup']) && !empty($whereCond['whereOrGroup'])) {
                $this->CI->db->group_start();

                if (isset($whereCond['whereOrGroup']['where']) && !empty($whereCond['whereOrGroup']['where'])) {
                    foreach ($whereCond['whereOrGroup']['where'] as $keyz => $valuez) {
                        $this->CI->db->where($keyz, $valuez);
                    }
                }

                if (isset($whereCond['whereOrGroup']['or_where']) && !empty($whereCond['whereOrGroup']['or_where'])) {
                    foreach ($whereCond['whereOrGroup']['or_where'] as $keyz => $valuez) {
                        $this->CI->db->or_where($keyz, $valuez);
                    }
                }

                $this->CI->db->group_end();
            }

            if (isset($whereCond['like']) && !empty($whereCond['like'])) {
                foreach ($whereCond['like'] as $key => $value) {
                    $wildcard = 'both';
                    $val = $value;

                    if (is_array($value)) {
                        $val = $value[0];
                        $wildcard = $value[1];
                    }

                    $this->CI->db->like($key, $val, $wildcard);
                }
            }

            if (isset($whereCond['or_like']) && !empty($whereCond['or_like'])) {
                foreach ($whereCond['or_like'] as $key => $value) {
                    $wildcard = 'both';
                    $val = $value;
                    if (is_array($value)) {
                        $val = $value[0];
                        $wildcard = $value[1];
                    }
                    $this->CI->db->or_like($key, $val, $wildcard);
                }
            }

            if (isset($whereCond['not_like']) && !empty($whereCond['not_like'])) {
                foreach ($whereCond['not_like'] as $key => $value) {
                    $wildcard = 'both';
                    $val = $value;

                    if (is_array($value)) {
                        $val = $value[0];
                        $wildcard = $value[1];
                    }
                    $this->CI->db->not_like($key, $val, $wildcard);
                }
            }

            if (isset($whereCond['where_in']) && !empty($whereCond['where_in'])) {
                foreach ($whereCond['where_in'] as $key => $value) {
                    $this->CI->db->where_in($key, $value);
                }
            }

            if (isset($whereCond['where_not_in']) && !empty($whereCond['where_not_in'])) {
                foreach ($whereCond['where_not_in'] as $key => $value) {
                    $this->CI->db->where_not_in($key, $value);
                }
            }
        }

        // if (!empty($where)) {
        //     foreach ($where as $key => $value) {
        //         $this->CI->db->where($key, $value);
        //     }
        // }

        $i = 0;

        if (!empty($_POST['search']['value'])) {
            foreach ($column_search as $item) { // looping awal
                if (isset($_POST['search'])) {
                    if ($_POST['search']['value']) {
                        // jika datatable mengirimkan pencarian dengan metode POST

                        if ($i === 0) { // looping awal
                            $this->CI->db->group_start();
                            $this->CI->db->like($item, $_POST['search']['value']);
                        } else {
                            $this->CI->db->or_like($item, $_POST['search']['value']);
                        }

                        if (count($column_search) - 1 == $i) {
                            $this->CI->db->group_end();
                        }
                    }
                }
                $i++;
            }
        } else {
            // untuk search each columns
            // like and looping
            $likeArr = array();


            for ($x = 0; $x < $columns_count; $x++) {
                if (!empty($_POST['columns'][$x]['search']['value'])) {
                    // pushing data with key where not empty value
                    $likeArr[$_POST['columns'][$x]['data']] = $_POST['columns'][$x]['search']['value'];
                }
            }


            if (!empty($likeArr)) {
                $this->CI->db->group_start();
                foreach ($likeArr as $key => $val) {
                    $this->CI->db->like($key, $val);
                }
                $this->CI->db->group_end();
            }
        }

        if (!empty($group)) {
            foreach ($group as $value) {
                $this->CI->db->group_by($value);
            }
        }

        if (isset($_POST['order'])) {
            //  $this->CI->db->order_by($column_order[$_POST['order'][0]['column']],$_POST['order'][0]['dir']);
            $_post_column = $_POST['columns'];
            $_post_orderz = $_POST['order'][0];
            $_post_column_index = $_post_column[$_post_orderz['column']]['data'];

			if(isset($_post_orderz['dir'])){
				$_post_column_value = $_post_orderz['dir'];
				$this->CI->db->order_by($_post_column_index, $_post_column_value);
			}

            /*foreach ($orderz as $key => $value) {
               if(strpos($key, $_POST['columns'][$_POST['order'][0]['column']]['data']) !== false){
                unset($key);
                $orderz[$_POST['columns'][$_POST['order'][0]['column']]['data']] = $_POST['order'][0]['dir'];
            }else{
                $orderz[$key] = $value;
            }*/
            // }
        }

        // $order = $orderz;
        if (!empty($orderz)) {
            // $this->CI->db->order_by(key($order), $order[key($order)]);
            foreach ($orderz as $key => $value) {
                $this->CI->db->order_by($key, $value);
            }
        }
    }

    public function get_datatables($select = array(), $table, $joinTable = array(), $joinCond = array(), $column_search = array(), $column_order = array(), $whereCond = array(), $group = array(), $order = array(), $is_json = true)
    {
        $length = (isset($_POST['length']) ? $_POST['length'] : '');
        $start = (isset($_POST['start']) ? $_POST['start'] : '');
        $draw = (isset($_POST['draw']) ? $_POST['draw'] : '');

        $new_data = array();
        $output = array(
            "draw"            => '',
            "recordsTotal"    => 0,
            "recordsFiltered" => 0,
            "data"            => array(),
        );

        if ($length != -1) {
            $this->_get_datatables_query($select, $table, $joinTable, $joinCond, $column_search, $column_order, $whereCond, $group, $order);

            $this->CI->db->limit($length, $start);
            $query = $this->CI->db->get();
            $data = $query->result_array();

            $no = $start;
            foreach ($data as $row) {
                $row['no'] = $no + 1;

                $new_data[] = $row;

                $no++;
            }

            $record_filter = $this->count_filtered($select, $table, $joinTable, $joinCond, $column_search, $column_order, $whereCond, $group, $order);


            if ($this->is_record_total) {
                $record_total = $this->count_all($table, $whereCond, $group, $joinTable);
            } else {
                $record_total = $record_filter;
            }


            $output = array(
                "draw"            => $draw,
                "recordsTotal"    => $record_total,
                "recordsFiltered" => $record_filter,
                "data"            => $new_data,
            );
        }

        if ($is_json == false) {
            return $output;
        } else {
            die(json_encode($output));
        }
    }

    public function count_filtered($select, $table, $joinTable, $joinCond, $column_search, $column_order, $whereCond, $group, $orderz)
    {
        $this->_get_datatables_query($select, $table, $joinTable, $joinCond, $column_search, $column_order, $whereCond, $group, $orderz);
        $query = $this->CI->db->get();

        return $query->num_rows();
    }

    public function count_all($table, $whereCond = array(), $group = array(), $joinTable = array())
    {
        $this->CI->db->from($table);

        if (empty($joinTable)) {
            if (!empty($whereCond)) {
                if (isset($whereCond['where']) && !empty($whereCond['where'])) {
                    foreach ($whereCond['where'] as $key => $value) {
                        $this->CI->db->where($key, $value);
                    }
                }

                if (isset($whereCond['or_where']) && !empty($whereCond['or_where'])) {
                    foreach ($whereCond['or_where'] as $key => $value) {
                        $this->CI->db->or_where($key, $value);
                    }
                }

                if (isset($whereCond['like']) && !empty($whereCond['like'])) {
                    foreach ($whereCond['like'] as $key => $value) {
                        $this->CI->db->like($key, $value);
                    }
                }

                if (isset($whereCond['or_like']) && !empty($whereCond['or_like'])) {
                    foreach ($whereCond['or_like'] as $key => $value) {
                        $this->CI->db->or_like($key, $value);
                    }
                }

                if (isset($whereCond['not_like']) && !empty($whereCond['not_like'])) {
                    foreach ($whereCond['not_like'] as $key => $value) {
                        $this->CI->db->not_like($key, $value);
                    }
                }

                if (isset($whereCond['where_in']) && !empty($whereCond['where_in'])) {
                    foreach ($whereCond['where_in'] as $key => $value) {
                        $this->CI->db->where_in($key, $value);
                    }
                }

                if (isset($whereCond['where_not_in']) && !empty($whereCond['where_not_in'])) {
                    foreach ($whereCond['where_not_in'] as $key => $value) {
                        $this->CI->db->where_not_in($key, $value);
                    }
                }
            }

            // if (!empty($where)) {
            //     foreach ($where as $key => $value) {
            //         $this->CI->db->where($key, $value);
            //     }
            //     }

            if (!empty($group)) {
                foreach ($group as $value) {
                    $this->CI->db->group_by($value);
                }
            }
        }

        return $this->CI->db->count_all_results();
    }


    // set variable

    public function is_record_total($cond)
    {
        $this->is_record_total = $cond;
    }


    // end of class file
}
