<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Petugas extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('petugas_model');
    }

    /**
     * Method index_get digunakan untuk mengambil data petugas
     */
    public function index_get()
    {
        $id = $this->get('id');

        // Jika id kosong
        if ($id == null) {
            $petugas = $this->petugas_model->get_data_petugas();
        } else {
            $petugas = $this->petugas_model->get_data_petugas($id);
        }

        // Jika data jurusannya ada 
        if ($petugas) {
            $this->response([
                'status' => true,
                'message' => 'Data petugas',
                'data' => $petugas
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
    }

    /**
     * Method index_post digunakan untuk menambahkan data petugas
     */
    public function index_post()
    {
        $data = [
            'nama' => $this->post('nama'),
            'email' => $this->post('email'),
            'password' => password_hash($this->post('password'), PASSWORD_DEFAULT),
            'role' => '3',
            'no_telepon' => $this->post('no_telepon'),
            // 'foto' => $this->post('foto'),
        ];

        // Validasi inputan
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|is_unique[user.email]|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('no_telepon', 'No Telepon', 'required');

        // Mengeset pesan error dari validasi
        $this->form_validation->set_message('required', '{field} harus diisi!.');
        $this->form_validation->set_message('is_unique', '{field} sudah ada!.');
        $this->form_validation->set_message('valid_email', '{field} tidak valid!.');
        $this->form_validation->set_message('min_length', '{field} minimal 5 karakter!.');

        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'error' => true,
                'nama' => form_error('nama'),
                'email' => form_error('email'),
                'password' => form_error('password'),
                'no_telepon' => form_error('no_telepon'),
            ], 400);
        } else {
            if ($this->petugas_model->insert_data_petugas($data) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data petugas berhasil tersimpan',
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal menambahkan data petugas',
                ], 404);
            }
        }
    }

    /**
     * Method index_put digunakan untuk mengubah data petugas
     */
    public function index_put()
    {
        $id = $this->put('id');

        $data = [
            'nama' => $this->put('nama'),
            'email' => $this->put('email'),
            'password' => password_hash($this->put('password'), PASSWORD_DEFAULT),
            'no_telepon' => $this->put('no_telepon'),
            // 'foto' => $this->put('foto'),
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
            $petugas = $this->petugas_model->get_data_petugas($id);
            if ($petugas) {
                // Cek jika nama nya sama
                if ($this->put('email') == $petugas['email']) {
                    // Jika sama

                    // Validasi inputan
                    $this->form_validation->set_rules('nama', 'Nama', 'required');
                    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                    $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
                    $this->form_validation->set_rules('no_telepon', 'No Telepon', 'required');

                    // Mengeset pesan error dari validasi
                    $this->form_validation->set_message('required', '{field} harus diisi!.');
                    $this->form_validation->set_message('valid_email', '{field} tidak valid!.');
                    $this->form_validation->set_message('min_length', '{field} minimal 5 karakter!.');
                } else {
                    // Jika tidak

                    // Validasi inputan
                    $this->form_validation->set_rules('nama', 'Nama', 'required');
                    $this->form_validation->set_rules('email', 'Email', 'required|is_unique[user.email]|valid_email');
                    $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
                    $this->form_validation->set_rules('no_telepon', 'No Telepon', 'required');

                    // Mengeset pesan error dari validasi
                    $this->form_validation->set_message('required', '{field} harus diisi!.');
                    $this->form_validation->set_message('is_unique', '{field} sudah ada!.');
                    $this->form_validation->set_message('valid_email', '{field} tidak valid!.');
                    $this->form_validation->set_message('min_length', '{field} minimal 5 karakter!.');
                }

                if (!$this->form_validation->run()) {
                    $this->response([
                        'error' => true,
                        'nama' => form_error('nama'),
                    ], 400);
                } else {
                    // if ($this->petugas_model->update_data_petugas($data, $id) > 0) {
                    $this->petugas_model->update_data_petugas($data, $id);
                    $this->response([
                        'status' => true,
                        'message' => 'Data petugas berhasil terupdate',
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
     * Method index_delete digunakan untuk menghapus data petugas
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
            // Cek jika id tidak ada ditable petugas
            if ($this->petugas_model->delete_data_petugas($id) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data petugas berhasil terhapus',
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
