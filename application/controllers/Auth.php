<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('Auth_model');
	}

	public function index()
	{
		$data['judul'] = "Login";

		$this->load->view('templates/auth_header', $data);
		$this->load->view('auth/login');
		$this->load->view('templates/auth_footer');
	}

	public function registration()
	{

		// ========================================= FORM VALIDASI ==============================================
		
		$this->form_validation->set_rules('nama', 'Nama', 'required|trim');
		$this->form_validation->set_rules('nim', 'NIM', 'required|trim');
		$this->form_validation->set_rules('fakultas', 'Fakultas','required|trim');
		$this->form_validation->set_rules('jurusan', 'Jurusan', 'required|trim');
		$this->form_validation->set_rules('angkatan', 'Angkatan', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[t_akun_mhs.username]', array('is_unique' => 'Username sudah digunakan'));
		$this->form_validation->set_rules('password1', 'Password1', 'required|trim|min_length[6]|matches[password2]', array('required' => 'Kolom Password harus diisi', 'min_length' => 'Password minimum 6 karakter', 'matches' => 'Password tidak sama'));
		$this->form_validation->set_rules('password2', 'Password2', 'required|trim|matches[password1]', array('required' => 'Kolom Password harus diisi', 'matches' => 'Password tidak sama'));


		// =========================================== CEK VALIDASI ============================================

		if ($this->form_validation->run() == false)
		{
			$data['judul'] = "Registration";
			
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/register');
			$this->load->view('templates/auth_footer');			
		} else 
		{
			$this->Auth_model->addMahasiswa();
			$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert"><strong>Selamat!</strong> Akun anda berhasil ditambahkan</div>');
			redirect('Auth');
		}

	}
}