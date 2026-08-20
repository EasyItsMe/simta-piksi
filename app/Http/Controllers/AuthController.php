<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:50', 'unique:mahasiswa,nim'],
            'program_studi' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'file_krs' => ['required', 'mimes:pdf', 'max:2048'],
            'file_transkrip' => ['required', 'mimes:pdf', 'max:2048'],
            'file_pembayaran' => ['required', 'mimes:pdf', 'max:2048'],
        ]);

        // Create User
        $roleMahasiswa = \App\Models\Role::where('nama_role', 'Mahasiswa')->first();
        if (!$roleMahasiswa) {
            return back()->withErrors(['email' => 'Role Mahasiswa belum dikonfigurasi di sistem.']);
        }

        $user = \App\Models\User::create([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role_id' => $roleMahasiswa->id,
        ]);

        // Create Mahasiswa profile
        $mahasiswa = \App\Models\Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'nama_lengkap' => $request->nama_lengkap,
            'program_studi' => $request->program_studi,
        ]);

        // Handle File Uploads & Create PendaftaranTa
        $dataPendaftaran = [
            'mahasiswa_id' => $mahasiswa->id,
            'status' => 'menunggu',
        ];

        if ($request->hasFile('file_krs')) {
            $dataPendaftaran['file_krs'] = $request->file('file_krs')->store('pendaftaran', 'public');
        }
        if ($request->hasFile('file_transkrip')) {
            $dataPendaftaran['file_transkrip'] = $request->file('file_transkrip')->store('pendaftaran', 'public');
        }
        if ($request->hasFile('file_pembayaran')) {
            $dataPendaftaran['file_pembayaran'] = $request->file('file_pembayaran')->store('pendaftaran', 'public');
        }

        \App\Models\PendaftaranTa::create($dataPendaftaran);

        // Auto login
        // Hapus auto-login
        // auth()->login($user);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Dokumen syarat pendaftaran Anda sedang diverifikasi oleh Admin. Anda baru bisa login setelah di-ACC.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(\Illuminate\Http\Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            
            // Cek verifikasi pendaftaran untuk mahasiswa baru
            if ($user->isMahasiswa()) {
                $pendaftaran = \App\Models\PendaftaranTa::where('mahasiswa_id', $user->mahasiswa->id)->first();
                if ($pendaftaran) {
                    if ($pendaftaran->status === 'menunggu') {
                        auth()->logout();
                        return back()->withErrors(['email' => 'Akun Anda masih dalam antrean verifikasi Admin. Silakan cek kembali nanti.'])->onlyInput('email');
                    } elseif ($pendaftaran->status === 'ditolak') {
                        auth()->logout();
                        return back()->withErrors(['email' => 'Pendaftaran ditolak: ' . $pendaftaran->keterangan . '. Silakan hubungi Admin.'])->onlyInput('email');
                    }
                }
            }

            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(\Illuminate\Http\Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function updatePassword(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
