<?php
class Jenis_pembayaran_model extends CI_Model
{
    public function get_data_jenis_pembayaran($id = null)
    {
        if ($id == null) {
            // Jika idnya kosong 
            $this->db->select('*, jenis_pembayaran.id as id_jenis_pembayaran, jenis_pembayaran.nama as nama_jenis_pembayaran, kelas.nama as nama_kelas');
            $this->db->from('jenis_pembayaran');
            $this->db->order_by('jenis_pembayaran.id', 'desc');
            $this->db->join('kelas', 'kelas.id = jenis_pembayaran.kelas_id');
            $result = $this->db->get();

            return $result->result_array();
        } else {
            return $this->db->get_where('jenis_pembayaran', ['id' => $id])->row_array();
        }
    }

    public function insert_data_jenis_pembayaran($data)
    {
        $this->db->insert('jenis_pembayaran', $data);
        return $this->db->affected_rows();
    }

    public function update_data_jenis_pembayaran($data, $id)
    {
        $this->db->update('jenis_pembayaran', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function delete_data_jenis_pembayaran($id)
    {
        $this->db->delete('jenis_pembayaran', ['id' => $id]);
        return $this->db->affected_rows();
    }
}
