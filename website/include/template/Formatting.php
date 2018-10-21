<?php
class Formatting {
    private function __construct() {}
    
    static function pageLinks($pagination) {
        $output = '';
        for ($i = 0; $i < $pagination->numItems(); $i++) {
            if ($pagination->type($i) == Pagination::PAGE) {
                $text = $pagination->number($i);
            } elseif ($pagination->type($i) == Pagination::NEXT) {
                $text = _('Next');
            } elseif ($pagination->type($i) == Pagination::PREV) {
                $text = _('Previous');
            }
        
            if ($pagination->link($i) === null) {
                $output .= '<span class="nav-link-sel">'
                           . $text
                           . '</span> ';
            } else {
                $output .= '<a href="" class="nav-link">'
                           . $text
                           . '</a> ';
            }
        }
        return $output;
    }
}