<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Str;
use Kreait\Firebase\Storage as FirebaseStorage;

class PromotionsController extends Controller
{
    protected $firebaseService;
    protected $firebaseStorage;
    protected $promotionsTable = 'promotions';


    public function __construct(FirebaseService $firebaseService, FirebaseStorage $firebaseStorage)
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
        $validatedData = $request->validate([
            'promotional_images' => 'required|array', // Ensure it's an array
            'promotional_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $promoImageUrls = [];

        if ($request->hasFile('promotional_images')) {
            foreach ($request->file('promotional_images') as $promoImage) {
                $promoImageName = Str::random(20) . '.' . $promoImage->getClientOriginalExtension();
                $promoFirebaseStoragePath = 'promotions/' . $promoImageName;

                $uploadedPromoFile = $this->firebaseStorage->getBucket()->upload(
                    file_get_contents($promoImage->getRealPath()),
                    [
                        'name' => $promoFirebaseStoragePath,
                        'predefinedAcl' => 'publicRead',
                    ]
                );

                $promoImageUrl = 'https://storage.googleapis.com/' . $this->firebaseStorage->getBucket()->name() . '/' . $promoFirebaseStoragePath;

                $promoImageUrls[] = $promoImageUrl;
            }
        }

        $database = $this->firebaseService->getDatabase();
        $promotionsRef = $database->getReference('promotions');

        $currentPromotions = $promotionsRef->getValue() ?: [];

        $nextIndex = count($currentPromotions);

        foreach ($promoImageUrls as $url) {
            $promotionsRef->getChild($nextIndex)->set($url);
            $nextIndex++;
        }

        return redirect()->back()->with('success', 'Promotional images added successfully.');
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
