<?php

defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Jenis_pembayaran extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('jenis_pembayaran_model');
    }

    /**
     * Method index_get digunakan untuk mengambil data jenis pembayaran
     */
    public function index_get()
    {
        $id = $this->get('id');

        // Jika id kosong
        if ($id == null) {
            $jenis_pembayaran = $this->jenis_pembayaran_model->get_data_jenis_pembayaran();
        } else {
            $jenis_pembayaran = $this->jenis_pembayaran_model->get_data_jenis_pembayaran($id);
        }

        // Jika data kelasnya ada 
        if ($jenis_pembayaran) {
            $this->response([
                'status' => true,
                'message' => 'Data jenis pembayaran',
                'data' => $jenis_pembayaran
            ]);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    /**
     * Method index_post digunakan untuk menambahkan data jenis pembayaran
     */
    public function index_post()
    {
        $data = [
            'kelas_id' => $this->post('kelas_id'),
            'nama' => $this->post('nama'),
            'biaya' => $this->post('biaya'),
        ];

        // Validasi inputan
        $this->form_validation->set_rules('kelas_id', 'Kelas', 'required');
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('biaya', 'Biaya', 'required|numeric');

        // Mengeset pesan error dari validasi
        $this->form_validation->set_message('required', '{field} harus diisi!.');
        $this->form_validation->set_message('numberic', '{field} harus berisi number!.');

        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => false,
                'kelas_id' => form_error('kelas_id'),
                'nama' => form_error('nama'),
                'biaya' => form_error('biaya'),
            ]);
        } else {
            if ($this->jenis_pembayaran_model->insert_data_jenis_pembayaran($data) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data jenis pembayaran berhasil tersimpan',
                ]);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal menambahkan data jenis_pembayaran',
                ]);
            }
        }
    }

    /**
     * Method index_put digunakan untuk mengubah data jenis pembayaran
     */
    public function index_put()
    {
        $id = $this->put('id');

        $data = [
            'kelas_id' => $this->put('kelas_id'),
            'nama' => $this->put('nama'),
            'biaya' => $this->put('biaya'),
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
            $jenis_pembayaran = $this->jenis_pembayaran_model->get_data_jenis_pembayaran($id);
            if ($jenis_pembayaran) {

                // Validasi inputan
                $this->form_validation->set_rules('kelas_id', 'Kelas', 'required');
                $this->form_validation->set_rules('nama', 'Nama', 'required');
                $this->form_validation->set_rules('biaya', 'Biaya', 'required');

                // Mengeset pesan error dari validasi
                $this->form_validation->set_message('required', '{field} harus diisi!.');

                if (!$this->form_validation->run()) {
                    $this->response([
                        'status' => false,
                        'kelas_id' => form_error('kelas_id'),
                        'nama' => form_error('nama'),
                        'biaya' => form_error('biaya'),
                    ]);
                } else {
                    // if ($this->jenis_pembayaran_model->update_data_jenis_pembayaran($data, $id) > 0) {
                    $this->jenis_pembayaran_model->update_data_jenis_pembayaran($data, $id);
                    $this->response([
                        'status' => true,
                        'message' => 'Data jenis pembayaran berhasil terupdate',
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
     * Method index_delete digunakan untuk menghapus data jenis pembayaran
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
            // Cek jika id tidak ada ditable jenis_pembayaran
            if ($this->jenis_pembayaran_model->delete_data_jenis_pembayaran($id) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data jenis_pembayaran berhasil terhapus',
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
