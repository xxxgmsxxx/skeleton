<?php
namespace core\dto;

class jsFile
{
    public $fileName = '';
    public $includeVersion = false;
    public $body = '';

    public function __construct($fileName, $includeVersion = false)
    {
        $this->fileName = $fileName;
        $this->includeVersion = $includeVersion;
    }

    public function getLink()
    {
        if (!$this->includeVersion)
        {
            return $this->fileName;
        }

        $this->fileName = trim($this->fileName, '?');
        if (strpos($this->fileName, '?') !== false)
        {
            return $this->fileName.'&v='.time();
        }
        else
        {
            return $this->fileName.'?v='.time();
        }
    }

    public function hasBody()
    {
        return (strlen($this->body) > 0);
    }

    public function getBody()
    {
        return $this->body;
    }
}