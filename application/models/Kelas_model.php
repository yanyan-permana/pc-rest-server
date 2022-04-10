<?php
class Kelas_model extends CI_Model
{
    public function get_data_kelas($id = null)
    {
        if ($id == null) {
            // Jika idnya kosong 
            $this->db->select('*');
            $this->db->from('kelas');
            $this->db->order_by('id', 'desc');
            $result = $this->db->get();

            return $result->result_array();
        } else {
            return $this->db->get_where('kelas', ['id' => $id])->row_array();
        }
    }

    public function insert_data_kelas($data)
    {
        $this->db->insert('kelas', $data);
        return $this->db->affected_rows();
    }

    public function update_data_kelas($data, $id)
    {
        $this->db->update('kelas', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function delete_data_kelas($id)
    {
        $this->db->delete('kelas', ['id' => $id]);
        return $this->db->affected_rows();
    }
}
