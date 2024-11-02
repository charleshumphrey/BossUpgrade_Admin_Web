<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Str;
use Kreait\Firebase\Storage;
use Kreait\Firebase\Database as FirebaseDatabase;

class PromotionsController extends Controller
{
    protected $firebaseService;
    protected $firebaseStorage;
    protected $promotionsTable = 'promotions';


    public function __construct(FirebaseService $firebaseService, Storage $firebaseStorage)
    {
        $this->firebaseService = $firebaseService;
        $this->firebaseStorage = $firebaseStorage;
    }

    public function index()
    {
        $promotions = $this->firebaseService->getPromotions();
        return view('promotions', compact('promotions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'promotional_images' => 'required|array',
            'promotional_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageUrls = [];

        if ($request->hasFile('promotional_images')) {
            foreach ($request->file('promotional_images') as $image) {
                $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();
                $firebaseStoragePath = 'promotions/' . $filename;

                try {
                    $this->firebaseStorage->getBucket()->upload(
                        file_get_contents($image->getRealPath()),
                        [
                            'name' => $firebaseStoragePath,
                            'predefinedAcl' => 'publicRead',
                        ]
                    );

                    $imageUrls[] = 'https://storage.googleapis.com/' . $this->firebaseStorage->getBucket()->name() . '/' . $firebaseStoragePath;
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Failed to upload images: ' . $e->getMessage());
                }
            }
        }

        // Store the image URLs as JSON
        $database = $this->firebaseService->getDatabase();
        $database->getReference('promotions')->set(json_encode($imageUrls));

        return redirect()->back()->with('success', 'Images uploaded successfully!');
    }

    public function destroy($key)
    {
        $database = $this->firebaseService->getDatabase();
        $promotionsRef = $database->getReference('promotions');

        if ($promotionsRef->getChild($key)->getValue()) {
            $promotionsRef->getChild($key)->remove();
            $promotions = $promotionsRef->getValue();

            if (empty($promotions)) {
                return redirect()->back()->with('success', 'Promotion image deleted successfully.');
            }

            $newPromotions = [];

            foreach ($promotions as $index => $promotion) {
                if ($index != $key) {
                    $newPromotions[] = $promotion;
                }
            }

            $promotionsRef->set($newPromotions);

            return redirect()->back()->with('success', 'Promotion image deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Promotion not found.');
        }
    }
}
