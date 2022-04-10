<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mahasiswa extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('mahasiswa_model');
    }

    /**
     * Method index_get digunakan untuk mengambil data mahasiswa
     */
    public function index_get()
    {
        $id = $this->get('id');

        // Jika id kosong
        if ($id == null) {
            $mahasiswa = $this->mahasiswa_model->get_data_mahasiswa();
        } else {
            $mahasiswa = $this->mahasiswa_model->get_data_mahasiswa($id);
        }

        // Jika data jurusannya ada 
        if ($mahasiswa) {
            $this->response([
                'status' => true,
                'message' => 'Data mahasiswa',
                'data' => $mahasiswa
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
    }

    /**
     * Method index_post digunakan untuk menambahkan data mahasiswa
     */
    public function index_post()
    {
        $data = [
            'jurusan_id' => $this->post('jurusan_id'),
            'kelas_id' => $this->post('kelas_id'),
            'angkatan_id' => $this->post('angkatan_id'),
            'nim' => $this->post('nim'),
            'nama' => $this->post('nama'),
            'jenis_kelamin' => $this->post('jenis_kelamin'),
            'email' => $this->post('email'),
            'no_telepon' => $this->post('no_telepon'),
            'alamat' => $this->post('alamat'),
        ];

        // Validasi inputan
        $this->form_validation->set_rules('jurusan_id', 'Jurusan', 'required');
        $this->form_validation->set_rules('kelas_id', 'Kelas', 'required');
        $this->form_validation->set_rules('angkatan_id', 'Angkatan', 'required');
        $this->form_validation->set_rules('nim', 'NIM', 'required|is_unique[mahasiswa.nim]');
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
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
            if ($this->mahasiswa_model->insert_data_petugas($data) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data mahasiswa berhasil tersimpan',
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal menambahkan data mahasiswa',
                ], 404);
            }
        }
    }

    /**
     * Method index_put digunakan untuk mengubah data mahasiswa
     */
    public function index_put()
    {
        $id = $this->put('id');

        $data = [
            'jurusan_id' => $this->post('jurusan_id'),
            'kelas_id' => $this->post('kelas_id'),
            'angkatan_id' => $this->post('angkatan_id'),
            'nim' => $this->post('nim'),
            'nama' => $this->post('nama'),
            'jenis_kelamin' => $this->post('jenis_kelamin'),
            'email' => $this->post('email'),
            'no_telepon' => $this->post('no_telepon'),
            'alamat' => $this->post('alamat'),
        ];

        // Cek jika id kosong
        if ($id == null) {
            $this->response([
                'status' => false,
                'message' => 'Id harus diisi',
            ], 400);
        } else {
            /**
             *  Ubah set datanya
             *  biar yang dicek saat validasi inputan itu method put bukan post
             */
            $this->form_validation->set_data($this->put());

            // cek jika id tidak ada
            $mahasiswa = $this->mahasiswa_model->get_data_mahasiswa($id);
            if ($mahasiswa) {
                // Cek jika nama nya sama
                if ($this->put('nim') == $mahasiswa['nim']) {
                    // Jika sama

                    // Validasi inputan
                    $this->form_validation->set_rules('jurusan_id', 'Jurusan', 'required');
                    $this->form_validation->set_rules('kelas_id', 'Kelas', 'required');
                    $this->form_validation->set_rules('angkatan_id', 'Angkatan', 'required');
                    $this->form_validation->set_rules('nim', 'NIM', 'required');
                    $this->form_validation->set_rules('nama', 'Nama', 'required');
                    $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
                    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                    $this->form_validation->set_rules('no_telepon', 'No Telepon', 'required');

                    // Mengeset pesan error dari validasi
                    $this->form_validation->set_message('required', '{field} harus diisi!.');
                    $this->form_validation->set_message('valid_email', '{field} tidak valid!.');
                    $this->form_validation->set_message('min_length', '{field} minimal 5 karakter!.');
                } else {
                    // Jika tidak

                    $data = [
                        'jurusan_id' => $this->post('jurusan_id'),
                        'kelas_id' => $this->post('kelas_id'),
                        'angkatan_id' => $this->post('angkatan_id'),
                        'nim' => $this->post('nim'),
                        'nama' => $this->post('nama'),
                        'jenis_kelamin' => $this->post('jenis_kelamin'),
                        'email' => $this->post('email'),
                        'no_telepon' => $this->post('no_telepon'),
                        'alamat' => $this->post('alamat'),
                    ];

                    // Validasi inputan
                    $this->form_validation->set_rules('jurusan_id', 'Jurusan', 'required');
                    $this->form_validation->set_rules('kelas_id', 'Kelas', 'required');
                    $this->form_validation->set_rules('angkatan_id', 'Angkatan', 'required');
                    $this->form_validation->set_rules('nim', 'NIM', 'required|is_unique[mahasiswa.nim]');
                    $this->form_validation->set_rules('nama', 'Nama', 'required');
                    $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
                    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
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
                    // if ($this->mahasiswa_model->update_data_petugas($data, $id) > 0) {
                    $this->mahasiswa_model->update_data_petugas($data, $id);
                    $this->response([
                        'status' => true,
                        'message' => 'Data mahasiswa berhasil terupdate',
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
     * Method index_delete digunakan untuk menghapus data mahasiswa
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
            // Cek jika id tidak ada ditable mahasiswa
            if ($this->mahasiswa_model->delete_data_petugas($id) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data mahasiswa berhasil terhapus',
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
