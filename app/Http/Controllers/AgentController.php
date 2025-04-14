<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAgentRequest;
use App\Http\Traits\ResponseFormatter;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    use ResponseFormatter;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $agents = Agent::when(request()->has('is_available'), function ($query) {
                        return $query->where('availability', (bool)request()->get('is_available'));
                    })->paginate(request()->get('per_page') ?? 10);
            return $this->success($agents, 'Successfully fetch agent list.');
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAgentRequest $request)
    {
        try {
            $agent = Agent::create($request->validated());
            return $this->success($agent, 'Successfully store agent information.');
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Agent $agent)
    {
        try {
            return $this->success($agent, 'Successfully fetch agent information.');
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAgentRequest $request, Agent $agent)
    {
        try {
            $agent->update($request->validated());
            return $this->success($agent, 'Successfully updated agent information.');
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agent $agent)
    {
        try {
            $agent->delete();
            return $this->success($agent, 'Successfully deleted agent information.');
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
