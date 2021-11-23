<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
  // fungsi GET
  public function tampil()
  {
    $siswa = DB::table('siswa')
      ->join('kelompok_belajar', 'kelompok_belajar.id_rombel', '=', 'siswa.id_rombel')
      ->get();

    foreach ($siswa as $key => $item) {
      $siswa[$key] = $item;
      $siswa[$key]->_links = [
        [
          'rel'   => 'Detail siswa',
          'href'  => '/api/siswa/' . $item->id_siswa,
          'type'  => 'GET'
        ],
        [
          'rel'   => 'Detail kelompokbelajar',
          'href'  => '/api/kelompokbelajar/' . $item->id_rombel,
          'type'  => 'GET'
        ]
      ];
    }

    $data = [
      'code'    => 200,
      'message' => 'Data semua siswa berhasil diambil!',
      'data'    => $siswa,
    ];

    return response()->json($data, 200);
  }

  public function detail($id)
  {
    // GET Detail
    $query = DB::table('siswa')->where('id_siswa', $id);

    if ($query->count() < 1) {
      $data = [
        'code'    => 404,
        'message' => 'Data tidak ditemukan!',
      ];
      return response()->json($data, 404);
    }

    $siswa = $query->join('kelompok_belajar', 'kelompok_belajar.id_rombel', '=', 'siswa.id_rombel')
      ->first();

    $siswa->_links = [
      [
        'href'  => '/api/kelompokbelajar/' . $siswa->id_rombel,
        'rel'   => 'Detail kelompokbelajar',
        'type'  => 'GET'
      ]
    ];

    $data = [
      'code'    => 200,
      'message' => 'Detail siswa berhasil diambil!',
      'data'    => $siswa,
    ];

    return response()->json($data, 200);
  }
  // fungsi PUT
  public function tambah(Request $request)
  {

    $validation = Validator::make($request->all(), [
      'nama' => 'required|unique:siswa,nama',
      'nis' => 'required',
      'alamat' => 'required',
      'id_rombel' => 'required|exists:kelompok_belajar,id_rombel',
    ], $this->_error_messages());

    if ($validation->fails()) {
      return response()->json([
        'code' => 422,
        'message' => 'Permintaan tidak valid!',
        'errors' => $validation->errors()
      ], 422);
    }

    $data_baru = [
      'nama' => $request->nama,
      'nis' => $request->nis,
      'alamat' => $request->alamat ? $request->alamat : 0,
      'id_rombel' => $request->id_rombel,
    ];

    DB::table('siswa')->insert($data_baru);

    $data = [
      'code'    => 201,
      'message' => 'Data siswa berhasil ditambah!',
    ];

    return response()->json($data, 201);
  }
  // fungsi PUT
  public function ubah(Request $request, $id)
  {

    $validation = Validator::make(
      $request->all(),
      [
        'nama' => 'required|unique:siswa,nama,' . $id . ',id_siswa',
        'nis' => 'required',
        'alamat' => 'required',
        'id_rombel' => 'required|exists:kelompok_belajar,id_rombel',
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

    $data_ubah = [
      'nama' => $request->nama,
      'nis' => $request->nis,
      'alamat' => $request->alamat,
      'id_rombel' => $request->id_rombel,
    ];

    DB::table('siswa')->where('id_siswa', $id)->update($data_ubah);

    $data = [
      'code'    => 200,
      'message' => 'Data siswa berhasil diperbarui!',
    ];

    return response()->json($data, 200);
  }
  // Fungsi DEL
  public function hapus($id)
  {

    // cek data
    $query = DB::table('siswa')->where('id_siswa', $id);

    if ($query->count() < 1) {
      $data = [
        'code'    => 404,
        'message' => 'Data siswa tidak ditemukan!',
      ];
      return response()->json($data, 404);
    }

    $query->delete();

    $data = [
      'code'    => 200,
      'message' => 'Data siswa berhasil dihapus!',
    ];

    return response()->json($data, 200);
  }

  private function _error_messages()
  {
    return  [
      'nama.required' => 'Nama siswa diperlukan.',
      'nama.unique' => 'Nama siswa sudah ada.',
      'nis.required' => 'nis siswa diperlukan.',
      'alamat.required' => 'alamat siswa diperlukan.',
      'id_rombel.required' => 'ID rombel diperlukan.',
      'id_rombel.exists' => 'ID rombel tidak ada.',
    ];
  }
}
