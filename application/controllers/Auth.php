<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('Auth_model');
	}

	public function index()
	{

		$this->form_validation->set_rules('username', 'username', 'required|trim');
		$this->form_validation->set_rules('password', 'password', 'required|trim');

		if ($this->form_validation->run() == false) {
			$data['judul'] = "Login";

			$this->load->view('templates/auth/header', $data);
			$this->load->view('auth/login');
			$this->load->view('templates/auth/footer');
		} else {
			$this->verification();
		}
	}

	public function registration()
	{

		// ========================================= FORM VALIDASI ==============================================

		$this->form_validation->set_rules('nama', 'nama', 'required|trim');
		$this->form_validation->set_rules('nim', 'NIM', 'required|trim');
		$this->form_validation->set_rules('fakultas', 'fakultas', 'required|trim');
		$this->form_validation->set_rules('jurusan', 'jurusan', 'required|trim');
		$this->form_validation->set_rules('angkatan', 'angkatan', 'required|trim');
		$this->form_validation->set_rules('email', 'email', 'required|trim|valid_email');
		$this->form_validation->set_rules('username', 'username', 'required|trim|is_unique[t_akun_mhs.username]', array('is_unique' => 'Username sudah digunakan'));
		$this->form_validation->set_rules('password1', 'password1', 'required|trim|min_length[6]|matches[password2]', array('required' => 'Kolom Password harus diisi', 'min_length' => 'Password minimum 6 karakter', 'matches' => 'Password tidak sama'));
		$this->form_validation->set_rules('password2', 'password2', 'required|trim|matches[password1]', array('required' => 'Kolom Password harus diisi', 'matches' => 'Password tidak sama'));


		// =========================================== CEK VALIDASI ============================================

		if ($this->form_validation->run() == false) {
			$data['judul'] = "Registration";

			$this->load->view('templates/auth/header', $data);
			$this->load->view('auth/register');
			$this->load->view('templates/auth/footer');
		} else {
			$this->Auth_model->addMahasiswa();
			$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert"><strong>Selamat!</strong> Akun anda berhasil ditambahkan</div>');
			redirect('Auth');
		}
	}

	public function verification()
	{
		if ($this->db->get_where('t_akun_mhs', ['username' => $this->input->post('username'), 'password' => $this->input->post('password')])->num_rows() > 0) {
			$this->db->select('id, nama, nim, jurusan, fakultas, angkatan');
			$this->db->from('t_akun_mhs');
			$this->db->join('t_mahasiswa', 't_akun_mhs.id_mhs = t_mahasiswa.id');
			$this->db->where('username', $this->input->post('username'));
			$this->db->where('password', $this->input->post('password'));

			$data = $this->db->get()->result_array()[0];

			$this->session->set_userdata($data);

			redirect('user');
		} else if ($this->db->get_where('t_admin', ['username' => $this->input->post('username'), 'password' => $this->input->post('password')])->num_rows()) {
			$data = $this->db->get_where('t_admin', ['username' => $this->input->post('username'), 'password' => $this->input->post('password')])->result_array()[0];

			$this->session->set_userdata([
				'id' => $data['id_admin'],
				'nama' => $data['nama']
			]);

			redirect('admin');
		} else {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert"><strong>Gagal Login!</strong> akun tidak ada atau username dan password tidak benar</div>');
			redirect('Auth');
		}
	}
}
