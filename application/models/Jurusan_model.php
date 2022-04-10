<?php
class Jurusan_model extends CI_Model
{
    public function get_data_jurusan($id = null)
    {
        if ($id == null) {
            // Jika idnya kosong 
            $this->db->select('*');
            $this->db->from('jurusan');
            $this->db->order_by('id', 'desc');
            $result = $this->db->get();
        } else {
            return $this->db->get_where('jurusan', ['id' => $id])->row_array();
        }
    }

    public function insert_data_jurusan($data)
    {
        $this->db->insert('jurusan', $data);
        return $this->db->affected_rows();
    }

    public function update_data_jurusan($data, $id)
    {
        $this->db->update('jurusan', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function delete_data_jurusan($id)
    {
        $this->db->delete('jurusan', ['id' => $id]);
        return $this->db->affected_rows();
    }
}
