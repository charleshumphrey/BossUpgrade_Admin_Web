<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Storage;

class FAQController extends Controller
{
    protected $firebaseService;
    protected $firebaseStorage;
    protected $faqTable = 'faqs';

    public function __construct(FirebaseService $firebaseService, Storage $firebaseStorage)
    {
        $this->firebaseService = $firebaseService;
        $this->firebaseStorage = $firebaseStorage;
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'  => 'required|string|max:1000',
        ]);

        $faqData = [
            'question'    => $request->input('question'),
            'answer'      => $request->input('answer'),
            'created_at'  => now()->toIso8601String(),
            'updated_at'  => now()->toIso8601String(),
        ];

        $this->firebaseService->insertData($this->faqTable, $faqData);

        return redirect()->back()->with('success', 'FAQ added successfully!');
    }

    public function index()
    {
        $faqs = $this->firebaseService->getData($this->faqTable);

        return view('faq', compact('faqs'));
    }

    public function destroy($id)
    {

        $this->firebaseService->deleteData($this->faqTable, $id);

        return redirect()->back()->with('success', 'FAQ deleted successfully!');
    }
}
