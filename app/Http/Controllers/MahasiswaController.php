<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use PDF;
use App\Models\Oracle;
class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function oracle()
    {
        $data = new Oracle;
        return $data;
    }

     public function uploadFile(Request $request,$oke)
    {
            $result ='';
            $file = $request->file($oke);
            $name = $file->getClientOriginalName();
            // $tmp_name = $file['tmp_name'];

            $extension = explode('.',$name);
            $extension = strtolower(end($extension));

            $key = rand().'-'.$oke;
            $tmp_file_name = "{$key}.{$extension}";
            $tmp_file_path = "admin/images/mahasiswa/";
            $file->move($tmp_file_path,$tmp_file_name);

            $result = 'admin/images/mahasiswa'.'/'.$tmp_file_name;
        return $result;
    }

    public function index()
    {
        $mahasiswa=Mahasiswa::with('kelas')->get(); 
        // Mengambil semua isi tabel
        $paginate=Mahasiswa::orderBy('nim','desc')->paginate(1);
        return view('mahasiswa.index',compact('mahasiswa','paginate'))->with('i',(request()->input('page',1)-1)*5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas=Kelas::all();
        return view('mahasiswa.create',compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['nim'=>'required'
        ,'nama'=>'required'
        ,'kelas_id'=>'required'
        ,'jurusan'=>'required'
        ,'no_handphone'=>'required'
        ,'email'=>'required'
        ,'tanggal_lahir'=>'required'
        ]);//fungsieloquentuntukmenambahdata
       // $image = $request->file('foto');
        $foto = $this->uploadFile($request,'foto');
        $file_name = $foto;
        $upOracle = $this->oracle()->upFileOracle($file_name);
        // if($image)
        // {
        //    $image_name = $request->file('foto')->store('images','public');
        // }
       Mahasiswa::create([

       'nim'=>$request->nim
       ,'nama'=>$request->nama
       ,'kelas_id'=>$request->kelas_id
       ,'jurusan'=>$request->jurusan
       ,'no_handphone'=>$request->no_handphone
       ,'email'=>$request->email
       ,'tanggal_lahir'=>$request->tanggal_lahir
       ,'foto'=>$upOracle['message']

       ]);
        //jikadataberhasilditambahkan,akankembalikehalamanutama
        return redirect()->route('mahasiswa.index')->with('success','Mahasiswa Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($nim)
    {
        $mahasiswa=Mahasiswa::find($nim);
        return view('mahasiswa.detail',compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($nim)
    {
        $mahasiswa=Mahasiswa::find($nim);
        $kelas=Kelas::all();
        return view('mahasiswa.edit',compact('mahasiswa','kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nim)
    {
        $request->validate(['nim'=>'required'
        ,'nama'=>'required'
        ,'kelas_id'=>'required'
        ,'jurusan'=>'required'
        ,'no_handphone'=>'required'
         ,'email'=>'required'
        ,'tanggal_lahir'=>'required'
        ]);
        $mhs = Mahasiswa::find($nim);
        $mhs->nim = $request->nim;
        $mhs->nama = $request->nama;
        $mhs->kelas_id = $request->kelas_id;
        $mhs->jurusan = $request->jurusan;
        $mhs->no_handphone = $request->no_handphone;
        $mhs->email = $request->email;
        $mhs->tanggal_lahir = $request->tanggal_lahir;
         if($mhs->foto && file_exists(storage_path('app/public/'.$mhs->foto)))
        {
            Storage::delete('public/'.$mhs->foto);
        }
        $image_name = $request->file('foto')->store('images','public');
        $mhs->foto = $image_name;
        $mhs->save();
        return redirect()->route('mahasiswa.index')->with('success','Mahasiswa Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($nim)
    {
        Mahasiswa::find($nim)->delete();
        return redirect()->route('mahasiswa.index')->with('success','Mahasiswa Berhasil  Dihapus');
    }

    public function nilai($nim)
    {
        $mhs = Mahasiswa::find($nim);
        //$jajal = $mhs->matakuliah;
        //$kelas = $mhs->kelas->nama_kelas;
         return view('mahasiswa.nilai',compact('mhs'));
    }

    public function cetak_pdf($id)
    {
        $mhs = Mahasiswa::find($id);
        $pdf = PDF::loadview('mahasiswa.cetak_pdf',compact('mhs'));
        return $pdf->stream();
    }
}
