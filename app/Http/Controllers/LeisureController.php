<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Leisure;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseFormatter;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateLeisureRequest;
use App\Http\Requests\UpdateLeisureRequest;
use App\Http\Resources\LeisureResource;

class LeisureController extends Controller
{
    use ResponseFormatter;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $data = Leisure::query();

            if (request()->has('is_available')) {
                $data->where('availability', filter_var(request()->get('is_available'), FILTER_VALIDATE_BOOLEAN));
            }

            $data = request()->has('per_page')
                ? $data->paginate(request()->get('per_page'))
                : $data->get();
            
            return $this->success($data);
        } catch (Exception $ex) {
            return $this->error([], $ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLeisureRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $imagePath = $request->file('image')->store('leisures', 'public'); // Save to storage/app/public/leisures
                $data['image'] = $imagePath;
            }

            $leisure = Leisure::create($data);
            return $this->success(new LeisureResource($leisure));
        } catch (Exception $ex) {
            return $this->error([], $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Leisure $leisure)
    {
        try {
            return $this->success(new LeisureResource($leisure));
        } catch (Exception $ex) {
            return $this->error([], $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeisureRequest $request, Leisure $leisure)
    {
        try {
            $data = $request->validated();

            // Check if an image is provided for update
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Delete the old image if it exists
                if ($leisure->image && Storage::disk('public')->exists($leisure->image)) {
                    Storage::disk('public')->delete($leisure->image);
                }

                // Save the new image
                $imagePath = $request->file('image')->store('leisures', 'public');
                $data['image'] = $imagePath;
            }

            $leisure->update($data);
            return $this->success(new LeisureResource($leisure), 'Leisure updated successfully');
        } catch (Exception $ex) {
            return $this->error([], $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $leisure = Leisure::findOrFail($id);
            $leisure->delete();
            return $this->success([], 'Leisure deleted successfully.');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
