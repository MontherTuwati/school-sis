<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\FeeCategory;
use App\Models\Scholarship;
use Carbon\Carbon;

class FinancialController extends Controller
{
    /** financial dashboard */
    public function index()
    {
        $stats = $this->getFinancialStats();
        $recentPayments = Payment::with(['student', 'fee'])
                               ->orderBy('created_at', 'desc')
                               ->limit(10)
                               ->get();
        
        return view('financial.index', compact('stats', 'recentPayments'));
    }

    /** fees management */
    public function fees()
    {
        $fees = Fee::with(['student', 'category'])->get();
        $categories = FeeCategory::all();
        $students = Student::where('is_active', true)->get();
        
        return view('financial.fees', compact('fees', 'categories', 'students'));
    }

    /** add fee */
    public function createFee()
    {
        $categories = FeeCategory::all();
        $students = Student::where('is_active', true)->get();
        
        return view('financial.create-fee', compact('categories', 'students'));
    }

    /** store fee */
    public function storeFee(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'category_id' => 'required|exists:fee_categories,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after_or_equal:today',
            'description' => 'nullable|string|max:500',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'required_if:is_recurring,1|in:monthly,quarterly,semester,yearly',
        ]);

        try {
            $fee = new Fee();
            $fee->student_id = $request->student_id;
            $fee->category_id = $request->category_id;
            $fee->amount = $request->amount;
            $fee->due_date = $request->due_date;
            $fee->description = $request->description;
            $fee->is_recurring = $request->boolean('is_recurring');
            $fee->recurring_frequency = $request->recurring_frequency;
            $fee->status = 'pending';
            $fee->created_by = auth()->id();
            $fee->save();
            
            return redirect()->route('financial.fees')->with('success', 'Fee added successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to add fee: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add fee. Please try again.');
        }
    }

    /** edit fee */
    public function editFee($id)
    {
        $fee = Fee::with(['student', 'category'])->findOrFail($id);
        $categories = FeeCategory::all();
        $students = Student::where('is_active', true)->get();
        
        return view('financial.edit-fee', compact('fee', 'categories', 'students'));
    }

    /** update fee */
    public function updateFee(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'category_id' => 'required|exists:fee_categories,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'required_if:is_recurring,1|in:monthly,quarterly,semester,yearly',
        ]);

        try {
            $fee = Fee::findOrFail($id);
            $fee->student_id = $request->student_id;
            $fee->category_id = $request->category_id;
            $fee->amount = $request->amount;
            $fee->due_date = $request->due_date;
            $fee->description = $request->description;
            $fee->is_recurring = $request->boolean('is_recurring');
            $fee->recurring_frequency = $request->recurring_frequency;
            $fee->save();
            
            return redirect()->route('financial.fees')->with('success', 'Fee updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to update fee: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update fee. Please try again.');
        }
    }

    /** delete fee */
    public function destroyFee($id)
    {
        try {
            $fee = Fee::findOrFail($id);
            
            // Check if fee has payments
            $hasPayments = Payment::where('fee_id', $id)->exists();
            
            if ($hasPayments) {
                return redirect()->back()->with('error', 'Cannot delete fee with existing payments.');
            }
            
            $fee->delete();
            
            return redirect()->route('financial.fees')->with('success', 'Fee deleted successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to delete fee: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete fee. Please try again.');
        }
    }

    /** payments management */
    public function payments()
    {
        $payments = Payment::with(['student', 'fee'])->orderBy('created_at', 'desc')->get();
        $students = Student::where('is_active', true)->get();
        $fees = Fee::where('status', '!=', 'paid')->get();
        
        return view('financial.payments', compact('payments', 'students', 'fees'));
    }

    /** record payment */
    public function recordPayment()
    {
        $students = Student::where('is_active', true)->get();
        $fees = Fee::where('status', '!=', 'paid')->get();
        
        return view('financial.record-payment', compact('students', 'fees'));
    }

    /** store payment */
    public function storePayment(Request $request)
    {
        $request->validate([
            'fee_id' => 'required|exists:fees,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|in:cash,check,bank_transfer,credit_card,online',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $fee = Fee::findOrFail($request->fee_id);
            
            if ($fee->status === 'paid') {
                throw new \Exception('Fee has already been paid.');
            }
            
            // Create payment record
            $payment = new Payment();
            $payment->fee_id = $request->fee_id;
            $payment->student_id = $fee->student_id;
            $payment->amount = $request->amount;
            $payment->payment_date = $request->payment_date;
            $payment->payment_method = $request->payment_method;
            $payment->reference_number = $request->reference_number;
            $payment->notes = $request->notes;
            $payment->processed_by = auth()->id();
            $payment->save();
            
            // Update fee status
            if ($request->amount >= $fee->amount) {
                $fee->status = 'paid';
                $fee->paid_amount = $fee->amount;
            } else {
                $fee->status = 'partial';
                $fee->paid_amount = ($fee->paid_amount ?? 0) + $request->amount;
            }
            $fee->save();
            
            DB::commit();
            return redirect()->route('financial.payments')->with('success', 'Payment recorded successfully!');
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Failed to record payment: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /** scholarships management */
    public function scholarships()
    {
        $scholarships = Scholarship::with(['student'])->get();
        $students = Student::where('is_active', true)->get();
        
        return view('financial.scholarships', compact('scholarships', 'students'));
    }

    /** add scholarship */
    public function createScholarship()
    {
        $students = Student::where('is_active', true)->get();
        
        return view('financial.create-scholarship', compact('students'));
    }

    /** store scholarship */
    public function storeScholarship(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        try {
            $scholarship = new Scholarship();
            $scholarship->student_id = $request->student_id;
            $scholarship->type = $request->type;
            $scholarship->amount = $request->amount;
            $scholarship->percentage = $request->percentage;
            $scholarship->start_date = $request->start_date;
            $scholarship->end_date = $request->end_date;
            $scholarship->description = $request->description;
            $scholarship->is_active = $request->boolean('is_active');
            $scholarship->created_by = auth()->id();
            $scholarship->save();
            
            return redirect()->route('financial.scholarships')->with('success', 'Scholarship added successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to add scholarship: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add scholarship. Please try again.');
        }
    }

    /** categories management */
    public function categories()
    {
        $categories = FeeCategory::withCount('fees')->get();
        
        return view('financial.categories', compact('categories'));
    }

    /** store category */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:fee_categories',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        try {
            $category = new FeeCategory();
            $category->name = $request->name;
            $category->description = $request->description;
            $category->is_active = $request->boolean('is_active');
            $category->save();
            
            return redirect()->route('financial.categories')->with('success', 'Category added successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to add category: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add category. Please try again.');
        }
    }

    /** financial reports */
    public function reports()
    {
        $stats = $this->getFinancialReportStats();
        
        return view('financial.reports', compact('stats'));
    }

    /** export financial data */
    public function export(Request $request)
    {
        $type = $request->input('type', 'fees');
        $format = $request->input('format', 'csv');
        
        switch ($type) {
            case 'fees':
                $data = Fee::with(['student', 'category'])->get();
                break;
            case 'payments':
                $data = Payment::with(['student', 'fee'])->get();
                break;
            case 'scholarships':
                $data = Scholarship::with('student')->get();
                break;
            default:
                $data = [];
        }
        
        if ($format === 'csv') {
            return $this->exportToCSV($data, $type);
        } else {
            return $this->exportToPDF($data, $type);
        }
    }

    /** get financial statistics */
    private function getFinancialStats()
    {
        $totalFees = Fee::sum('amount');
        $totalPaid = Payment::sum('amount');
        $totalPending = $totalFees - $totalPaid;
        
        $pendingFees = Fee::where('status', '!=', 'paid')->count();
        $overdueFees = Fee::where('due_date', '<', Carbon::today())
                         ->where('status', '!=', 'paid')
                         ->count();
        
        $totalScholarships = Scholarship::where('is_active', true)->sum('amount');
        
        // Recent activity
        $recentPayments = Payment::where('created_at', '>=', Carbon::now()->subDays(30))->sum('amount');
        $recentFees = Fee::where('created_at', '>=', Carbon::now()->subDays(30))->sum('amount');
        
        return [
            'total_fees' => $totalFees,
            'total_paid' => $totalPaid,
            'total_pending' => $totalPending,
            'pending_fees' => $pendingFees,
            'overdue_fees' => $overdueFees,
            'total_scholarships' => $totalScholarships,
            'recent_payments' => $recentPayments,
            'recent_fees' => $recentFees,
        ];
    }

    /** get financial report statistics */
    private function getFinancialReportStats()
    {
        // Monthly payment trends
        $monthlyPayments = [];
        for ($i = 0; $i < 6; $i++) {
            $month = Carbon::now()->subMonths($i);
            $amount = Payment::whereYear('payment_date', $month->year)
                           ->whereMonth('payment_date', $month->month)
                           ->sum('amount');
            $monthlyPayments[$month->format('M Y')] = $amount;
        }
        
        // Payment method distribution
        $paymentMethods = Payment::selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
                                ->groupBy('payment_method')
                                ->get();
        
        // Category-wise fees
        $categoryFees = FeeCategory::withSum('fees', 'amount')
                                 ->withCount('fees')
                                 ->get();
        
        // Outstanding fees by student
        $outstandingFees = Student::withSum(['fees as total_fees' => function($query) {
            $query->where('status', '!=', 'paid');
        }])
        ->withSum(['payments as total_payments' => function($query) {
            $query->whereHas('fee', function($q) {
                $q->where('status', '!=', 'paid');
            });
        }])
        ->get()
        ->map(function($student) {
            $student->outstanding = ($student->total_fees ?? 0) - ($student->total_payments ?? 0);
            return $student;
        })
        ->where('outstanding', '>', 0)
        ->sortByDesc('outstanding');
        
        return [
            'monthly_payments' => $monthlyPayments,
            'payment_methods' => $paymentMethods,
            'category_fees' => $categoryFees,
            'outstanding_fees' => $outstandingFees,
        ];
    }

    /** export to CSV */
    private function exportToCSV($data, $type)
    {
        $filename = $type . '_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data, $type) {
            $file = fopen('php://output', 'w');
            
            switch ($type) {
                case 'fees':
                    fputcsv($file, ['Student', 'Category', 'Amount', 'Due Date', 'Status', 'Description']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->student ? $item->student->first_name . ' ' . $item->student->last_name : 'N/A',
                            $item->category ? $item->category->name : 'N/A',
                            $item->amount,
                            $item->due_date,
                            ucfirst($item->status),
                            $item->description ?? 'N/A'
                        ]);
                    }
                    break;
                case 'payments':
                    fputcsv($file, ['Student', 'Fee', 'Amount', 'Payment Date', 'Method', 'Reference']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->student ? $item->student->first_name . ' ' . $item->student->last_name : 'N/A',
                            $item->fee ? $item->fee->description : 'N/A',
                            $item->amount,
                            $item->payment_date,
                            ucfirst($item->payment_method),
                            $item->reference_number ?? 'N/A'
                        ]);
                    }
                    break;
                case 'scholarships':
                    fputcsv($file, ['Student', 'Type', 'Amount', 'Percentage', 'Start Date', 'End Date', 'Status']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->student ? $item->student->first_name . ' ' . $item->student->last_name : 'N/A',
                            $item->type,
                            $item->amount,
                            $item->percentage ?? 'N/A',
                            $item->start_date,
                            $item->end_date,
                            $item->is_active ? 'Active' : 'Inactive'
                        ]);
                    }
                    break;
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /** export to PDF */
    private function exportToPDF($data, $type)
    {
        // Placeholder for PDF export - would use a library like DomPDF
        return response()->json(['message' => 'PDF export not implemented yet']);
    }
}
