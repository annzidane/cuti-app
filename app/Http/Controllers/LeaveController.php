<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\RedirectResponse;
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

            $employee = Employee::findOrFail($validated['employee_id']);
            $start = \Carbon\Carbon::parse($validated['start_date']);
            $end = \Carbon\Carbon::parse($validated['end_date']);

            // Validasi: tahun cuti harus tahun ini
            $currentYear = now()->year;
            if ($start->year != $currentYear || $end->year != $currentYear) {
                return redirect()->back()->withInput()->withErrors([
                    'start_date' => 'Cuti hanya bisa diajukan untuk tahun ini (' . $currentYear . ').',
                ]);
            }

            // Validasi: tanggal sudah pernah diajukan?
            $existingLeaves = Leave::where('employee_id', $employee->id)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('start_date', [$start, $end])
                        ->orWhereBetween('end_date', [$start, $end])
                        ->orWhere(function ($query) use ($start, $end) {
                            $query->where('start_date', '<=', $start)->where('end_date', '>=', $end);
                        });
                })->exists();

            if ($existingLeaves) {
                return redirect()->back()->withInput()->withErrors([
                    'start_date' => 'Tanggal cuti ini sudah diajukan sebelumnya.',
                ]);
            }

            // Hitung hari kerja
            $daysRequested = $start->diffInDaysFiltered(fn($date) => $date->isWeekday(), $end) + 1;

            if ($daysRequested > $employee->remaining_leave_quota) {
                return redirect()->back()->withInput()->withErrors([
                    'start_date' => "Anda hanya bisa mengajukan maksimal {$employee->remaining_leave_quota} hari.",
                ]);
            }

            Leave::create(array_merge($validated, ['total_days' => $daysRequested]));

            return redirect()->route('leaves.index')->with('success', 'Cuti berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan cuti. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Leave $leave
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $leave = Leave::with('employee')->findOrFail($id);
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
    public function update(Request $request, $id)
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

            $leave = Leave::findOrFail($id);
            $employee = Employee::findOrFail($validated['employee_id']);

            $start = \Carbon\Carbon::parse($validated['start_date']);
            $end = \Carbon\Carbon::parse($validated['end_date']);

            // Validasi: hanya tahun ini
            $currentYear = now()->year;
            if ($start->year != $currentYear || $end->year != $currentYear) {
                return redirect()->back()->withInput()->withErrors([
                    'start_date' => 'Cuti hanya bisa diajukan untuk tahun ini (' . $currentYear . ').',
                ]);
            }

            // Validasi: tanggal sudah diajukan sebelumnya, kecuali yang sedang diupdate
            $existingLeaves = Leave::where('employee_id', $employee->id)
                ->where('id', '!=', $leave->id)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('start_date', [$start, $end])
                        ->orWhereBetween('end_date', [$start, $end])
                        ->orWhere(function ($query) use ($start, $end) {
                            $query->where('start_date', '<=', $start)->where('end_date', '>=', $end);
                        });
                })->exists();

            if ($existingLeaves) {
                return redirect()->back()->withInput()->withErrors([
                    'start_date' => 'Tanggal cuti ini sudah diajukan sebelumnya.',
                ]);
            }

            // Kembalikan jatah cuti lama sebelum dihitung ulang
            $adjustedQuota = $employee->remaining_leave_quota + $leave->total_days;
            $daysRequested = $start->diffInDaysFiltered(fn($date) => $date->isWeekday(), $end) + 1;

            if ($daysRequested > $adjustedQuota) {
                return redirect()->back()->withInput()->withErrors([
                    'start_date' => "Anda hanya bisa mengajukan maksimal $adjustedQuota hari.",
                ]);
            }

            $leave->update(array_merge($validated, ['total_days' => $daysRequested]));

            return redirect()->route('leaves.index')->with('success', 'Cuti berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui cuti. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Leave $leave
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $leave = Leave::find($id);

            if (!$leave) {
                return redirect()->route('leaves.index')->with('error', 'Data cuti tidak ditemukan.');
            }

            Log::info('Menghapus cuti:', ['leave_id' => $leave->id]);

            $leave->delete();
            return redirect()->route('leaves.index')->with('success', 'Cuti berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus cuti: ' . $e->getMessage(), [
                'leave_id' => $id,
                'exception' => $e,
            ]);

            return redirect()->route('leaves.index')->with('error', 'Gagal menghapus cuti. Silakan coba lagi atau hubungi administrator.');
        }
    }
}