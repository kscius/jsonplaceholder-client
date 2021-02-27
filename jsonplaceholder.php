<?php

declare(strict_types = 1);
namespace MSRENA;

class JSONPlaceHolder 
{
    const API_URL       = 'https://jsonplaceholder.typicode.com/';
    private $url        = '';
    private $data       = [];
    private $method     = 'GET';
    private $endpoint   = [];
    private $headers    = [];
    private $link       = [
        'first' => '',
        'prev' => '',
        'next' => '',
        'last' => ''
    ];

    public function Posts(int $_id = null) : JSONPlaceHolder
    {
        $this->url      = self::API_URL.'posts'.((int)$_id > 0 ? '/'.$_id : '');
        $this->method   = 'GET';
        $this->data     = [];
        return $this;
    }

    public function GetById(int $_id = null)
    {
        $this->url  .=  '/'.$_id;
        return $this;
    }

    public function ListById(int $_id = null)
    {
        if($_id > 0)
        {
            $this->data['id'][] = $_id;
        }
        return $this;
    }

    public function Page(int $_page_number = null)
    {
        if($_page_number > 0)
        {
            $this->data['_page'] = $_page_number;
        }
        return $this;
    }

    public function Limit(int $_limit = 10)
    {
        if($_limit > 0)
        {
            $this->data['_limit'] = $_limit;
        }
        return $this;
    }

    public function SortBy(string $_sorted_by = '')
    {
        if(strlen($_sorted_by) > 0)
        {
            $this->data['_sort'] = $_sorted_by;
        }
        return $this;
    }

    public function OrderBy(string $_order_by = 'asc')
    {
        if(strlen($_order_by) > 0)
        {
            $this->data['_order'] = $_order_by;
        }
        return $this;
    }

    public function Embed(string $_embeded_with = '')
    {
        if(strlen($_embeded_with) > 0)
        {
            $this->data['_embed'] = $_embeded_with;
        }
        return $this;
    }

    public function SetQueryParameters(array $_data = [])
    {
        $this->data = $_data;
        return $this;
    }
    
    public function Call()
    {
        if(strlen($this->url) > 40){
            $Result = $this->_CallAPI($this->method,$this->url,$this->data);
            $this->_Refresh();
            return $Result;
        }else {
            return json_encode(['error' => 1, 'message' => "URL Not Defined Correctly {$this->url}"]);
        }
    }

    private function _Refresh()
    {
        $this->url      = '';
        $this->method   = 'GET';
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
        $result = curl_exec($curl);
        $this->_ValidateResponseHeaders($this->_GetHeaders(substr($result, 0, curl_getinfo($curl, CURLINFO_HEADER_SIZE))));
        curl_close($curl);
        return $result; 
    }

    private function _ValidateResponseHeaders(array $_headers)
    {
        $Link = explode(',',$_headers['Link']);
        $this->link['first'] = str_replace('<','',explode('>;',$Link[0])[0]);
        $this->link['prev' ] = str_replace('<','',explode('>;',$Link[1])[0]);
        $this->link['next' ] = str_replace('<','',explode('>;',$Link[2])[0]);
        $this->link['last' ] = str_replace('<','',explode('>;',$Link[3])[0]);
    }

    private function _GetHeaders($respHeaders)
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

    public function _Posts(int $_id = null,bool $_diplay_comments = false, array $_data = [])
    {
        return  (JSONPlaceHolder::getInstance())->_CallAPI('GET',self::API_URL.'posts'.((int)$_id > 0 ? '/'.$_id : '').($_diplay_comments? '/comments' : ''),$_data);
    }

    public function Albums(int $_id = null,bool $_diplay_photos= false, array $_data = [])
    {
        return  (JSONPlaceHolder::getInstance())->_CallAPI('GET',self::API_URL.'albums'.((int)$_id > 0 ? '/'.$_id : '').($_diplay_photos? '/photos' : ''), $_data);
    }

    public function Comments(int $_id = null, array $_data = [])
    {
        return  (JSONPlaceHolder::getInstance())->_CallAPI('GET',self::API_URL.'comments'.((int)$_id > 0 ? '/'.$_id : ''),$data);
    }

    public function Photos(int $_id = null, array $_data = [])
    {
        return  (JSONPlaceHolder::getInstance())->_CallAPI('GET',self::API_URL.'photos',$data);
    }

    public function Todos(int $_id = null, array $_data = [])
    {
        return  (JSONPlaceHolder::getInstance())->_CallAPI('GET',self::API_URL.'todos',$data);
    }

    public function Users(int $_id = null,bool $_diplay_albums = false,bool $_diplay_todos = false,bool $_diplay_posts = false, array $_data = [])
    {
        return  (JSONPlaceHolder::getInstance())->_CallAPI('GET',self::API_URL.'users',$data);
    }

}

?>