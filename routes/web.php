<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Siswa
$router->get('api/siswa', 'SiswaController@tampil');
$router->get('api/siswa/{id}', 'SiswaController@detail');
$router->post('api/siswa', 'SiswaController@tambah');
$router->put('api/siswa/{id}', 'SiswaController@ubah');
$router->delete('api/siswa/{id}', 'SiswaController@hapus');

// Kelompokbelajar
$router->get('api/kelompokbelajar', 'KelompokbelajarController@tampil');
$router->get('api/kelompokbelajar/{id}', 'KelompokbelajarController@detail');
$router->post('api/kelompokbelajar', 'KelompokbelajarController@tambah');
$router->put('api/kelompokbelajar/{id}', 'KelompokbelajarController@ubah');
$router->delete('api/kelompokbelajar/{id}', 'KelompokbelajarController@hapus');
