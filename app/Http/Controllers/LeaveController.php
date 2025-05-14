<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $leaves = Leave::with('employee')
            ->orderByDesc('start_date')
            ->paginate(10);
        return view('admins.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $pegawai = Employee::select('id', 'first_name', 'last_name')->get();
        return view('admins.create', compact('pegawai'));
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
                'employee_id' => 'required|exists:employees,id',
                'reason' => 'required|string|max:1000',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
            ], [
                'employee_id.required' => 'Pegawai wajib dipilih.',
                'employee_id.exists' => 'Pegawai yang dipilih tidak valid.',
                'reason.required' => 'Alasan cuti wajib diisi.',
                'reason.string' => 'Alasan cuti harus berupa teks.',
                'reason.max' => 'Alasan cuti tidak boleh lebih dari 1000 karakter.',
                'start_date.required' => 'Tanggal mulai wajib diisi.',
                'start_date.date' => 'Tanggal mulai harus dalam format tanggal yang valid.',
                'start_date.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
                'end_date.required' => 'Tanggal selesai wajib diisi.',
                'end_date.date' => 'Tanggal selesai harus dalam format tanggal yang valid.',
                'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            ]);

            Leave::create($validated);

            return redirect()->route('leaves.index')->with('success', 'Cuti berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan cuti: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);

            return redirect()->back()->with('error', 'Gagal menambahkan cuti. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Leave $leave
     * @return \Illuminate\View\View
     */
    public function show(Leave $leave)
    {
        $pegawai = Employee::select('id', 'first_name', 'last_name')->get();
        return view('admins.show', compact('leave', 'pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Leave $leave
     * @return \Illuminate\View\View
     */
    public function edit(Leave $leave)
    {
        $pegawai = Employee::select('id', 'first_name', 'last_name')->get();
        return view('admins.edit', compact('leave', 'pegawai'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Leave $leave
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Leave $leave)
    {
        try {
            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'reason' => 'required|string|max:1000',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
            ], [
                'employee_id.required' => 'Pegawai wajib dipilih.',
                'employee_id.exists' => 'Pegawai yang dipilih tidak valid.',
                'reason.required' => 'Alasan cuti wajib diisi.',
                'reason.string' => 'Alasan cuti harus berupa teks.',
                'reason.max' => 'Alasan cuti tidak boleh lebih dari 1000 karakter.',
                'start_date.required' => 'Tanggal mulai wajib diisi.',
                'start_date.date' => 'Tanggal mulai harus dalam format tanggal yang valid.',
                'start_date.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
                'end_date.required' => 'Tanggal selesai wajib diisi.',
                'end_date.date' => 'Tanggal selesai harus dalam format tanggal yang valid.',
                'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            ]);

            $leave->update($validated);

            return redirect()->route('leaves.index')->with('success', 'Cuti berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui cuti: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);

            return redirect()->back()->with('error', 'Gagal memperbarui cuti. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Leave $leave
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Leave $leave)
    {
        try {
            $leave->delete();
            return redirect()->route('leaves.index')->with('success', 'Cuti berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus cuti: ' . $e->getMessage(), [
                'leave_id' => $leave->id,
                'exception' => $e,
            ]);

            return redirect()->route('leaves.index')->with('error', 'Gagal menghapus cuti. Silakan coba lagi atau hubungi administrator.');
        }
    }
}