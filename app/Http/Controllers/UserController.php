<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = User::paginate(10);
        return view('admins.users.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pegawai = User::where('role', '!=', 'admin')->get();
        return view('Admins.Users.create', compact('pegawai'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'gender' => 'required|in:Male,Female',
                'dob' => 'required|date',
                'password' => 'required|min:6',
            ], [
                'first_name.required' => 'Nama depan wajib diisi.',
                'first_name.string' => 'Nama depan harus berupa teks.',
                'first_name.max' => 'Nama depan tidak boleh lebih dari 255 karakter.',
                'last_name.required' => 'Nama belakang wajib diisi.',
                'last_name.string' => 'Nama belakang harus berupa teks.',
                'last_name.max' => 'Nama belakang tidak boleh lebih dari 255 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar, gunakan email lain.',
                'gender.required' => 'Jenis kelamin wajib dipilih.',
                'gender.in' => 'Jenis kelamin harus Male atau Female.',
                'dob.required' => 'Tanggal lahir wajib diisi.',
                'dob.date' => 'Tanggal lahir harus dalam format tanggal yang valid.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password harus memiliki minimal 6 karakter.',
            ]);

            $validated['password'] = Hash::make('password');
            $validated['role'] = 'admin';

            User::create($validated);

            return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan user: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);

            return redirect()->back()->with('error', 'Gagal menambahkan user. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('Admins.Users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required|in:Male,Female',
            'dob' => 'required|date',
            'email' => 'required|email',
            'password' => 'nullable|min:6',
        ]);

        try {
            $user = User::findOrFail($id);

            $user->first_name = $validated['first_name'];
            $user->last_name = $validated['last_name'];
            $user->email = $validated['email'];
            $user->dob = $validated['dob'];
            $user->gender = $validated['gender'];

            if (!empty($validated['password'])) {
                $user->password = bcrypt($validated['password']);
            }

            $user->save();

            return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Gagal memperbarui User: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Gagal menghapus User: ' . $e->getMessage());
        }
    }
}
