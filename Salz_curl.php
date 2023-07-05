<?php

/*
 *
 * Salz Library Curl v1.6.3
 *
 * Created by Mochammad Faisal
 * Created at 2020-12-10 11:46:25
 * Updated at 2023-07-05 13:53:54
 *
 */



class Salz_curl {
    protected $host;
    protected $method;
    protected $headers;
    protected $body;
    protected $encode_json = false;
    protected $is_multipart_body = false;
    protected $is_get_http_code = false;
    protected $is_disable_ssl_verif = false;
    protected $is_header_response_enable = false;
    protected $res_http_code;
    protected $res_headers = [];

    // get set
    public function get_Host() {
        return $this->host;
    }
    public function set_Host($host) {
        $this->host = $host;
    }

    public function get_Method() {
        return $this->method;
    }
    public function set_Method($method) {
        $this->method = strtoupper($method);
    }

    public function get_Header() {
        return $this->headers;
    }
    public function set_Header($header) {
        $this->headers = $header;
    }

    public function get_Body() {
        return $this->body;
    }
    public function set_Body($body) {
        $this->body = $body;
    }

    public function get_encode_json() {
        return $this->encode_json;
    }

    public function set_encode_json($is_set) {
        $this->encode_json = $is_set;
    }

    public function get_is_multipart_body() {
        return $this->is_multipart_body;
    }

    public function set_is_multipart_body($is_set) {
        $this->is_multipart_body = $is_set;
    }

    public function get_res_http_code() {
        return $this->res_http_code;
    }

    public function set_res_http_code($code) {
        $this->res_http_code = $code;
    }

    public function get_is_http_code() {
        return $this->is_get_http_code;
    }

    public function set_is_http_code($is_set) {
        $this->is_get_http_code = $is_set;
    }

    public function set_is_disable_ssl_verif($is_set) {
        $this->is_disable_ssl_verif = $is_set;
    }

    public function set_is_header_response_enable($is_set) {
        $this->is_header_response_enable = $is_set;
    }

    public function get_is_header_response_enable() {
        return $this->is_header_response_enable;
    }

    public function get_response_header() {
        return $this->res_headers;
    }

    public function build_curl() {
        $curl = curl_init();
        $stamp = time();
        $headers_list = array();
        $body = array();
        $default_config = false;
        $http_status = null;

        if ($default_config) {
            $headers_list = array(
                "Content-Type: application/json",
                "Accept: application/json",
            );
        }

        if (!empty($this->get_Header())) {
            foreach ($this->get_Header() as $key => $value) {
                if (strpos($key, 'timestamp') !== false) {
                    $value = $stamp;
                }

                $headers_list[] = $key . ': ' . trim($value);
            }
        } else {
            $headers_list = array(
                "Content-Type: application/json",
                "Accept: application/json",
                "X-timestamp: " . $stamp,
            );
        }

        if (!empty($this->body)) {
            $body = $this->body;

            if ($this->get_encode_json()) {
                $body = json_encode($body);
            }

            if ($this->get_is_multipart_body()) {
                $body = http_build_query($body);
            }
        }

        $headers = $headers_list;
        $options = array(
            CURLOPT_URL => $this->host,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $body,
        );

        if ($this->is_disable_ssl_verif) {
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            $options[CURLOPT_SSL_VERIFYPEER] = false;
        }

        if ($this->get_is_header_response_enable()) {
            // reff : https://github.com/stripe/stripe-php/issues/220
            $options[CURLOPT_HEADERFUNCTION] = array($this, 'customHeaderCallback');
        }

        curl_setopt_array($curl, $options);


        // print_r($headers); exit;
        // print_r($body); exit;
        // die($body);
        // print_r($options);exit;

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($this->get_is_http_code()) {
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $this->set_res_http_code($http_status);
        }

        curl_close($curl);


        // print_r($curl);exit();
        // print_r($response);exit();
        // print_r($body);exit();
        // echo $response; exit;
        // echo $err; exit;

        if ($err) {
            return json_decode($err, true);
        } else {
            return json_decode($response, true);
        }
    }

    public function customHeaderCallback($curl, $header_line) {
        // reff : https://stackoverflow.com/a/51661183/10351006
        // https://github.com/stripe/stripe-php/issues/220
        if (strpos($header_line, ":") === false) {
            return strlen($header_line);
        }
        list($key, $value) = explode(":", trim($header_line), 2);
        $this->res_headers[trim($key)] = trim($value);
        return strlen($header_line);
    }

    // end of class
}
