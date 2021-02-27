<?php

declare(strict_types = 1);
namespace MSRENA;

class JSONPlaceHolder
{
    const API_URL           = 'https://jsonplaceholder.typicode.com/';
    private $url            = '';
    private $data           = [];
    private $method         = 'GET';
    private $endpoint       = [];
    private $totalCount     = 0;
    private $link           = [
        'first' => '',
        'prev' => '',
        'next' => '',
        'last' => ''
    ];

    public function GetDataBase()
    {
        $this->method = 'GET';
        $this->url = self::API_URL.'db';
        return $this->Call();
    }

    public function Posts(int $_id = null) : JSONPlaceHolder
    {
        $this->url      = self::API_URL.'posts'.((int)$_id > 0 ? '/'.$_id : '');
        $this->method   = 'GET';
        $this->data     = [];
        return $this;
    }

    public function Comments(int $_id = null) : JSONPlaceHolder
    {
        $this->url      = self::API_URL.'comments'.((int)$_id > 0 ? '/'.$_id : '');
        $this->method   = 'GET';
        $this->data     = [];
        return $this;
    }

    public function Albums(int $_id = null) : JSONPlaceHolder
    {
        $this->url      = self::API_URL.'albums'.((int)$_id > 0 ? '/'.$_id : '');
        $this->method   = 'GET';
        $this->data     = [];
        return $this;
    }

    public function Photos(int $_id = null) : JSONPlaceHolder
    {
        $this->url      = self::API_URL.'photos'.((int)$_id > 0 ? '/'.$_id : '');
        $this->method   = 'GET';
        $this->data     = [];
        return $this;
    }

    public function Todos(int $_id = null) : JSONPlaceHolder
    {
        $this->url      = self::API_URL.'todos'.((int)$_id > 0 ? '/'.$_id : '');
        $this->method   = 'GET';
        $this->data     = [];
        return $this;
    }

    public function Users(int $_id = null) : JSONPlaceHolder
    {
        $this->url      = self::API_URL.'users'.((int)$_id > 0 ? '/'.$_id : '');
        $this->method   = 'GET';
        $this->data     = [];
        return $this;
    }

    public function GetById(int $_id = null) : JSONPlaceHolder
    {
        $this->url  .=  '/'.$_id;
        return $this;
    }

    public function ListById(int $_id = null) : JSONPlaceHolder
    {
        if($_id > 0)
        {
            $this->data['id'][] = $_id;
        }
        return $this;
    }

    public function Page(int $_page_number = null) : JSONPlaceHolder
    {
        if($_page_number > 0)
        {
            $this->data['_page'] = $_page_number;
        }
        return $this;
    }

    public function Limit(int $_limit = 10) : JSONPlaceHolder
    {
        if($_limit > 0)
        {
            $this->data['_limit'] = $_limit;
        }
        return $this;
    }

    public function SortBy(string $_sorted_by = '') : JSONPlaceHolder
    {
        if(strlen($_sorted_by) > 0)
        {
            $this->data['_sort'] = $_sorted_by;
        }
        return $this;
    }

    public function OrderBy(string $_order_by = 'asc') : JSONPlaceHolder
    {
        if(strlen($_order_by) > 0)
        {
            $this->data['_order'] = $_order_by;
        }
        return $this;
    }

    public function Embed(string $_embeded_with = '') : JSONPlaceHolder
    {
        if(strlen($_embeded_with) > 0)
        {
            $this->data['_embed'] = $_embeded_with;
        }
        return $this;
    }

    public function Expand(string $_expand_with = '') : JSONPlaceHolder
    {
        if(strlen($_expand_with) > 0)
        {
            $this->data['_expand'] = $_expand_with;
        }
        return $this;
    }

    public function NestedPosts() : JSONPlaceHolder
    {
        $this->url .= '/posts';
        return $this;
    }

    public function NestedComments() : JSONPlaceHolder
    {
        $this->url .= '/comments';
        return $this;
    }

    public function NestedAlbums() : JSONPlaceHolder
    {
        $this->url .= '/albums';
        return $this;
    }

    public function NestedPhotos() : JSONPlaceHolder
    {
        $this->url .= '/photos';
        return $this;
    }

    public function NestedTodos() : JSONPlaceHolder
    {
        $this->url .= '/todos';
        return $this;
    }

    public function Start(int $_start = 0) : JSONPlaceHolder
    {
        if($_start > -1)
        {
            $this->data['_start'] = $_start;
        }
        return $this;
    }

    public function End(int $_end = 0) : JSONPlaceHolder
    {
        if($_end > 0)
        {
            $this->data['_end'] = $_end;
        }
        return $this;
    }

    public function ExcludeValue(string $_field,string $_value) : JSONPlaceHolder
    {
        if(strlen($_field) > 0 && strlen($_value) > 0)
        {
            $this->data["{$_field}_ne"] = $_value;
        }
        return $this;
    }

    public function LikeValue(string $_field,string $_value) : JSONPlaceHolder
    {
        if(strlen($_field) > 0 && strlen($_value) > 0)
        {
            $this->data["{$_field}_like"] = $_value;
        }
        return $this;
    }

    public function FullSeach(string $_value = '') : JSONPlaceHolder
    {
        if(strlen($_field) > 0 && strlen($_value) > 0)
        {
            $this->data["q"] = $_value;
        }
        return $this;
    }

    public function SetQueryParameters(array $_data = []) : JSONPlaceHolder
    {
        $this->data = $_data;
        return $this;
    }

    public function Call()
    {
        if(strlen($this->url) > 40){
            $Result = [
                    'error'     => 0,
                    'message'   => "Request OK to {$this->url}",
                    'log'       => [
                        'method'     => $this->method,
                        'url'        => $this->url,
                        'parameters' => $this->data
                    ],
                    'data'      => $this->_CallAPI($this->method,$this->url,$this->data)
            ];
            $this->_Refresh();
            return $Result;
        }else {
            return
                [
                    'error'     => 1,
                    'message'   => "URL Not Defined Correctly {$this->url}",
                    'data'      => []
                ];
        }
    }

    private function _Refresh()
    {
        $this->url      = '';
        $this->method   = 'GET';
        $this->data     = [];
        $this->data     = [];
    }

    private function _CallAPI($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));//echo $url;die();
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        $result = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $this->_ValidateResponseHeaders($this->_GetHeaders(substr($result, 0, $header_size)));
        $body = substr($result, $header_size);
        curl_close($curl);
        return [['link' => $this->link,'count' => $this->totalCount],'result' => $body];
    }

    private function _ValidateResponseHeaders(array $_headers)
    {
        if(array_key_exists('Link',$_headers))
        {
            $Link = explode(',',$_headers['Link']);
            $this->link['first'] = str_replace('<','',explode('>;',$Link[0])[0]);
            $this->link['prev' ] = str_replace('<','',explode('>;',$Link[1])[0]);
            $this->link['next' ] = str_replace('<','',explode('>;',$Link[2])[0]);
            $this->link['last' ] = str_replace('<','',explode('>;',$Link[3])[0]);
        }
        if(array_key_exists('X-Total-Count',$_headers))
        {
            $this->totalCount = $_headers['X-Total-Count'];
        }
    }

    private function _GetHeaders(string $respHeaders): array
    {
        $headers = array();
        $headerText = substr($respHeaders, 0, strpos($respHeaders, "\r\n\r\n"));
        foreach (explode("\r\n", $headerText) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list ($key, $value) = explode(': ', $line);

                $headers[$key] = $value;
            }
        }
        return $headers;
    }
}
?>