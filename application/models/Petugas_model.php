<?php
class Petugas_model extends CI_Model
{
    public function get_data_petugas($id = null)
    {
        if ($id == null) {
            // Jika idnya kosong 
            return $this->db->get_where('user', ['role' => 3])->result_array();
        } else {
            return $this->db->get_where('user', ['id' => $id])->row_array();
        }
    }

    public function insert_data_petugas($data)
    {
        $this->db->insert('user', $data);
        return $this->db->affected_rows();
    }

    public function update_data_petugas($data, $id)
    {
        $this->db->update('user', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function delete_data_petugas($id)
    {
        $this->db->delete('user', ['id' => $id]);
        return $this->db->affected_rows();
    }
}
