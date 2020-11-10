<?php

if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

/**

Salz Library v1.12.0
Created By Mochammad Faisal

Create Date 2019-03-01 13:20:00
Last Update 2020-11-07 02:05:40

 **/

class Salz
{
	public function __construct()
	{
		date_default_timezone_set("Asia/Jakarta");
		$this->CI = &get_instance();

		$this->CI->load->library('session');
		$this->CI->load->helper('url');
		$this->db = $this->CI->load->database('default', true);

		$this->sess   = $this->CI->session->userdata('auth');
		$this->userid = (isset($this->sess['userid']) ? $this->sess['userid'] : '');

	}

// save function

	public function insertData($table, $data, $is_create_date = true)
	{
		if ($is_create_date)
		{
			$data['create_date'] = date_now;
			$data['create_user'] = (isset($data['create_user']) ? $data['create_user'] : $this->userid);
		}

		return $this->db->insert($table, $data);
	}

	public function insertData_batch($table, $data, $is_create_date = true)
	{
		if ($is_create_date)
		{
			$new_data = array();

			foreach ($data as $key => $value)
			{
				$value['create_date'] = date_now;
				$data['create_user']  = (isset($data['create_user']) ? $data['create_user'] : $this->userid);

				$new_data[] = $value;
			}
			$data = $new_data;

		}

		return $this->db->insert_batch($table, $data);
	}

	public function insertData_batch_chunk($table, $data, $is_create_date = true, $chunk = 100)
	{
		if ($is_create_date)
		{
			$new_data = array();

			foreach ($data as $key => $value)
			{
				$value['create_date'] = date_now;
				$data['create_user']  = (isset($data['create_user']) ? $data['create_user'] : $this->userid);

				$new_data[] = $value;
			}
			$data = $new_data;

		}

		$this->db->trans_start();

		$chunk1 = array_chunk($data, $chunk);

		foreach ($chunk1 as $key => $vals)
		{
			$this->db->insert_batch($table, $vals);
		}

		return $this->db->trans_complete();
	}

	public function insertDataID($table, $data, $is_create_date = true)
	{
		if ($is_create_date)
		{
			$data['create_date'] = date_now;
			$data['create_user'] = (isset($data['create_user']) ? $data['create_user'] : $this->userid);
		}

        // return last insert id
		$insert    = $this->db->insert($table, $data);
		$insert_id = '';

		if ($insert)
		{
			$insert_id = $this->db->insert_id();
		}
		else
		{
			$insert_id = '';
		}

		return $insert_id;
	}

	public function updateData($table, $data, $where = array(), $is_update_date = true)
	{
		if ($is_update_date)
		{
			$data['update_date'] = date_now;
			$data['update_user'] = (isset($data['update_user']) ? $data['update_user'] : $this->userid);
		}

		foreach ($where as $key => $value)
		{
			$this->db->where($key, $value);
		}

		return $this->db->update($table, $data);
	}

	public function updateDataArr($table, $data, $whereCond = array(), $is_update_date = true)
	{
		if ($is_update_date)
		{
			$data['update_date'] = date_now;
			$data['update_user'] = (isset($data['update_user']) ? $data['update_user'] : $this->userid);
		}

		if (!empty($whereCond))
		{
			if (isset($whereCond['where']) && !empty($whereCond['where']))
			{
				foreach ($whereCond['where'] as $key => $value)
				{
					$this->db->where($key, $value);
				}
			}

			if (isset($whereCond['where_in']) && !empty($whereCond['where_in']))
			{
				foreach ($whereCond['where_in'] as $key => $value)
				{
					$this->db->where_in($key, $value);
				}
			}

			if (isset($whereCond['where_not_in']) && !empty($whereCond['where_not_in']))
			{
				foreach ($whereCond['where_not_in'] as $key => $value)
				{
					$this->db->where_not_in($key, $value);
				}
			}

		}

		return $this->db->update($table, $data);
	}

	public function updateData2($table, $data, $where = array(), $is_update_date = true)
	{
        // escaped set value
		if ($is_update_date)
		{
			$data['update_date'] = "'" . date_now . "'";
			$data['update_user'] = (isset($data['update_user']) ? $data['update_user'] : $this->userid);
		}

		foreach ($data as $key => $value)
		{
			$this->db->set($key, $value, false);
		}

		foreach ($where as $key => $value)
		{
			$this->db->where($key, $value);
		}

		return $this->db->update($table);
	}

	public function updateData_batch($table, $data, $FieldValueID = null, $is_update_date = true)
	{
		if ($is_update_date)
		{
			$new_data = array();
			foreach ($data as $key => $value)
			{
				$value['update_date'] = date_now;
				$data['update_user']  = (isset($data['update_user']) ? $data['update_user'] : $this->userid);

				$new_data[] = $value;
			}
			$data = $new_data;
		}

		return $this->db->update_batch($table, $data, $FieldValueID);
	}

	public function updateData_batch2($table, $data, $FieldValueID = null, $where = array(), $is_update_date = true)
	{
		if ($is_update_date)
		{
			$new_data = array();
			foreach ($data as $key => $value)
			{
				$value['update_date'] = date_now;
				$data['update_user']  = (isset($data['update_user']) ? $data['update_user'] : $this->userid);

				$new_data[] = $value;
			}
			$data = $new_data;
		}

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		return $this->db->update_batch($table, $data, $FieldValueID);
	}

	public function updateData_batch_chunk($table, $data, $FieldValueID = null, $where = array(), $is_update_date = true, $chunk = 100)
	{
		if ($is_update_date)
		{
			$new_data = array();
			foreach ($data as $key => $value)
			{
				$value['update_date'] = date_now;
				$data['update_user']  = (isset($data['update_user']) ? $data['update_user'] : $this->userid);

				$new_data[] = $value;
			}
			$data = $new_data;
		}

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		$this->db->trans_start();

		$chunk1 = array_chunk($data, $chunk);

		foreach ($chunk1 as $key => $vals)
		{
			$this->db->update_batch($table, $vals, $FieldValueID);
		}

		return $this->db->trans_complete();

	}

	public function deleteData($table, $where = array())
	{
		foreach ($where as $key => $value)
		{
			$this->db->where($key, $value);
		}

		return $this->db->delete($table);
	}

    // upload function
	public function uploadsData($inputpostName, $config = array(), $with_default = false)
	{
		if (!empty($config))
		{
			if ($with_default == true)
			{
				$new_namez = $_FILES[$inputpostName]['name'];
				$new_name  = microtime(true) . '.' . substr(strtolower(strrchr($new_namez, ".")), 1);
                // $configz['file_name'] = $new_name;
				$configz = array(
					'file_name'     => $new_name,
					'upload_path'   => './assets/uploads/',
                    // 'allowed_types' => 'gif|jpg|jpeg|png|bmp|pdf',
                    // 'encrypt_name' => true,
					'allowed_types' => '*',
					'max_size'      => '3000000',
					'remove_spaces' => true,
				);
			}

			foreach ($config as $key => $value)
			{
				$configz[$key] = $value;
			}

		}
		else
		{
			$new_namez = $_FILES[$inputpostName]['name'];
			$new_name  = microtime(true) . '.' . substr(strtolower(strrchr($new_namez, ".")), 1);
            // $configz['file_name'] = $new_name;
			$configz = array(
				'file_name'     => $new_name,
				'upload_path'   => './assets/uploads/',
                // 'allowed_types' => 'gif|jpg|jpeg|png|bmp|pdf',
                // 'encrypt_name' => true,
				'allowed_types' => '*',
				'max_size'      => '3000000',
				'remove_spaces' => true,
			);

		}
        // $this->load->library('upload', $configz);

// if(substr($configz['upload_path'], 0, 2) === "./"){

		$mkdir_path = $configz['upload_path'];
        // $mkdir_path = substr_replace($mkdir_path, '', 0, 2);

        // if(!file_exists($mkdir_path)){
        //     @mkdir($mkdir_path, 0755, TRUE);
        //     @chmod($mkdir_path, 0755);
        // }

		if (!is_dir($mkdir_path))
		{
            // check directory is exist
            // create recursive directory
			@mkdir($mkdir_path, 0755, true);
		}

        // }

		$this->CI->upload->initialize($configz);

		if (!$this->CI->upload->do_upload($inputpostName))
		{
			$error = $this->CI->upload->display_errors();
            // print_r($error);

			return array('success' => false, 'data' => $error);
		}
		else
		{
			$dataUpload = $this->CI->upload->data();
            // $filename = $dataUpload['file_name'];
			$data = array(
				'filename' => $dataUpload['file_name'],
				'filetype' => $dataUpload['file_type'],
				'filesize' => $dataUpload['file_size'],
			);

			return array('success' => true, 'data' => $data);

		}
	}

//method untuk upload gambar menggunakan plugin froala text editor
	public function uploadsDataLink($inputpostName, $config = array(), $with_default = false)
	{
		if (!empty($config))
		{
			if ($with_default == true)
			{
				$new_namez = $_FILES[$inputpostName]['name'];
				$new_name  = microtime(true) . '.' . substr(strtolower(strrchr($new_namez, ".")), 1);
                // $configz['file_name'] = $new_name;
				$configz = array(
					'file_name'     => $new_name,
					'upload_path'   => './assets/uploads/',
					'allowed_types' => 'jpg|jpeg|png',
                    // 'encrypt_name' => true,
					'allowed_types' => '*',
					'max_size'      => '3000000',
					'remove_spaces' => true,
				);
			}

			foreach ($config as $key => $value)
			{
				$configz[$key] = $value;
			}

		}
		else
		{
			$new_namez = $_FILES[$inputpostName]['name'];
			$new_name  = microtime(true) . '.' . substr(strtolower(strrchr($new_namez, ".")), 1);
            // $configz['file_name'] = $new_name;
			$configz = array(
				'file_name'     => $new_name,
				'upload_path'   => './assets/uploads/',
				'allowed_types' => 'jpg|jpeg|png',
                // 'encrypt_name' => true,
				'allowed_types' => '*',
				'max_size'      => '3000000',
				'remove_spaces' => true,
			);

		}
        // $this->load->library('upload', $configz);

		$mkdir_path = $configz['upload_path'];
		if (!is_dir($mkdir_path))
		{
            // check directory is exist
            // create recursive directory
			@mkdir($mkdir_path, 0755, true);
		}

		$this->CI->upload->initialize($configz);

		if (!$this->CI->upload->do_upload($inputpostName))
		{
			$error = $this->CI->upload->display_errors();
            // print_r($error);

			return array('success' => false, 'data' => $error);
		}
		else
		{
			$dataUpload = $this->CI->upload->data();
            // $filename = $dataUpload['file_name'];
            // $data = array(
            //     'filename'=>$dataUpload['file_name'],
            //     'filetype'=>$dataUpload['file_type'],
            //     'filesize'=>$dataUpload['file_size']
            // );
			$data = './assets/uploads/' . $dataUpload['file_name'];

			return array('success' => true, 'link' => $data, 'thumbnail' => $dataUpload['file_name']);
            // return array('success'=>true, 'data'=>$data);

		}
	}

// Get Function

	public function getWhereArr($select, $from, $whereCond = array(), $order = array(), $limit = array())
	{
		$this->db->select($select);
		$this->db->from($from);

        // print_r($whereCond);exit();

		if (!empty($whereCond))
		{
			if (isset($whereCond['where']) && !empty($whereCond['where']))
			{
				foreach ($whereCond['where'] as $key => $value)
				{
					$this->db->where($key, $value);
				}
			}

			if (isset($whereCond['where_in']) && !empty($whereCond['where_in']))
			{
				foreach ($whereCond['where_in'] as $key => $value)
				{
					$this->db->where_in($key, $value);
				}
			}

			if (isset($whereCond['where_not_in']) && !empty($whereCond['where_not_in']))
			{
				foreach ($whereCond['where_not_in'] as $key => $value)
				{
					$this->db->where_not_in($key, $value);
				}
			}

		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		if (!empty($limit))
		{
			if (isset($limit[1]))
			{
				$this->db->limit($limit[0], $limit[1]);
			}
			else
			{
				$this->db->limit($limit[0]);
			}
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereArr2($select, $from, $whereCond = array(), $group = array(), $order = array(), $limit = array())
	{
		$this->db->select($select);
		$this->db->from($from);

        // print_r($whereCond);exit();

		if (!empty($whereCond))
		{
			if (isset($whereCond['where']) && !empty($whereCond['where']))
			{
				foreach ($whereCond['where'] as $key => $value)
				{
					$this->db->where($key, $value);
				}
			}

			if (isset($whereCond['where_in']) && !empty($whereCond['where_in']))
			{
				foreach ($whereCond['where_in'] as $key => $value)
				{
					$this->db->where_in($key, $value);
				}
			}

			if (isset($whereCond['where_not_in']) && !empty($whereCond['where_not_in']))
			{
				foreach ($whereCond['where_not_in'] as $key => $value)
				{
					$this->db->where_not_in($key, $value);
				}
			}

		}

		if (!empty($group))
		{
			foreach ($group as $value)
			{
				$this->db->group_by($value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		if (!empty($limit))
		{
			if (isset($limit[1]))
			{
				$this->db->limit($limit[0], $limit[1]);
			}
			else
			{
				$this->db->limit($limit[0]);
			}
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereReplace($select, $from, $where = array(), $replace = '')
	{
		$this->db->select($select);
		$this->db->from($from);
		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		$get  = $this->db->get();
		$data = $get->row_array();
		$num  = $get->num_rows();
		if ($num > 0)
		{
			return $data[$select];
		}
		else
		{
			if (!empty($replace))
			{
                //Replace if result got false
				if (is_numeric($replace))
				{
					return $data[$select];
				}
				else
				{
					return $replace;
				}
			}
			else
			{
				return '';
			}
		}
	}

	public function getWhere($select, $from, $where = array(), $order = array(), $is_array = true, $is_result = true)
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		$get = $this->db->get();
		if ($is_array == false)
		{
			if ($is_result == false)
			{
				return $get;
			}
			else
			{
				return $get->result();
			}

		}
		else
		{
			return $get->result_array();

		}
	}

	public function getWhereGroup($select, $from, $where = array(), $group = array(), $order = array())
	{
		$this->db->select($select);
		$this->db->from($from);
		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		if (!empty($group))
		{
			foreach ($group as $key => $value)
			{
				$this->db->group_by($value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereGroupLike($select, $from, $where = array(), $like = array(), $group = array(), $order = array())
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		if (!empty($like))
		{
			foreach ($like as $key => $value)
			{
				$this->db->like($key, $value);
			}
		}

		if (!empty($group))
		{
			foreach ($group as $key => $value)
			{
				$this->db->group_by($value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhere_GroupAND($select, $from, $where = array(), $where_group = array(), $group = array(), $order = array())
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		if (!empty($where_group))
		{
			$this->db->group_start();
			foreach ($where_group as $key => $value)
			{
				$this->db->where($key, $value);
			}
			$this->db->group_end();
		}

		if (!empty($group))
		{
			foreach ($group as $key => $value)
			{
				$this->db->group_by($value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhere_GroupANDOR($select, $from, $where = array(), $where_group = array(), $group = array(), $order = array())
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		if (!empty($where_group))
		{
			$this->db->group_start();
			foreach ($where_group as $key => $value)
			{
				$this->db->or_where($key, $value);
			}
			$this->db->group_end();
		}

		if (!empty($group))
		{
			foreach ($group as $key => $value)
			{
				$this->db->group_by($value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereIN($select, $from, $where = array(), $whereIN = array(), $order = array())
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		if (!empty($whereIN))
		{
			foreach ($whereIN as $key => $value)
			{
				$this->db->where_in($key, $value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereINLimit($select, $from, $where = array(), $whereIN = array(), $order = array(), $limit = '')
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		if (!empty($whereIN))
		{
			foreach ($whereIN as $key => $value)
			{
				$this->db->where_in($key, $value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		if (!empty($limit))
		{
			$this->db->limit($limit);
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereLimit($select, $from, $where = array(), $order = array(), $limit = '', $start = '')
	{
		$this->db->select($select);
		$this->db->from($from);
		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		if (!empty($limit))
		{
			if (!empty($start))
			{
				$this->db->limit($limit, $start);
			}
			else
			{
				$this->db->limit($limit);
			}
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function countAllWhereOnly($select, $from, $where)
	{
		$countAll = 0;

		$this->db->select($select);
		$this->db->from($from);
		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		$countAll = $this->db->get();

		return $countAll->num_rows();
	}

	public function getWhereRow($select, $from, $where = array(), $order = array(), $single = true)
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		$get   = $this->db->get();
		$hasil = $get->row_array();
		if ($single == false)
		{
			return $hasil;
		}
		else
		{
			return $hasil[$select];

		}
	}

	public function getWhereRowLimit($select, $from, $where = array(), $order = array(), $single = true, $limit = '')
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		if (!empty($limit))
		{
			$this->db->limit($limit);
		}

		$get   = $this->db->get();
		$hasil = $get->row_array();
		if ($single == false)
		{
			return $hasil;
		}
		else
		{
			return $hasil[$select];

		}
	}

	public function getWhereOrder($select, $from, $where = array(), $order = array())
	{
		$this->db->select($select);
		$this->db->from($from);
		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}
		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereJoin($select, $from, $join = array(), $where = array(), $order = '')
	{
		$this->db->select($select);
		$this->db->from($from);
		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		if (!empty($join))
		{
			foreach ($join as $key => $value)
			{
				$this->db->join($key, $value);
			}
		}

		if (!empty($order))
		{
			$this->db->order_by($order);
		}
		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereJoin2($select, $from, $join = array(), $joinCond = array(), $where = array(), $group = array(), $order = array())
	{
		$this->db->select($select);
		$this->db->from($from);
		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		if (!empty($join))
		{
			$countJoin = (count($join) > 0 ? count($join) : count($join) + 1);
			$i         = 0;
			foreach ($join as $key => $value)
			{
				if (!empty($joinCond))
				{
					$this->db->join($key, $value, $joinCond[$i]);
				}
				else
				{
					$this->db->join($key, $value);
				}

				$i++;
			}
		}

		if (!empty($group))
		{
			foreach ($group as $value)
			{
				$this->db->group_by($value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereJoinGroup($select, $from, $join = array(), $where = array(), $group = array(), $order = array())
	{
		$this->db->select($select);
		$this->db->from($from);
		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		if (!empty($join))
		{
			foreach ($join as $key => $value)
			{
				$this->db->join($key, $value);
			}
		}

		if (!empty($group))
		{
			foreach ($group as $value)
			{
				$this->db->group_by($value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereJoinGroupLimit($select, $from, $join = array(), $where = array(), $group = '', $order = array(), $limit = '')
	{
		$this->db->select($select);
		$this->db->from($from);
		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		if (!empty($join))
		{
			foreach ($join as $key => $value)
			{
				$this->db->join($key, $value);
			}
		}

		if (!empty($group))
		{
			$this->db->group_by($group);
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		if (!empty($limit))
		{
			$this->db->limit($limit);
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereJoinLeft($select, $from, $join = array(), $where = array(), $order = '')
	{
		$this->db->select($select);
		$this->db->from($from);
		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		if (!empty($join))
		{
			foreach ($join as $key => $value)
			{
				$this->db->join($key, $value, 'left');
			}
		}

		if (!empty($group))
		{
			foreach ($group as $key => $value)
			{
				$this->db->group_by($value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}
		$get = $this->db->get();

		return $get->result_array();
	}
	public function getWhereJoinLeftGroup($select, $from, $join = array(), $where = array(), $group = array(), $order = array())
	{
		$this->db->select($select);
		$this->db->from($from);
		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		if (!empty($join))
		{
			foreach ($join as $key => $value)
			{
				$this->db->join($key, $value, 'left');
			}
		}

		if (!empty($order))
		{
			$this->db->order_by($order);
		}
		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereOR($select, $from, $where = array(), $where_or = array())
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, "'" . $value . "'", false);
			}
		}

		if (!empty($where_or))
		{
			foreach ($where_or as $key => $value)
			{
				$this->db->or_where($key, "'" . $value . "'", false);
			}
		}

		$get = $this->db->get();

		return $get->result_array();

	}

	public function getWhereLike($select, $from, $where = array(), $like = array())
	{
		$this->db->select($select);
		$this->db->from($from);
		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		if (!empty($like))
		{
			foreach ($like as $key => $value)
			{
				$value = $this->db->escape_like_str($value);
				$this->db->like($key, $value);
			}
		}
		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereLikeOrderGroup($select, $from, $where = array(), $like = array(), $order = array(), $group = array())
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		if (!empty($like))
		{
			foreach ($like as $key => $value)
			{
				$value = $this->db->escape_like_str($value);
				$this->db->like($key, $value);
			}
		}

		if (!empty($group))
		{
			foreach ($group as $value)
			{
				$this->db->group_by($value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereLikeOrderGroupLimit($select, $from, $where = array(), $like = array(), $order = array(), $group = array(), $limit = '')
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		if (!empty($like))
		{
			foreach ($like as $key => $value)
			{
				$value = $this->db->escape_like_str($value);
				$this->db->like($key, $value);
			}
		}

		if (!empty($group))
		{
			foreach ($group as $value)
			{
				$this->db->group_by($value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		if (!empty($limit))
		{
			$this->db->limit($limit);
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereORLikeJoin($select, $from, $where = array(), $like = array(), $join = array(), $joinCond = array(), $order = array(), $group = array(), $limit = array())
	{
        // or like
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		if (!empty($like))
		{
			$this->db->group_start();
			foreach ($like as $key => $value)
			{
				$value = $this->db->escape_like_str($value);
				$this->db->or_like($key, $value);
			}
			$this->db->group_end();
		}

		if (!empty($join))
		{
			$countJoin = (count($join) > 0 ? count($join) : count($join) + 1);
			$i         = 0;
			foreach ($join as $key => $value)
			{
				if (!empty($joinCond))
				{
					$this->db->join($key, $value, $joinCond[$i]);
				}
				else
				{
					$this->db->join($key, $value);
				}

				$i++;
			}
		}

		if (!empty($group))
		{
			foreach ($group as $value)
			{
				$this->db->group_by($value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		if (!empty($limit))
		{
			if (isset($limit[1]))
			{
				$this->db->limit($limit[0], $limit[1]);
			}
			else
			{
				$this->db->limit($limit[0]);
			}
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getLikeArr($select, $from, $whereCond = array(), $like = array(), $join = array(), $joinCond = array(), $order = array(), $group = array(), $limit = array())
	{
        // or like
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($whereCond))
		{
			if (isset($whereCond['where']) && !empty($whereCond['where']))
			{
				foreach ($whereCond['where'] as $key => $value)
				{
					$this->db->where($key, $value);
				}
			}

			if (isset($whereCond['where_in']) && !empty($whereCond['where_in']))
			{
				foreach ($whereCond['where_in'] as $key => $value)
				{
					$this->db->where_in($key, $value);
				}
			}

			if (isset($whereCond['where_not_in']) && !empty($whereCond['where_not_in']))
			{
				foreach ($whereCond['where_not_in'] as $key => $value)
				{
					$this->db->where_not_in($key, $value, false);
				}
			}

		}

		if (!empty($like))
		{
			$this->db->group_start();
			foreach ($like as $key => $value)
			{
				$value = $this->db->escape_like_str($value);
				$this->db->or_like($key, $value);
			}
			$this->db->group_end();
		}

		if (!empty($join))
		{
			$countJoin = (count($join) > 0 ? count($join) : count($join) + 1);
			$i         = 0;
			foreach ($join as $key => $value)
			{
				if (!empty($joinCond))
				{
					$this->db->join($key, $value, $joinCond[$i]);
				}
				else
				{
					$this->db->join($key, $value);
				}

				$i++;
			}
		}

		if (!empty($group))
		{
			foreach ($group as $value)
			{
				$this->db->group_by($value);
			}
		}

		if (!empty($order))
		{
			foreach ($order as $key => $value)
			{
				$this->db->order_by($key, $value);
			}
		}

		if (!empty($limit))
		{
			if (isset($limit[1]))
			{
				$this->db->limit($limit[0], $limit[1]);
			}
			else
			{
				$this->db->limit($limit[0]);
			}
		}

		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereIN_SETLike($select, $from, $where = array(), $like = array(), $in_set = array(), $cond_inset = false)
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		if (!empty($in_set))
		{
			foreach ($in_set as $key => $value)
			{
				if ($cond_inset == true)
				{
                    //memunculkan data yang sama dengan $value
					$cond_insetx = "!= ";
				}
				else
				{
                    //memunculkan data yang tidak sama dengan $value
					$cond_insetx = "= ";
				}

				$find = 'FIND_IN_SET(`' . $key . '`, \'' . $value . '\' )' . $cond_insetx;

				$this->db->where($find, 0);
			}
		}

		if (!empty($like))
		{
			foreach ($like as $key => $value)
			{
				$value = $this->db->escape_like_str($value);
				$this->db->like($key, $value);
			}
		}
		$get = $this->db->get();

		return $get->result_array();
	}

	public function getWhereNum($select, $from, $where = array(), $count = false, $rows = true)
	{
		$this->db->select($select);
		$this->db->from($from);
		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		$get    = $this->db->get();
		$hasil  = $get->row_array();
		$hasil2 = $get->result_array();
		$num    = $get->num_rows();

		if ($num > 0)
		{
			if ($count == true)
			{
				return $num;
			}
			else
			{
				if ($rows == false)
				{
					return $hasil2;
				}
				else
				{
					return $hasil[$select];
				}
			}
		}
		else
		{
			return 0;
		}
	}

	public function getWhereNumLike($select, $from, $where = array(), $like = array(), $count = false, $rows = true)
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		if (!empty($like))
		{
			foreach ($like as $key => $value)
			{
				$value = $this->db->escape_like_str($value);
				$this->db->like($key, $value);
			}
		}

		$get    = $this->db->get();
		$hasil  = $get->row_array();
		$hasil2 = $get->result_array();
		$num    = $get->num_rows();

		if ($num > 0)
		{
			if ($count == true)
			{
				return $num;
			}
			else
			{
				if ($rows == false)
				{
					return $hasil2;
				}
				else
				{
					return $hasil[$select];
				}
			}
		}
		else
		{
			return 0;
		}
	}

	public function getWhereNumLikeOR($select, $from, $where = array(), $like = array(), $or_like = array(), $count = false, $rows = true)
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}

		if (!empty($like))
		{
			foreach ($like as $key => $value)
			{
				$value = $this->db->escape_like_str($value);
				$this->db->like($key, $value);
			}
		}

		if (!empty($or_like))
		{
			foreach ($or_like as $key => $value)
			{
				$value = $this->db->escape_like_str($value);
				$this->db->or_like($key, $value);
			}
		}

		$get    = $this->db->get();
		$hasil  = $get->row_array();
		$hasil2 = $get->result_array();
		$num    = $get->num_rows();

		if ($num > 0)
		{
			if ($count == true)
			{
				return $num;
			}
			else
			{
				if ($rows == false)
				{
					return $hasil2;
				}
				else
				{
					return $hasil[$select];
				}
			}
		}
		else
		{
			return 0;
		}
	}

	public function getWhereNumJoin($select, $from, $where = array(), $join = array(), $count = false, $rows = true)
	{
		$this->db->select($select);
		$this->db->from($from);

		if (!empty($join))
		{
			foreach ($join as $key => $value)
			{
				$this->db->join($key, $value);
			}
		}

		if (!empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($key, $value);
			}
		}
		$get    = $this->db->get();
		$hasil  = $get->row_array();
		$hasil2 = $get->result_array();
		$num    = $get->num_rows();

		if ($num > 0)
		{
			if ($count == true)
			{
				return $num;
			}
			else
			{
				if ($rows == false)
				{
					return $hasil2;
				}
				else
				{
					return $hasil[$select];
				}
			}
		}
		else
		{
			return 0;
		}
	}

	public function limit_txt($text, $limit = 500, $replace = false, $replace_txt = array())
	{
		$string = strip_tags($text);
		if (strlen($string) > $limit)
		{
            // truncate string
			$stringCut = substr($string, 0, $limit);
			$endPoint  = strrpos($stringCut, ' ');

            //if the string doesn't contain any space then it will cut without word basis.
			$string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
			$string .= ' ...';
            // $string .= '... <a href="/this/story">Read More</a>';
		}

		if ($replace == true)
		{
			$string = str_replace($replace_txt[0], $replace_txt[1], $string);

			return $string;

		}
		else
		{
			return $string;
		}
	}

	public function newline_txt($text, $width = 12, $break = "\n", $cut = false)
	{
		$string = wordwrap($text, $width, $break, $cut);

		return $string;
	}

	public function formatDate($date, $format = '')
	{
		if (!empty($format))
		{
			$format = $format;
		}
		else
		{
			$format = 'd M, Y';
            // $format = 'd-m-Y';
		}

		if ($date == '0000-00-00' || $date == '')
		{
			$hasil = '--';
		}
		else
		{
			$hasil = date($format, strtotime($date));
		}

		return $hasil;
	}

	public function formatDate2($tanggal, $format)
	{
		$bulan = array(
			1 => 'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember',
		);
		$pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun
		if ($format == 'm' || 'M')
		{
            // return $bulan[(int)];
		}
		else
		{
			return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
		}
	}

	public function formatBulan($tgl)
	{
		$bulan = array(
			1 => 'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember',
		);

		return $bulan[(int) $tgl];
	}

	public function timeAgo($timestamp)
	{
		$datetime1 = new DateTime("now");
		$datetime2 = create_date($timestamp);
		$diff      = date_diff($datetime1, $datetime2);
		$timemsg   = '';
		if ($diff->y > 0)
		{
			$timemsg = $diff->y . ' year' . ($diff->y > 1 ? "'s" : '');

		}
		elseif ($diff->m > 0)
		{
			$timemsg = $diff->m . ' month' . ($diff->m > 1 ? "'s" : '');
		}
		elseif ($diff->d > 0)
		{
			$timemsg = $diff->d . ' day' . ($diff->d > 1 ? "'s" : '');
		}
		elseif ($diff->h > 0)
		{
			$timemsg = $diff->h . ' hour' . ($diff->h > 1 ? "'s" : '');
		}
		elseif ($diff->i > 0)
		{
			$timemsg = $diff->i . ' minute' . ($diff->i > 1 ? "'s" : '');
		}
		elseif ($diff->s > 0)
		{
			$timemsg = $diff->s . ' second' . ($diff->s > 1 ? "'s" : '');
		}

		$timemsg = $timemsg . ' ago';

		return $timemsg;
	}

	public function persen($val1, $val2, $is_rounded = true, $rounded_digit = 2)
	{
		if ($val2 != 0)
		{
			$result = ($val1 * 100) / $val2;
		}
		else
		{
			$result = 0;
		}

		if ($is_rounded)
		{
			$result = round($result, $rounded_digit);
		}

		return $result . ' %';
	}

	public function pagination($table, $site_url, $limit = 10)
	{
		$page_limit = $limit;
		$from       = $this->input->get("page");

		$data['total'] = $this->getWhereNum('id', $table, array(), true);

		$page  = (!empty($this->input->get('page'))) ? $this->input->get('page') : 1;
		$mulai = ($page > 1 && !empty($page)) ? ($page * $page_limit) - $page_limit : 0;

		$data['pages'] = ceil($data['total'] / $page_limit);

		$config['page_query_string'] = true;
        // custom quuery parameter string page ^_^
		$config['query_string_segment'] = 'page';

		$config['base_url']         = site_url($site_url);
		$config['first_url']        = $config['base_url'] . '?' . $config['query_string_segment'] . '=1';
		$config['total_rows']       = $data['total'];
		$config['per_page']         = $page_limit;
		$config['use_page_numbers'] = true;
        // $config['num_links'] = 2;
        // $config['anchor_class'] = 'class="number"';
		$config['attributes'] = array('class' => 'page-numbers');

		$config['full_tag_open']  = '<div class="paginate_links" style="float : unset;">';
		$config['full_tag_close'] = "</div>";
        // $config['num_tag_open'] = '<li>';
        // $config['num_tag_close'] = '</li>';
		$config['cur_tag_open']  = '<span class="page-numbers current">';
		$config['cur_tag_close'] = "</span>";
        // $config['last_link'] = true;
        // $config['next_tag_open'] = "<li>";
        // $config['next_tagl_close'] = "</li>";
        // $config['prev_tag_open'] = "<li>";
        // $config['prev_tagl_close'] = "</li>";
        // $config['first_tag_open'] = "<li>";
        // $config['first_tagl_close'] = "</li>";
        // $config['last_tag_open'] = "<li>";
        // $config['last_tagl_close'] = "</li>";

		$this->pagination->initialize($config);

		return array('limit' => $page_limit, 'start' => $mulai);
	}

	public function fetch_data($limit, $start, $filter = '')
	{
		$this->db->select("*");
		$this->db->from(tbl_content);
		if (!empty($filter))
		{
			$this->db->where_in('kategori', $filter);
		}
		$this->db->order_by("id", "DESC");
		$this->db->limit($limit, $start);
		$query = $this->db->get();

		return $query;
	}

	public function SumArray($array)
	{
//harus key yg duplicatenya adalah nama
        //harus key yg countnya adalah total
		$sum = array_reduce($array, function ($a, $b)
		{
			if (isset($a[$b['nama']]))
			{
				$a[$b['nama']]['total'] += $b['total'];
			}
			else
			{
				$a[$b['nama']] = $b;
			}

			return $a;

		});

		return array_values($sum);
	}

/*

Array function

 */

function objectToArray($d) {
	if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map($this->objectToArray($d), $d);
        }
        else {
            // Return array
        	return $d;
        }
    }

    function object_to_array($data)
    {
    	if (is_array($data) || is_object($data))
    	{
    		$result = array();
    		foreach ($data as $key => $value)
    		{
    			$result[$key] = $this->object_to_array($value);
    		}
    		return $result;
    	}
    	return $data;
    }

    function arrayToObject($d) {
    	if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $d);
        }
        else {
            // Return object
        	return $d;
        }
    }

    function objectToArray2($dataJson){
    	$data = array();

    	foreach ($dataJson as $key => $value)
    	{
		# Remove ' from value
    		$value = str_replace("'", '', $value);

		# Set value as array not as array object from json_encode
    		$value = (array) json_decode($value, true);

		# Pushing into $old_data
    		$data = $value;
    	}
    	return $data;
    }

/**
 * Builds a tree array.
 * call function on looping values
 * ex : buildTree($row, $parentId)
 *
 * @param      array    $data  The data array
 * @param      integer  $parentId  The parent identifier
 *
 * @return     array    The tree.
 * refference : https://stackoverflow.com/a/13878662/10351006
 */
public function buildTree($data = array(), $parentId = 0, $parentIndex = 'parent_id', $idIndex = 'id')
{
	$branch = array();

	foreach ($data as $element)
	{
		if ($element[$parentIndex] == $parentId)
		{
			$children = $this->buildTree($data, $element[$idIndex], $parentIndex, $idIndex);

			if ($children)
			{
				$element['children'] = $children;
			}

			$branch[] = $element;
		}
	}

	return $branch;
}

/**
 * destroy tree stucture / buildTree array
 *
 * @param      <type>   $jsonArray    must convert json object to json array
 * @param      integer  $parentID     The parent id
 * @param      string   $parentIndex  The parent index
 * @param      string   $idIndex      The identifier index
 *
 * @return     array    ( description_of_the_return_value )
 */
public function parseJsonArray($jsonArray, $parentID = 0, $parentIndex = 'parent_id', $idIndex = 'id')
{
	$return = array();

	foreach ($jsonArray as $subArray)
	{
		$returnSubSubArray = array();

		if (isset($subArray['children']))
		{
			$returnSubSubArray = $this->parseJsonArray($subArray['children'], $subArray[$idIndex]);
		}

		$return[] = array($idIndex => $subArray[$idIndex], $parentIndex => $parentID);
		$return   = array_merge($return, $returnSubSubArray);
	}

	return $return;
}

/**
 *  convert multidimensional array to single array
 *  only single index
 *
 * @param      array  $array  The multidimenional array
 *
 * @return     array  ( single array )
 *
 * refference : https://stackoverflow.com/a/56260590/10351006
 */
public function nestedToSingle(array $array)
{
	$singleDimArray = [];

	foreach ($array as $item)
	{
		if (is_array($item))
		{
			$singleDimArray = array_merge($singleDimArray, $this->nestedToSingle($item));

		}
		else
		{
			$singleDimArray[] = $item;
		}
	}

	return $singleDimArray;
}

/**
 * Convert a multi-dimensional array into a single-dimensional array.
 * @author Sean Cannon, LitmusBox.com | seanc@litmusbox.com
 * @param  array $array The multi-dimensional array.
 * @return array
 */
public function array_flatten($array)
{
	if (!is_array($array))
	{
		return false;
	}
	$result = array();
	foreach ($array as $key => $value)
	{
		if (is_array($value))
		{
			$result = array_merge($result, $this->array_flatten($value));
		}
		else
		{
			$result = array_merge($result, array($key => $value));
		}
	}

	return $result;
}

public function array_flatten2($arr, $out = array())
{
	foreach ($arr as $item)
	{
		if (is_array($item))
		{
			$out = array_merge($out, $this->array_flatten2($item));
		}
		else
		{
			$out[] = $item;
		}
	}

	return $out;
}

public function array_flatten3($array, $prefix = '')
{
	$result = array();
	foreach ($array as $key => $value)
	{
		if (is_array($value))
		{
			$result = $result + $this->array_flatten3($value, $prefix . $key . '.');
		}
		else
		{
			$result[$prefix . $key] = $value;
		}
	}

	return $result;
}

public function array_flatten4($arr)
{
	$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($arr));

	return iterator_to_array($it, true);
}

/**
 * convert multidimensional array to single array
 * reduce tree structure to single array
 *
 * @param      <type>  $a      { array }
 * @param      array   $flat   The flat
 *
 * @return     array   ( return to single array )
 */
public function array_flatten5($a, $flat = [])
{
	$entry = [];
	foreach ($a as $key => $el)
	{
		if (is_array($el))
		{
			$flat = $this->array_flatten5($el, $flat);
		}
		else
		{
			$entry[$key] = $el;
		}
	}
	if (!empty($entry))
	{
		$flat[] = $entry;
	}

	return $flat;
}

/**
 * search data on multiple dimension array
 *
 * @param      array   $dataList        The data list
 * @param      string  $keyIndexSearch  The key index search
 * @param      string  $valueToFind     The value to find
 *
 * @return     array   ( return array by index result from searching )
 */
public function arraySearch($dataList = array(), $keyIndexSearch, $valueToFind)
{
        // refference : https://stackoverflow.com/a/24527099/10351006
        // search data on multiple dimension array
        // return only 1 array

	$key = array_search($valueToFind, array_column($dataList, $keyIndexSearch));

	return $dataList[$key];
}

public function arrayToColumn($arr = array(), $index = 'name', $value = 'value')
{
        //convert list value to column

        /*
        @param $arr = Array()
        @param index like name
        @param value like value

         */

        return array_column($arr, $value, $index);
    }

    public function array_column_multi($array, $column, $multi = true, $index_remove = true)
    {
    	$types = array_unique(array_column($array, $column));

    	$return = [];
    	foreach ($types as $type)
    	{
    		foreach ($array as $key => $value)
    		{
    			if ($type === $value[$column])
    			{
    				if ($index_remove)
    				{
    					unset($value[$column]);
    				}
    				if ($multi == false)
    				{
    					$return[$type] = $value;
    				}
    				else
    				{
    					$return[$type][] = $value;
    				}
    				unset($array[$key]);
    			}
    		}
    	}

    	return $return;
    }

    public function duplicate_multiarray($dataArray, $opsi = 2)
    {
        // remove duplicate on array multi-dimesional
        // recommend use $opsi 2 if there had string value
    	if ($opsi == 1)
    	{
    		array_unique($dataArray, SORT_REGULAR);
    	}
    	elseif ($opsi == 2)
    	{
    		return array_map("unserialize", array_unique(array_map("serialize", $dataArray)));
    	}
    }

/*
array sorting

sorting array yg hanya bisa dilakukan secara langsung
alias pemanggilan method function takan berfungsi
 */

public function sortArray($data = array(), $opsi = 1)
{
        /*
        only for single array
        sort() - sort arrays in ascending order
        rsort() - sort arrays in descending order
        asort() - sort associative arrays in ascending order, according to the value
        ksort() - sort associative arrays in ascending order, according to the key
        arsort() - sort associative arrays in descending order, according to the value
        krsort() - sort associative arrays in descending order, according to the key
         */

        switch ($opsi)
        {
        	case 1:
        	default:
        	return sort($data);
        	break;
        	case 2:
        	return rsort($data);
        	break;
        	case 3:
        	return asort($data);
        	break;
        	case 4:
        	return ksort($data);
        	break;
        	case 5:
        	return arsort($data);
        	break;
        	case 6:
        	return krsort($data);
        	break;
        }
    }

    public function array_sort_by_column(&$arr, $col, $dir = SORT_ASC)
    {
    	$sort_col = array();
    	foreach ($arr as $key => $row)
    	{
    		$sort_col[$key] = $row[$col];
    	}

    	array_multisort($sort_col, $dir, $arr);
    }

    public function sortArrayMulti($data = array(), $nama = 'total', $order = 'DESC')
    {
    	array_multisort(array_column($data, $nama), ($order == 'DESC' ? SORT_DESC : SORT_ASC), $data);
    }

    public function sortArrayMulti2($data = array())
    {
        // gagal, harus secara langsung
    	function cmp($a, $b)
    	{
    		if ($a['total'] == $b['total'])
    		{
    			return 0;
    		}

    		return ($a['total'] < $b['total']) ? -1 : 1;
    	}
        // script pemanggilannya :
    	uasort($data, 'cmp');
    }

    public function SortByKeyValue($data, $sortKey, $sort_flags = SORT_ASC)
    {
        // refference : https://stackoverflow.com/a/16563755/10351006

    	if (empty($data) or empty($sortKey))
    	{
    		return $data;
    	}

    	$ordered = array();
    	foreach ($data as $key => $value)
    	{
    		$ordered[$value[$sortKey]] = $value;
    	}

    	ksort($ordered, $sort_flags);

        // if($reindex){
        // array_values() added for identical result with multisort*

    	return array_values($ordered);

        // }else{
        // return $ordered;
        // }

    }

    public function reindexArraybyValue($data, $keyToIndex)
    {
        // create by mochammad faisal
        // date create 20/04/2020 10:52

    	$new_data = array();
    	foreach ($data as $key => $value)
    	{
    		$new_data[$value[$keyToIndex]] = $value;
    	}

    	return $new_data;
    }

// get min max value array
    public function getMaxValueArray($dataArray, $index_of_column = '')
    {
        /*
         * @param index_of_column ex. nama
         * @param dataArray list of data array
         */

        if (!empty($index_of_column))
        {
        	return max(array_column($dataArray, $index_of_column));
        }
        else
        {
        	return max($dataArray);
        }
    }

    public function getMinValueArray($dataArray, $index_of_column = '')
    {
        /*
         * @param index_of_column ex. nama
         * @param dataArray list of data array
         */

        if (!empty($index_of_column))
        {
        	return min(array_column($dataArray, $index_of_column));
        }
        else
        {
        	return min($dataArray);
        }
    }

// end of min max value array

/*

number formating

 */

public function numberFormat($number, $desimal_digit = 0, $desimal_separator = ".", $ribuan_separator = ",")
{
	$decimals      = $desimal_digit;
	$dec_point     = $desimal_separator;
	$thousands_sep = $ribuan_separator;

        /*$init_config = (isset($this->init_config['number']) ? $this->init_config['number'] : array());

        if(!empty($init_config)){

        if(!empty($init_config['separator_ribuan'])){
        $thousands_sep = $this->separator_ribuan;
        }

    }*/

    return number_format($number, $decimals, $dec_point, $thousands_sep);
}

public function shortNumberFormat($num)
{
        /*
        refference list of number :

        https://blog.prepscholar.com/what-comes-after-trillion
        https://www.mathsisfun.com/metric-numbers.html
         */

        // maks 100000000000000 = 100t

        if ($num > 1000)
        {
        	$x               = round($num);
        	$x_number_format = number_format($x);
        	$x_array         = explode(',', $x_number_format);
        	$x_parts         = array('k', 'M', 'B', 'T', 'P', 'E', 'Z', 'Y');
        	$x_count_parts   = count($x_array) - 1;
        	$x_display       = $x;
        	$x_display       = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        	$x_display .= $x_parts[$x_count_parts - 1];

        	return $x_display;

        }

        return $num;
    }

    private function penyebut($nilai)
    {
        // function pembantu terbilang
        // refference web : https://www.malasngoding.com/cara-mudah-membuat-fungsi-terbilang-dengan-php/
        // maks 100000000000000 = seratus trilyun

    	$nilai = abs($nilai);
    	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    	$temp  = "";
    	if ($nilai < 12)
    	{
    		$temp = " " . $huruf[$nilai];
    	}
    	elseif ($nilai < 20)
    	{
    		$temp = penyebut($nilai - 10) . " belas";
    	}
    	elseif ($nilai < 100)
    	{
    		$temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
    	}
    	elseif ($nilai < 200)
    	{
    		$temp = " seratus" . penyebut($nilai - 100);
    	}
    	elseif ($nilai < 1000)
    	{
    		$temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
    	}
    	elseif ($nilai < 2000)
    	{
    		$temp = " seribu" . penyebut($nilai - 1000);
    	}
    	elseif ($nilai < 1000000)
    	{
    		$temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
    	}
    	elseif ($nilai < 1000000000)
    	{
    		$temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
    	}
    	elseif ($nilai < 1000000000000)
    	{
    		$temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
    	}
    	elseif ($nilai < 1000000000000000)
    	{
    		$temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
    	}

    	return $temp;
    }

    private function penyebut_china($nilai)
    {
        // function pembantu terbilang
        // refference web : https://www.malasngoding.com/cara-mudah-membuat-fungsi-terbilang-dengan-php/
        // maks 100000000000000 = seratus trilyun

    	$nilai = abs($nilai);
    	$huruf = array("", "it", "Ji/No", "sa", "si", "go", "lak", "cit", "pek", "kau", "cap");
    	$temp  = "";
    	if ($nilai < 11)
    	{
    		$temp = " " . $huruf[$nilai];
    	}
    	elseif ($nilai < 20)
    	{
    		$temp = penyebut($nilai - 10) . " cap";
    	}
    	elseif ($nilai < 100)
    	{
    		$temp = penyebut($nilai / 10) . " cap" . penyebut($nilai % 10);
    	}
    	elseif ($nilai < 200)
    	{
    		$temp = " seratus" . penyebut($nilai - 100);
    	}
    	elseif ($nilai < 1000)
    	{
    		$temp = penyebut($nilai / 100) . " pek" . penyebut($nilai % 100);
    	}
    	elseif ($nilai < 2000)
    	{
    		$temp = " seribu" . penyebut($nilai - 1000);
    	}
    	elseif ($nilai < 1000000)
    	{
    		$temp = penyebut($nilai / 1000) . " ceng" . penyebut($nilai % 1000);
    	}
    	elseif ($nilai < 1000000000)
    	{
    		$temp = penyebut($nilai / 1000000) . " tiao" . penyebut($nilai % 1000000);
    	}

        //sampe juta

    	return $temp;
    }

    public function terbilang($nilai, $style = 4)
    {
        /*

        style :
        1 = SERATUS TRILYUN
        2 = seratus trilyun
        3 = Seratus Trilyun
        4 = Seratus trilyun

         */
        if ($nilai < 0)
        {
        	$hasil = "minus " . trim(penyebut($nilai));
        }
        else
        {
        	$hasil = trim(penyebut($nilai));
        }

        switch ($style)
        {
        	case 1:
        	$hasil = strtoupper($hasil);
        	break;
        	case 2:
        	$hasil = strtolower($hasil);
        	break;
        	case 3:
        	$hasil = ucwords($hasil);
        	break;
        	default:
        	$hasil = ucfirst($hasil);
        	break;
        }

        return $hasil;
    }

/*
roman function
 */

public function conv_romawi($number)
{
/*
refference simbol : https://en.wikipedia.org/wiki/List_of_Latin-script_letters
refference roman value : https://en.wiktionary.org/wiki/Appendix:Roman_numerals

 */

        // maks 500000 = C̄C̄C̄C̄C̄
$map         = array('C̄' => 100000, 'L̄' => 50000, 'X̅' => 10000, 'V̄' => 5000, 'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
$returnValue = '';
while ($number > 0)
{
	foreach ($map as $roman => $int)
	{
		if ($number >= $int)
		{
			$number -= $int;
			$returnValue .= $roman;
			break;
		}
	}
}

return $returnValue;
}

public function deromanize(String $number)
{
	$numerals = array('C̄' => 100000, 'L̄' => 50000, 'X̅' => 10000, 'V̄' => 5000, 'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
	$number   = str_replace(" ", "", strtoupper($number));
	$result   = 0;
	foreach ($numerals as $key => $value)
	{
		while (strpos($number, $key) === 0)
		{
			$result += $value;
			$number = substr($number, strlen($key));
		}
	}

	return $result;
}
public function romanize($number)
{
	$result   = "";
	$numerals = array('C̄' => 100000, 'L̄' => 50000, 'X̅' => 10000, 'V̄' => 5000, 'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
	foreach ($numerals as $key => $value)
	{
		$result .= str_repeat($key, $number / $value);
		$number %= $value;
	}

	return $result;
}

/*
end of roman function
 */

/*
end of number formating
 */

/* Notification */

public function statusNotifikasi($stat)
{
	if ($stat == 1)
	{
		return 'Pending';
	}
	elseif ($stat == 2)
	{
		return 'Verified';
	}
	else
	{
		return 'Un-Verified';
	}
}

public function checklistStatusNotifikasi($stat)
{
	if ($stat == 1)
	{
		return '<i class="fa fa-check text-green" aria-hidden="true"></i>';
	}
	else
	{
		return '<i class="fa fa-close text-red" aria-hidden="true"></i>';
	}
}

/* End of Notification */

    // end of class
}
