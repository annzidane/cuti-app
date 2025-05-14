<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $employees = Employee::paginate(10);
        return view('Admins.Employee.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('Admins.Employee.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email',
                'gender' => 'required|in:Male,Female',
                'phone_number' => 'required|string|max:20',
                'address' => 'required|string|max:500',
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
                'phone_number.required' => 'Nomor handphone wajib diisi.',
                'phone_number.string' => 'Nomor handphone harus berupa teks.',
                'phone_number.max' => 'Nomor handphone tidak boleh lebih dari 20 karakter.',
                'address.required' => 'Alamat wajib diisi.',
                'address.string' => 'Alamat harus berupa teks.',
                'address.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
            ]);

            Employee::create($validated);

            return redirect()->route('employees.index')->with('success', 'Pegawai berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan pegawai: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);

            return redirect()->back()->with('error', 'Gagal menambahkan pegawai. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Employee $employee
     * @return \Illuminate\View\View
     */
    public function show(Employee $employee)
    {
        return view('Admins.Employee.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Employee $employee
     * @return \Illuminate\View\View
     */
    public function edit(Employee $employee)
    {
        return view('Admins.Employee.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Employee $employee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Employee $employee)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email,' . $employee->id,
                'gender' => 'required|in:Male,Female',
                'phone_number' => 'required|string|max:20',
                'address' => 'required|string|max:500',
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
                'phone_number.required' => 'Nomor handphone wajib diisi.',
                'phone_number.string' => 'Nomor handphone harus berupa teks.',
                'phone_number.max' => 'Nomor handphone tidak boleh lebih dari 20 karakter.',
                'address.required' => 'Alamat wajib diisi.',
                'address.string' => 'Alamat harus berupa teks.',
                'address.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
            ]);

            $employee->update($validated);

            return redirect()->route('employees.index')->with('success', 'Pegawai berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pegawai: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);

            return redirect()->back()->with('error', 'Gagal memperbarui pegawai. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Employee $employee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();
            return redirect()->route('employees.index')->with('success', 'Pegawai berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus pegawai: ' . $e->getMessage(), [
                'employee_id' => $employee->id,
                'exception' => $e,
            ]);

            return redirect()->route('employees.index')->with('error', 'Gagal menghapus pegawai. Silakan coba lagi atau hubungi administrator.');
        }
    }
}