<?php
class Pimpinan_model extends CI_Model
{
    public function get_data_pimpinan($id = null)
    {
        if ($id == null) {
            // Jika idnya kosong 
            return $this->db->get_where('user', ['role' => 2])->result_array();
        } else {
            return $this->db->get_where('user', ['id' => $id])->row_array();
        }
    }

    public function insert_data_pimpinan($data)
    {
        $this->db->insert('user', $data);
        return $this->db->affected_rows();
    }

    public function update_data_pimpinan($data, $id)
    {
        $this->db->update('user', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function delete_data_pimpinan($id)
    {
        $this->db->delete('user', ['id' => $id]);
        return $this->db->affected_rows();
    }
}
