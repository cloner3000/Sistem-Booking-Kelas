<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelas_model extends CI_Model
{
    public function getAllKelas()
    {
        return $this->db->get('t_ruangan')->result();
    }

    public function getPartKelas()
    {
        return $this->db->get('t_ruangan', 5)->result();
    }

    public function addKelas()
    {
        $data = [
            'nama_ruangan' => $this->input->post('nama_kelas'),
            'kapasitas' => $this->input->post('kapasitas')
        ];

        $this->db->insert('t_ruangan', $data);
    }
}