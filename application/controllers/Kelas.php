<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Kelas extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('kelas_model');
    }

    /**
     * Method index_get digunakan untuk mengambil data kelas
     */
    public function index_get()
    {
        $id = $this->get('id');

        // Jika id kosong
        if ($id == null) {
            $kelas = $this->kelas_model->get_data_kelas();
        } else {
            $kelas = $this->kelas_model->get_data_kelas($id);
        }

        // Jika data kelasnya ada 
        if ($kelas) {
            $this->response([
                'status' => true,
                'message' => 'Data kelas',
                'data' => $kelas
            ]);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    /**
     * Method index_post digunakan untuk menambahkan data kelas
     */
    public function index_post()
    {
        $data = [
            'nama' => $this->post('nama'),
        ];

        // Validasi inputan
        $this->form_validation->set_rules('nama', 'Nama', 'required|is_unique[kelas.nama]');

        // Mengeset pesan error dari validasi
        $this->form_validation->set_message('required', '{field} harus diisi!.');
        $this->form_validation->set_message('is_unique', '{field} sudah ada!.');

        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => false,
                'nama' => form_error('nama'),
            ]);
        } else {
            if ($this->kelas_model->insert_data_kelas($data) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data kelas berhasil tersimpan',
                ]);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal menambahkan data kelas',
                ]);
            }
        }
    }

    /**
     * Method index_put digunakan untuk mengubah data kelas
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
            ]);
        } else {
            /**
             * Ubah set datanya
             * biar yang dicek saat validasi inputan itu method put bukan post
             */
            $this->form_validation->set_data($this->put());

            // cek jika id tidak ada
            $kelas = $this->kelas_model->get_data_kelas($id);
            if ($kelas) {
                // Cek jika nama nya sama
                if ($this->put('nama') == $kelas['nama']) {
                    // Jika sama

                    // Validasi inputan
                    $this->form_validation->set_rules('nama', 'Nama', 'required');

                    // Merubah pesan error dari validasi
                    $this->form_validation->set_message('required', '{field} harus diisi!.');
                } else {
                    // Jika tidak

                    // Validasi inputan
                    $this->form_validation->set_rules('nama', 'Nama', 'required|is_unique[kelas.nama]');

                    // Merubah pesan error dari validasi
                    $this->form_validation->set_message('required', '{field} harus diisi!.');
                    $this->form_validation->set_message('is_unique', '{field} sudah ada!.');
                }

                if (!$this->form_validation->run()) {
                    $this->response([
                        'status' => false,
                        'nama' => form_error('nama'),
                    ]);
                } else {
                    // if ($this->kelas_model->update_data_kelas($data, $id) > 0) {
                    $this->kelas_model->update_data_kelas($data, $id);
                    $this->response([
                        'status' => true,
                        'message' => 'Data kelas berhasil terupdate',
                    ]);
                    // }
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Id tidak ditemukan',
                ]);
            }
        }
    }

    /**
     * Method index_delete digunakan untuk menghapus data kelas
     */
    public function index_delete()
    {
        $id = $this->delete('id');

        // Jika id kosong
        if ($id == null) {
            $this->response([
                'status' => false,
                'message' => 'Id harus dikirim',
            ]);
        } else {
            // Cek jika id tidak ada ditable kelas
            if ($this->kelas_model->delete_data_kelas($id) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data kelas berhasil terhapus',
                    'id' => $id,
                ]);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Id tidak ditemukan',
                ]);
            }
        }
    }
}
