<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validator;

class KelompokbelajarController extends Controller
{

  public function tampil()
  {
    $kelompokbelajar = DB::table('kelompok_belajar')->get();

    foreach ($kelompokbelajar as $key => $item) {
      $kelompokbelajar[$key] = $item;
      $kelompokbelajar[$key]->_links = [
        [
          'rel'   => 'Detail kelompokbelajar',
          'href'  => '/api/kelompokbelajar/' . $item->id_rombel,
          'type'  => 'GET'
        ]
      ];
    }

    $data = [
      'code'    => 200,
      'message' => 'Data semua kelompokbelajar berhasil diambil!',
      'data'    => $kelompokbelajar,
    ];

    return response()->json($data, 200);
  }

  public function detail($id)
  {
    // cek data
    $query = DB::table('kelompok_belajar')->where('id_rombel', $id);

    if ($query->count() < 1) {
      $data = [
        'code'    => 404,
        'message' => 'Data tidak ditemukan!',
      ];
      return response()->json($data, 404);
    }

    $kelompokbelajar = $query->first();

    $data = [
      'code'    => 200,
      'message' => 'Detail kelompokbelajar berhasil diambil!',
      'data'    => $kelompokbelajar,
    ];

    return response()->json($data, 200);
  }
  // fungsi POS
  public function tambah(Request $request)
  {

    $validation = Validator::make($request->all(), [
      'kelas' => 'required|unique:kelompok_belajar,kelas',
    ], $this->_error_messages());

    if ($validation->fails()) {
      return response()->json([
        'code' => 422,
        'message' => 'Permintaan tidak valid!',
        'errors' => $validation->errors()
      ], 422);
    }

    DB::table('kelompok_belajar')->insert(['kelas' => $request->kelas]);

    $data = [
      'code'    => 201,
      'message' => 'Data kelompokbelajar berhasil ditambah!',
    ];

    return response()->json($data, 201);
  }
  // fungsi PUT
  public function ubah(Request $request, $id)
  {

    $validation = Validator::make(
      $request->all(),
      [
        'kelas' => 'required|unique:kelompok_belajar,kelas,' . $id . ',id_rombel',
      ],
      $this->_error_messages()
    );

    if ($validation->fails()) {
      return response()->json([
        'code' => 422,
        'message' => 'Permintaan tidak valid!',
        'errors' => $validation->errors()
      ], 422);
    }

    DB::table('kelompok_belajar')->where('id_rombel', $id)
      ->update(['kelas' => $request->kelas]);

    $data = [
      'code'    => 200,
      'message' => 'Data kelompokbelajar berhasil diperbarui!',
    ];

    return response()->json($data, 200);
  }

  public function hapus($id)
  {
    // cek data
    $query = DB::table('kelompok_belajar')->where('id_rombel', $id);

    if ($query->count() < 1) {
      $data = [
        'code'    => 404,
        'message' => 'Data kelompokbelajar tidak ditemukan!',
      ];
      return response()->json($data, 404);
    }

    $query->delete();

    $data = [
      'code'    => 200,
      'message' => 'Data kelompokbelajar berhasil dihapus!',
    ];

    return response()->json($data, 200);
  }

  private function _error_messages()
  {
    return  [
      'kelas.required' => 'kelas diperlukan.',
      'kelas.unique' => 'kelas sudah ada.',
    ];
  }
}
