<?php

namespace App\Http\Controllers;

use App\Models\RestoTable;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRestoTableRequest;
use App\Http\Traits\ResponseFormatter;

class RestoTableController extends Controller
{
    use ResponseFormatter;
    public function index()
    {
        try {
            $tables = RestoTable::paginate(request()->get('per_page') ?? 10);
            return $this->success($tables, 'Successfully fetched resto tables.');
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    // Store a new table
    public function store(StoreRestoTableRequest $request)
    {
        try {
            $table = RestoTable::create($request->validated());
            return $this->success($table, 'Successfully created resto table.');
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $table= RestoTable::findOrFail($id);
            return $this->success($table, 'Successfully fetch resto table.');
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    // Update an existing table
    public function update(StoreRestoTableRequest $request, $id)
    {
        try {
            $table = RestoTable::findOrFail($id);
            $table->update($request->validated());
            return $this->success($table, 'Successfully updated resto table.');
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function destroy(RestoTable $table)
    {
        try {
            $table->delete();
            return $this->success($table, 'Successfully deleted resto table.');
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
