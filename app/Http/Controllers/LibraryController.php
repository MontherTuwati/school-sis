<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Borrowing;
use App\Models\Category;
use Carbon\Carbon;

class LibraryController extends Controller
{
    /** library dashboard */
    public function index()
    {
        $stats = $this->getLibraryStats();
        $recentBorrowings = Borrowing::with(['book', 'borrower'])
                                   ->orderBy('created_at', 'desc')
                                   ->limit(10)
                                   ->get();
        
        return view('library.index', compact('stats', 'recentBorrowings'));
    }

    /** books list */
    public function books()
    {
        $books = Book::with(['category'])->get();
        $categories = Category::all();
        
        return view('library.books', compact('books', 'categories'));
    }

    /** add book */
    public function create()
    {
        $categories = Category::all();
        return view('library.create', compact('categories'));
    }

    /** store book */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books',
            'category_id' => 'required|exists:categories,id',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'edition' => 'nullable|string|max:50',
            'pages' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:1000',
            'copies' => 'required|integer|min:1',
            'location' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $book = new Book();
            $book->title = $request->title;
            $book->author = $request->author;
            $book->isbn = $request->isbn;
            $book->category_id = $request->category_id;
            $book->publisher = $request->publisher;
            $book->publication_year = $request->publication_year;
            $book->edition = $request->edition;
            $book->pages = $request->pages;
            $book->description = $request->description;
            $book->copies = $request->copies;
            $book->available_copies = $request->copies;
            $book->location = $request->location;
            $book->price = $request->price;
            $book->is_active = true;
            $book->save();

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $image = $request->file('cover_image');
                $imageName = 'book_' . $book->id . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/books', $imageName);
                $book->cover_image = $imageName;
                $book->save();
            }
            
            return redirect()->route('library.books')->with('success', 'Book added successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to add book: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add book. Please try again.');
        }
    }

    /** edit book */
    public function edit($id)
    {
        $book = Book::with('category')->findOrFail($id);
        $categories = Category::all();
        
        return view('library.edit', compact('book', 'categories'));
    }

    /** update book */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn,' . $id,
            'category_id' => 'required|exists:categories,id',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'edition' => 'nullable|string|max:50',
            'pages' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:1000',
            'copies' => 'required|integer|min:1',
            'location' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $book = Book::findOrFail($id);
            $book->title = $request->title;
            $book->author = $request->author;
            $book->isbn = $request->isbn;
            $book->category_id = $request->category_id;
            $book->publisher = $request->publisher;
            $book->publication_year = $request->publication_year;
            $book->edition = $request->edition;
            $book->pages = $request->pages;
            $book->description = $request->description;
            $book->copies = $request->copies;
            $book->location = $request->location;
            $book->price = $request->price;
            $book->save();

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $image = $request->file('cover_image');
                $imageName = 'book_' . $book->id . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/books', $imageName);
                $book->cover_image = $imageName;
                $book->save();
            }
            
            return redirect()->route('library.books')->with('success', 'Book updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to update book: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update book. Please try again.');
        }
    }

    /** delete book */
    public function destroy($id)
    {
        try {
            $book = Book::findOrFail($id);
            
            // Check if book has active borrowings
            $activeBorrowings = Borrowing::where('book_id', $id)
                                       ->where('return_date', null)
                                       ->count();
            
            if ($activeBorrowings > 0) {
                return redirect()->back()->with('error', 'Cannot delete book with active borrowings.');
            }
            
            $book->delete();
            
            return redirect()->route('library.books')->with('success', 'Book deleted successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to delete book: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete book. Please try again.');
        }
    }

    /** borrow book */
    public function borrow()
    {
        $books = Book::where('available_copies', '>', 0)->with('category')->get();
        $students = Student::where('is_active', true)->get();
        $teachers = Teacher::where('is_active', true)->get();
        
        return view('library.borrow', compact('books', 'students', 'teachers'));
    }

    /** process borrowing */
    public function processBorrow(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrower_type' => 'required|in:student,teacher',
            'borrower_id' => 'required|integer',
            'borrow_date' => 'required|date|after_or_equal:today',
            'due_date' => 'required|date|after:borrow_date',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $book = Book::findOrFail($request->book_id);
            
            if ($book->available_copies <= 0) {
                throw new \Exception('No copies available for borrowing.');
            }
            
            // Check if borrower has overdue books
            $overdueBooks = Borrowing::where('borrower_id', $request->borrower_id)
                                   ->where('borrower_type', $request->borrower_type)
                                   ->where('due_date', '<', Carbon::today())
                                   ->where('return_date', null)
                                   ->count();
            
            if ($overdueBooks > 0) {
                throw new \Exception('Borrower has overdue books. Please return them first.');
            }
            
            // Create borrowing record
            $borrowing = new Borrowing();
            $borrowing->book_id = $request->book_id;
            $borrowing->borrower_type = $request->borrower_type;
            $borrowing->borrower_id = $request->borrower_id;
            $borrowing->borrow_date = $request->borrow_date;
            $borrowing->due_date = $request->due_date;
            $borrowing->notes = $request->notes;
            $borrowing->processed_by = auth()->id();
            $borrowing->save();
            
            // Update book availability
            $book->available_copies = $book->available_copies - 1;
            $book->save();
            
            DB::commit();
            return redirect()->route('library.borrowings')->with('success', 'Book borrowed successfully!');
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Failed to process borrowing: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /** return book */
    public function return($id)
    {
        $borrowing = Borrowing::with(['book', 'borrower'])->findOrFail($id);
        
        return view('library.return', compact('borrowing'));
    }

    /** process return */
    public function processReturn(Request $request, $id)
    {
        $request->validate([
            'return_date' => 'required|date|after_or_equal:today',
            'condition' => 'required|in:good,fair,poor,damaged',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $borrowing = Borrowing::findOrFail($id);
            
            if ($borrowing->return_date) {
                throw new \Exception('Book has already been returned.');
            }
            
            $borrowing->return_date = $request->return_date;
            $borrowing->condition = $request->condition;
            $borrowing->notes = $request->notes;
            $borrowing->processed_by = auth()->id();
            $borrowing->save();
            
            // Update book availability
            $book = Book::find($borrowing->book_id);
            $book->available_copies = $book->available_copies + 1;
            $book->save();
            
            DB::commit();
            return redirect()->route('library.borrowings')->with('success', 'Book returned successfully!');
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Failed to process return: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /** borrowings list */
    public function borrowings()
    {
        $borrowings = Borrowing::with(['book', 'borrower'])->orderBy('created_at', 'desc')->get();
        
        return view('library.borrowings', compact('borrowings'));
    }

    /** categories management */
    public function categories()
    {
        $categories = Category::withCount('books')->get();
        
        return view('library.categories', compact('categories'));
    }

    /** store category */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $category = new Category();
            $category->name = $request->name;
            $category->description = $request->description;
            $category->save();
            
            return redirect()->route('library.categories')->with('success', 'Category added successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to add category: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add category. Please try again.');
        }
    }

    /** library reports */
    public function reports()
    {
        $stats = $this->getLibraryReportStats();
        
        return view('library.reports', compact('stats'));
    }

    /** export library data */
    public function export(Request $request)
    {
        $type = $request->input('type', 'books');
        $format = $request->input('format', 'csv');
        
        switch ($type) {
            case 'books':
                $data = Book::with('category')->get();
                break;
            case 'borrowings':
                $data = Borrowing::with(['book', 'borrower'])->get();
                break;
            case 'categories':
                $data = Category::withCount('books')->get();
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

    /** get library statistics */
    private function getLibraryStats()
    {
        $totalBooks = Book::count();
        $totalCopies = Book::sum('copies');
        $availableCopies = Book::sum('available_copies');
        $borrowedCopies = $totalCopies - $availableCopies;
        
        $activeBorrowings = Borrowing::where('return_date', null)->count();
        $overdueBorrowings = Borrowing::where('due_date', '<', Carbon::today())
                                    ->where('return_date', null)
                                    ->count();
        
        $totalCategories = Category::count();
        
        // Recent additions
        $recentBooks = Book::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        
        return [
            'total_books' => $totalBooks,
            'total_copies' => $totalCopies,
            'available_copies' => $availableCopies,
            'borrowed_copies' => $borrowedCopies,
            'active_borrowings' => $activeBorrowings,
            'overdue_borrowings' => $overdueBorrowings,
            'total_categories' => $totalCategories,
            'recent_books' => $recentBooks,
        ];
    }

    /** get library report statistics */
    private function getLibraryReportStats()
    {
        // Most borrowed books
        $mostBorrowed = Book::withCount('borrowings')
                           ->orderBy('borrowings_count', 'desc')
                           ->limit(10)
                           ->get();
        
        // Category distribution
        $categoryDistribution = Category::withCount('books')->get();
        
        // Monthly borrowing trends
        $monthlyTrends = [];
        for ($i = 0; $i < 6; $i++) {
            $month = Carbon::now()->subMonths($i);
            $count = Borrowing::whereYear('borrow_date', $month->year)
                             ->whereMonth('borrow_date', $month->month)
                             ->count();
            $monthlyTrends[$month->format('M Y')] = $count;
        }
        
        // Overdue analysis
        $overdueAnalysis = [
            'total_overdue' => Borrowing::where('due_date', '<', Carbon::today())
                                      ->where('return_date', null)
                                      ->count(),
            'overdue_by_days' => [
                '1-7 days' => Borrowing::where('due_date', '<', Carbon::today())
                                     ->where('due_date', '>=', Carbon::today()->subDays(7))
                                     ->where('return_date', null)
                                     ->count(),
                '8-30 days' => Borrowing::where('due_date', '<', Carbon::today()->subDays(7))
                                      ->where('due_date', '>=', Carbon::today()->subDays(30))
                                      ->where('return_date', null)
                                      ->count(),
                '30+ days' => Borrowing::where('due_date', '<', Carbon::today()->subDays(30))
                                     ->where('return_date', null)
                                     ->count(),
            ]
        ];
        
        return [
            'most_borrowed' => $mostBorrowed,
            'category_distribution' => $categoryDistribution,
            'monthly_trends' => $monthlyTrends,
            'overdue_analysis' => $overdueAnalysis,
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
                case 'books':
                    fputcsv($file, ['Title', 'Author', 'ISBN', 'Category', 'Publisher', 'Year', 'Copies', 'Available', 'Location']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->title,
                            $item->author,
                            $item->isbn,
                            $item->category ? $item->category->name : 'N/A',
                            $item->publisher,
                            $item->publication_year,
                            $item->copies,
                            $item->available_copies,
                            $item->location ?? 'N/A'
                        ]);
                    }
                    break;
                case 'borrowings':
                    fputcsv($file, ['Book', 'Borrower', 'Borrow Date', 'Due Date', 'Return Date', 'Status']);
                    foreach ($data as $item) {
                        $status = $item->return_date ? 'Returned' : 
                                ($item->due_date < Carbon::today() ? 'Overdue' : 'Active');
                        fputcsv($file, [
                            $item->book ? $item->book->title : 'N/A',
                            $item->borrower ? $item->borrower->first_name . ' ' . $item->borrower->last_name : 'N/A',
                            $item->borrow_date,
                            $item->due_date,
                            $item->return_date ?? 'Not Returned',
                            $status
                        ]);
                    }
                    break;
                case 'categories':
                    fputcsv($file, ['Name', 'Description', 'Books Count']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->name,
                            $item->description ?? 'N/A',
                            $item->books_count
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
