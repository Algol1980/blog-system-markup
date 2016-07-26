<?php

/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 27.07.2016
 * Time: 0:48
 */
class Pagination
{
    public $buttons = [];
    public $totalPosts;
    public $currentPage;
    public $totalPages;
    public $path;

    public function __construct($totalPosts, $currentPage, $path)
    {
        if (!$currentPage) {
            return false;
        }
        $this->totalPosts = $totalPosts;
        $this->currentPage = $currentPage;
        $this->totalPages = ceil($this->totalPosts/ PER_PAGE);
        $this->path = $path;

        if ( $this->totalPages == 1) {
            return false;
        }

        if ($this->currentPage > $this->totalPages) {
            $this->currentPage = $this->totalPages;
        }

        $this->buttons[] =  $this->makeButton($this->currentPage - 1,  $this->currentPage > 1, '«');
        $i = 1;
        while ($i <=  $this->totalPages) {
            $isActive = $this->currentPage != $i;
            $this->buttons[] =  $this->makeButton($i, $isActive);
            $i++;
        }
        $this->buttons[] =   $this->makeButton($this->currentPage + 1, $this->currentPage < $this->totalPages, '»');

        return $this->buttons;
    }


    public function makeButton($page, $isActive = true, $text = null)
    {
        $btn = [];
        $btn['page'] = $page;
        $btn['isActive'] = $isActive;
        $btn['text'] = is_null($text) ? $page : $text;
        return $btn;
    }
}