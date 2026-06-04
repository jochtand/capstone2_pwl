<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Session::has('user')) {
            return redirect('/'); 
        }
        
        return response()->view('auth.login')
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    }

    public function processLogin(Request $request)
    {
        // Panggil API Node.js
        $response = Http::post('http://localhost:3000/api/login', [
            'email' => $request->email,
            'password' => $request->password
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            if ($data['status'] == 'success') {
                $user = $data['data'];
                Session::put('user', $user);

                switch ($user['role']) {
                    case 'Administrator': return redirect('/admin/ruangan');
                    case 'Kepala Laboratorium': return redirect('/kepala-lab/draft');
                    case 'Kaprodi': return redirect('/kaprodi/review');
                    case 'Staf Administrasi': return redirect('/staf-admin/pengadaan');
                    case 'Staf Laboratorium': return redirect('/staf-lab/inventaris');
                }
            }
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function logout()
    {
        Session::flush(); // Hancurkan semua sesi
        return redirect('/')->with('success', 'Anda berhasil logout.');
    }
}