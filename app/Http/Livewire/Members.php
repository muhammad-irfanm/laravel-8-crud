<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Member;

class Members extends Component
{
    public $members, $name, $email, $phone_number, $status, $member_id;
    public $isModal = 0;

  	//FUNGSI INI UNTUK ME-LOAD VIEW YANG AKAN MENJADI TAMPILAN HALAMAN MEMBER
    public function render()
    {
        $this->members = Member::orderBy('created_at', 'DESC')->get(); //MEMBUAT QUERY UNTUK MENGAMBIL DATA
        return view('livewire.members'); //LOAD VIEW MEMBERS.BLADE.PHP YG ADA DI DALAM FOLDER /RESOURSCES/VIEWS/LIVEWIRE
    }

    //FUNGSI INI AKAN DIPANGGIL KETIKA TOMBOL TAMBAH ANGGOTA DITEKAN
    public function create()
    {
        //KEMUDIAN DI DALAMNYA KITA MENJALANKAN FUNGSI UNTUK MENGOSONGKAN FIELD
        $this->resetFields();
        //DAN MEMBUKA MODAL
        $this->openModal();
    }

    //FUNGSI INI UNTUK MENUTUP MODAL DIMANA VARIABLE ISMODAL KITA SET JADI FALSE
    public function closeModal()
    {
        $this->isModal = false;
    }

    //FUNGSI INI DIGUNAKAN UNTUK MEMBUKA MODAL
    public function openModal()
    {
        $this->isModal = true;
    }

    //FUNGSI INI UNTUK ME-RESET FIELD/KOLOM, SESUAIKAN FIELD APA SAJA YANG KAMU MILIKI
    public function resetFields()
    {
        $this->name = '';
        $this->email = '';
        $this->phone_number = '';
        $this->status = '';
        $this->member_id = '';
    }

    //METHOD STORE AKAN MENG-HANDLE FUNGSI UNTUK MENYIMPAN / UPDATE DATA
    public function store()
    {
        //MEMBUAT VALIDASI
        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:members,email,' . $this->member_id,
            'phone_number' => 'required|numeric',
            'status' => 'required'
        ]);

        //QUERY UNTUK MENYIMPAN / MEMPERBAHARUI DATA MENGGUNAKAN UPDATEORCREATE
        //DIMANA ID MENJADI UNIQUE ID, JIKA IDNYA TERSEDIA, MAKA UPDATE DATANYA
        //JIKA TIDAK, MAKA TAMBAHKAN DATA BARU
        Member::updateOrCreate(['id' => $this->member_id], [
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'status' => $this->status,
        ]);

        //BUAT FLASH SESSION UNTUK MENAMPILKAN ALERT NOTIFIKASI
        session()->flash('message', $this->member_id ? $this->name . ' Diperbaharui': $this->name . ' Ditambahkan');
        $this->closeModal(); //TUTUP MODAL
        $this->resetFields(); //DAN BERSIHKAN FIELD
    }

    //FUNGSI INI UNTUK MENGAMBIL DATA DARI DATABASE BERDASARKAN ID MEMBER
    public function edit($id)
    {
        $member = Member::find($id); //BUAT QUERY UTK PENGAMBILAN DATA
        //LALU ASSIGN KE DALAM MASING-MASING PROPERTI DATANYA
        $this->member_id = $id;
        $this->name = $member->name;
        $this->email = $member->email;
        $this->phone_number = $member->phone_number;
        $this->status = $member->status;

        $this->openModal(); //LALU BUKA MODAL
    }

    //FUNGSI INI UNTUK MENGHAPUS DATA
    public function delete($id)
    {
        $member = Member::find($id); //BUAT QUERY UNTUK MENGAMBIL DATA BERDASARKAN ID
        $member->delete(); //LALU HAPUS DATA
        session()->flash('message', $member->name . ' Dihapus'); //DAN BUAT FLASH MESSAGE UNTUK NOTIFIKASI
    }
}

