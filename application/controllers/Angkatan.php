<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Angkatan extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('angkatan_model');
        $this->methods['index_get']['limit'] = 100;
        $this->methods['index_post']['limit'] = 100;
        $this->methods['index_put']['limit'] = 100;
        $this->methods['index_delete']['limit'] = 100;
    }

    /**
     * Method index_get digunakan untuk mengambil data angkatan
     */
    public function index_get()
    {
        $id = $this->get('id');

        // Jika id kosong
        if ($id == null) {
            $angkatan = $this->angkatan_model->get_data_angkatan();
        } else {
            $angkatan = $this->angkatan_model->get_data_angkatan($id);
        }

        // Jika data jurusannya ada 
        if ($angkatan) {
            $this->response([
                'status' => true,
                'message' => 'Data angkatan',
                'data' => $angkatan
            ]);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    /**
     * Method index_post digunakan untuk menambahkan data angkatan
     */
    public function index_post()
    {
        $data = [
            'tahun' => $this->post('tahun'),
        ];

        // Validasi inputan
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|numeric|is_unique[angkatan.tahun]');

        // Mengeset pesan error dari validasi
        $this->form_validation->set_message('required', '{field} harus diisi!.');
        $this->form_validation->set_message('numeric', '{field} harus berisi number!.');
        $this->form_validation->set_message('is_unique', '{field} sudah ada!.');

        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => false,
                'tahun' => form_error('tahun'),
            ]);
        } else {
            if ($this->angkatan_model->insert_data_angkatan($data) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data angkatan berhasil tersimpan',
                ]);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal menambahkan data angkatan',
                ]);
            }
        }
    }

    /**
     * Method index_put digunakan untuk mengubah data angkatan
     */
    public function index_put()
    {
        $id = $this->put('id');

        $data = [
            'tahun' => $this->put('tahun'),
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
            $angkatan = $this->angkatan_model->get_data_angkatan($id);
            if ($angkatan) {
                // Cek jika tahun nya sama
                if ($this->put('tahun') == $angkatan['tahun']) {
                    // Jika sama

                    // Validasi inputan
                    $this->form_validation->set_rules('tahun', 'Tahun', 'required|numeric');

                    // Merubah pesan error dari validasi
                    $this->form_validation->set_message('required', '{field} harus diisi!.');
                    $this->form_validation->set_message('numeric', '{field} harus berisi number!.');
                } else {
                    // Jika tidak

                    // Validasi inputan
                    $this->form_validation->set_rules('tahun', 'Tahun', 'required|numeric|is_unique[angkatan.tahun]');

                    // Merubah pesan error dari validasi
                    $this->form_validation->set_message('required', '{field} harus diisi!.');
                    $this->form_validation->set_message('numeric', '{field} harus berisi number!.');
                    $this->form_validation->set_message('is_unique', '{field} sudah ada!.');
                }

                if (!$this->form_validation->run()) {
                    $this->response([
                        'status' => false,
                        'tahun' => form_error('tahun'),
                    ]);
                } else {
                    // if ($this->angkatan_model->update_data_angkatan($data, $id) > 0) {
                    $this->angkatan_model->update_data_angkatan($data, $id);
                    $this->response([
                        'status' => true,
                        'message' => 'Data angkatan berhasil terupdate',
                    ]);
                    // }
                }
            } else {
                $this->response([
                    'error' => true,
                    'message' => 'Id tidak ditemukan',
                ]);
            }
        }
    }

    /**
     * Method index_delete digunakan untuk menghapus data angkatan
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
            // Cek jika id tidak ada ditable angkatan
            if ($this->angkatan_model->delete_data_angkatan($id) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data angkatan berhasil terhapus',
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
