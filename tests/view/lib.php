<?php

error_reporting( E_ERROR );

function get_state_class( $result )
{

    $prefix = 'alert alert-';

    switch( $result )
    {

        case '0':
            return $prefix.'success';

        case '4':
            return $prefix.'danger';

        default:
            return $prefix.'warning';

    }

}

function get_images( $basedir, $scenario, $feature )
{
    $res = array();
    foreach( glob( get_behat_result_dir().'/'.$scenario->file.'/'.$scenario->title.'/*.png' ) AS $img )
    {
        $res[] = str_replace( get_behat_result_dir().'/', '', $img  );
    }
    sort($res);
    return $res;
}

function get_behat_result_dir()
{

    $mageDir = isset($_SERVER['SCRIPT_FILENAME']) ? dirname( dirname( dirname($_SERVER['SCRIPT_FILENAME']) ) ) : dirname(getcwd());

    if( $_REQUEST['time'] )
    {
        return   $mageDir.'/var/tests/'.$_REQUEST['time'];
    }

    if( !is_dir( $mageDir.'/var/tests' ) )
    {
        die('var/tests is missing?');
    }

    return $behat_result_dir = max( glob($mageDir.'/var/tests/*') );
}
function get_image_url($img, $forStaticReport)
{
    $file = $img . '.png';
    if ($forStaticReport) {
        return $file;
    } else {
        $time = isset($_REQUEST['time']) ? $_REQUEST['time'] : '';
        return 'images.php?file=' . urlencode($file) . '&amp;time=' . $time;
    }
}