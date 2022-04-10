<?php
class Angkatan_model extends CI_Model
{
    public function get_data_angkatan($id = null)
    {
        if ($id == null) {
            // Jika idnya kosong 
            // return $this->db->get('angkatan')->result_array();
            $this->db->select('*');
            $this->db->from('angkatan');
            $this->db->order_by('id', 'desc');
            $result = $this->db->get();

            return $result->result_array();
        } else {
            return $this->db->get_where('angkatan', ['id' => $id])->row_array();
        }
    }

    public function insert_data_angkatan($data)
    {
        $this->db->insert('angkatan', $data);
        return $this->db->affected_rows();
    }

    public function update_data_angkatan($data, $id)
    {
        $this->db->update('angkatan', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function delete_data_angkatan($id)
    {
        $this->db->delete('angkatan', ['id' => $id]);
        return $this->db->affected_rows();
    }
}
