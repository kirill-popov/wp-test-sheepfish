<?php

namespace SheepFish;

use SheepFish\classes\SheepFishTheme;

if (!defined('ABSPATH')) {
    return;
}

function load_files(array $files=[]): void
{
    if (!empty($files)) {
        foreach ($files as $file) {
            require_once($file);
        }
    }
}

$path = get_theme_file_path() . '/includes/classes/*.php';
$files = glob($path);
load_files($files);


$theme = new SheepFishTheme();
$theme->add_style('parent-style', get_template_directory_uri() . '/style.css')
->add_style('child-style', get_stylesheet_uri(), array('parent-style'))
->add_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css')

->add_script('sheepfish-script', get_stylesheet_directory_uri() . '/js/script.js', array('jquery'))

->add_user_role('Customer');

$theme->run();

add_action( 'wp_ajax_customer_form_submit', 'sheepfish_customer_form_submit' );
add_action( 'wp_ajax_nopriv_customer_form_submit', 'sheepfish_customer_form_submit' );

function sheepfish_customer_form_submit() {

}