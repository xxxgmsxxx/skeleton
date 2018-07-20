<?php
namespace core;

interface Db
{
    public function exec($query, $params = []);
    public function fetch();
    public function fetchAll();
    public function fetchCol();
    public function fetchPairs();
    public function rows();
    public function lastError();
}