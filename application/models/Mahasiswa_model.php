<?php
class Mahasiswa_model extends CI_Model
{
    public function get_data_mahasiswa($id = null)
    {
        if ($id == null) {
            // Jika idnya kosong 
            return $this->db->get('mahasiswa')->result_array();
        } else {
            return $this->db->get_where('mahasiswa', ['id' => $id])->row_array();
        }
    }

    public function insert_data_mahasiswa($data)
    {
        $this->db->insert('mahasiswa', $data);
        return $this->db->affected_rows();
    }

    public function update_data_mahasiswa($data, $id)
    {
        $this->db->update('mahasiswa', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function delete_data_mahasiswa($id)
    {
        $this->db->delete('mahasiswa', ['id' => $id]);
        return $this->db->affected_rows();
    }
}
