<?php


class View
{
    public function generate($contentView, $templateView, $data = null)
    {
        include 'app/views/' . $templateView;
    }
}