<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Measurements;
use App\Models\Personnel;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request){

        $properties = Property::query()
                    ->with([
                        'personnel',
                    ])
                    ->when($request->search, function ($query, $search) {
                        $query->where('particulars', 'like', "%{$search}%");
                    })
                    ->orderBy('id', 'desc')
                    ->cursorPaginate(15)
                    ->withQueryString();

        return inertia('property/index', [
            'list' => $properties,
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }

    public function edit(string $id){
        $property = Property::findOrFail($id);
        $categories = Category::all();
        $measurements = Measurements::all();
        $personnel = Personnel::where('account_status','Assigned')->get();

        return inertia('property/edit',[
            'property' => $property,
            'personnel' => $personnel,
            'measurements' => $measurements,
            'categories' => $categories
        ]);
    }

    public function update(string $id, Request $request){
        $validated = $request->validate([
            'property_no'       => 'required|string',
            'measurement_unit'  => 'required|string',
            'particulars'       => 'required|string',
            'unit_cost'         => 'required|numeric',
            'status'            => 'required|string',
            'remarks'           => 'nullable|string',
            'category'          => 'required|string',
            'mr_to'             => 'required|numeric',
            'acquisition_date'  => 'required|date',
            'specs'             => 'nullable|string',
        ]);

        $property = Property::findOrFail($id);
        $property->update($validated);

        return back();
    }

    public function create(){
        $categories = Category::all();
        $measurements = Measurements::all();
        $personnel = Personnel::where('account_status','Assigned')->get();

        return inertia('property/create',[
            'personnel' => $personnel,
            'measurements' => $measurements,
            'categories' => $categories
        ]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'property_no'       => 'required|string',
            'measurement_unit'  => 'required|string',
            'particulars'       => 'required|string',
            'unit_cost'         => 'required|numeric',
            'status'            => 'required|string',
            'remarks'           => 'nullable|string',
            'category'          => 'required|string',
            'mr_to'             => 'required|numeric',
            'acquisition_date'  => 'required|date',
            'specs'             => 'nullable|string',
        ]);

        Property::create($validated);

        return back();
    }

    public function transferForm(Request $request){

        $properties = $request->search
        ? Property::query()
            ->with('personnel')
            ->where(function ($query) use ($request) {
                $query->where('particulars', 'like', "%{$request->search}%")
                    ->orWhere('property_no', 'like', "%{$request->search}%");
            })
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get()
        : [];

        $personnel = Personnel::where('account_status','Assigned')->get();


        return inertia('property/transfer',[
            'properties' => $properties,
            'personnel' => $personnel
        ]);
    }

    public function transfer(Request $request){
        $validated = $request->validate([
            'properties' => 'required|array|min:1',
            'personnel'  => 'required|numeric'
        ]);

        foreach($validated['properties'] as $prop){
            $property = Property::findOrFail($prop);
            $property->update(['mr_to' => $validated['personnel']]);
        }

        return back();
    }

    public function inventoryReport(Request $request){
        $person = $request->personnel ? Personnel::findOrFail($request->personnel) : null;
        $person?->load(['properties']);
        $personnel = Personnel::where('account_status','Assigned')->get();
        
        return inertia('property/inventoryReport',[
            'personnel' => $personnel,
            'person' => $person
        ]);
    }

}
