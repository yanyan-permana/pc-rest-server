<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Jurusan extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('jurusan_model');
    }

    /**
     * Method index_get digunakan untuk mengambil data jurusan
     */
    public function index_get()
    {
        $id = $this->get('id');

        // Jika id kosong
        if ($id == null) {
            $jurusan = $this->jurusan_model->get_data_jurusan();
        } else {
            $jurusan = $this->jurusan_model->get_data_jurusan($id);
        }

        // Jika data jurusannya ada 
        if ($jurusan) {
            $this->response([
                'status' => true,
                'message' => 'Data jurusan',
                'data' => $jurusan
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
    }

    /**
     * Method index_post digunakan untuk menambahkan data jurusan
     */
    public function index_post()
    {
        $data = [
            'nama' => $this->post('nama'),
        ];

        // Validasi inputan
        $this->form_validation->set_rules('nama', 'Nama', 'required|is_unique[jurusan.nama]');

        // Mengeset pesan error dari validasi
        $this->form_validation->set_message('required', '{field} harus diisi!.');
        $this->form_validation->set_message('is_unique', '{field} sudah ada!.');

        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'error' => true,
                'nama' => form_error('nama'),
            ], 400);
        } else {
            if ($this->jurusan_model->insert_data_jurusan($data) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data jurusan berhasil tersimpan',
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal menambahkan data jurusan',
                ], 404);
            }
        }
    }

    /**
     * Method index_put digunakan untuk mengubah data jurusan
     */
    public function index_put()
    {
        $id = $this->put('id');

        $data = [
            'nama' => $this->put('nama'),
        ];

        // Cek jika id kosong
        if ($id == null) {
            $this->response([
                'status' => false,
                'message' => 'Id harus diisi',
            ], 400);
        } else {
            /**
             * Ubah set datanya
             * biar yang dicek saat validasi inputan itu method put bukan post
             */
            $this->form_validation->set_data($this->put());

            // cek jika id tidak ada
            $jurusan = $this->jurusan_model->get_data_jurusan($id);
            if ($jurusan) {
                // Cek jika nama nya sama
                if ($this->put('nama') == $jurusan['nama']) {
                    // Jika sama

                    // Validasi inputan
                    $this->form_validation->set_rules('nama', 'Nama', 'required');

                    // Merubah pesan error dari validasi
                    $this->form_validation->set_message('required', '{field} harus diisi!.');
                } else {
                    // Jika tidak

                    // Validasi inputan
                    $this->form_validation->set_rules('nama', 'Nama', 'required|is_unique[jurusan.nama]');

                    // Merubah pesan error dari validasi
                    $this->form_validation->set_message('required', '{field} harus diisi!.');
                    $this->form_validation->set_message('is_unique', '{field} sudah ada!.');
                }

                if (!$this->form_validation->run()) {
                    $this->response([
                        'error' => true,
                        'nama' => form_error('nama'),
                    ], 400);
                } else {
                    // if ($this->jurusan_model->update_data_jurusan($data, $id) > 0) {
                    $this->jurusan_model->update_data_jurusan($data, $id);
                    $this->response([
                        'status' => true,
                        'message' => 'Data jurusan berhasil terupdate',
                    ], 200);
                    // }
                }
            } else {
                $this->response([
                    'error' => true,
                    'message' => 'Id tidak ditemukan',
                ], 400);
            }
        }
    }

    /**
     * Method index_delete digunakan untuk menghapus data jurusan
     */
    public function index_delete()
    {
        $id = $this->delete('id');

        // Jika id kosong
        if ($id == null) {
            $this->response([
                'status' => false,
                'message' => 'Id harus dikirim',
            ], 404);
        } else {
            // Cek jika id tidak ada ditable jurusan
            if ($this->jurusan_model->delete_data_jurusan($id) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data jurusan berhasil terhapus',
                    'id' => $id,
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Id tidak ditemukan',
                ], 404);
            }
        }
    }
}
