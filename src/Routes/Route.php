<?php

namespace Tualo\Office\GroupEditor\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;

class Route extends \Tualo\Office\Basic\RouteWrapper
{
    public static function register()
    {
        BasicRoute::add('/groupeditor/read', function ($matches) {


            App::contenttype('application/json');
            App::result('success', false);
            try {
                if (isset($_SESSION['tualoapplication']) && isset($_SESSION['tualoapplication']['typ']) && ($_SESSION['tualoapplication']['typ'] == 'master')) {
                    $list =  App::get('session')->db->direct('select name id,name,aktiv from macc_groups', []);
                    App::result('data', $list);
                    App::result('total', count($list));
                    App::result('success', true);
                }
            } catch (\Exception $e) {
                App::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);


        BasicRoute::add('/groupeditor/update', function ($matches) {
            App::contenttype('application/json');
            try {
                if (isset($_SESSION['tualoapplication']) && isset($_SESSION['tualoapplication']['typ']) && ($_SESSION['tualoapplication']['typ'] == 'master')) {
                    $input = json_decode(file_get_contents('php://input'), true);
                    if (is_null($input)) throw new \Exception("Error Processing Request", 1);
                    if ($input !== array_values($input)) {
                        $input = [$input];
                    }
                    foreach ($input as $row) {
                        foreach ($row as $key => $value) {
                            if (in_array($key, ['aktiv', 'name'])) {
                                $sql = 'update macc_groups set `' . $key . '`={value} where name={id}';
                                if (($key == 'aktiv') && ($value == false)) {
                                    $row[$key] = 0;
                                }
                                App::get('session')->db->direct($sql, array('id' => $row['id'], 'value' => $row[$key]));
                            }
                        }
                    }

                    App::result('success', true);
                }
            } catch (\Exception $e) {
                App::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);

        BasicRoute::add('/groupeditor/create', function ($matches) {
            App::contenttype('application/json');
            try {
                if (isset($_SESSION['tualoapplication']) && isset($_SESSION['tualoapplication']['typ']) && ($_SESSION['tualoapplication']['typ'] == 'master')) {
                    $input = json_decode(file_get_contents('php://input'), true);
                    if (is_null($input)) throw new \Exception("Error Processing Request", 1);
                    if ($input !== array_values($input)) {
                        $input = [$input];
                    }
                    foreach ($input as  $row) {
                        $sql = 'insert ignore into macc_groups (name,aktiv) values ({name},{aktiv})';
                        App::get('session')->db->direct($sql, $row);
                    }
                    App::result('success', true);
                }
            } catch (\Exception $e) {
                App::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);


        BasicRoute::add('/groupeditor/delete', function ($matches) {
            App::contenttype('application/json');
            try {
                if (isset($_SESSION['tualoapplication']) && isset($_SESSION['tualoapplication']['typ']) && ($_SESSION['tualoapplication']['typ'] == 'master')) {
                    if (isset($_REQUEST['id'])) {
                        App::get('session')->db->direct(
                            'delete from macc_groups where name={id}',
                            [
                                'id' => $_REQUEST['id']
                            ]
                        );
                        App::result('success', true);
                    }
                }
            } catch (\Exception $e) {
                App::result('msg', $e->getMessage());
            }
        }, ['get', 'post'], true);
    }
}
