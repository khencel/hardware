<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseFormatter;

class PackageController extends Controller
{
    use ResponseFormatter;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $packages = Package::when(request()->has('is_available'), function ($query) {
                            return $query->where('availability', (bool)request()->get('is_available'));
                        })->paginate(request()->get('per_page') ?? 10);
            return $this->success($packages, "Successfully fetch packages.");
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePackageRequest $request)
    {
        try {
            $package = Package::create($request->validated());
            return $this->success($package, "Successfully store package.", 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        try {
            return $this->success($package, 'Successfully fetch package.', 200);
        } catch (\Exception $e) {
            return $this->success([], $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePackageRequest $request, Package $package)
    {
        try {

            $package->update($request->validated());

            return $this->success($package, 'Successfully updated package.', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        try {
            $package->delete();

            return $this->success([], 'Successfully deleted package.');
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
