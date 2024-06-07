<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CalculatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate request data
            $request->validate([
                'operation' => 'required|string',
                'operand1' => 'required|numeric',
                'operand2' => 'required|numeric',
            ]);

            $operand1 = $request->input('operand1');
            $operand2 = $request->input('operand2');
            $operation = $request->input('operation');

            // Perform the calculation based on the operation
            switch ($operation) {
                case '+':
                    $result = $operand1 + $operand2;
                    break;
                case '-':
                    $result = $operand1 - $operand2;
                    break;
                case '*':
                    $result = $operand1 * $operand2;
                    break;
                case '/':
                    // Check for division by zero
                    if ($operand2 == 0) {
                        return response()->json(['error' => 'Division by zero'], 400);
                    }
                    $result = $operand1 / $operand2;
                    break;
                default:
                    return response()->json(['error' => 'Invalid operation'], 400);
            }

            // Begin a transaction
            DB::beginTransaction();

            // Create a new calculation record
            $calculation = Calculation::create([
                'operand1' => $operand1,
                'operand2' => $operand2,
                'operation' => $operation,
                'result' => $result,
            ]);

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json(['message' => 'Calculation stored successfully', 'calculation' => $calculation], 200);
        } catch (Throwable $th) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Log the error for debugging (optional)
            Log::error('Failed to store calculation: ', ['error' => $th->getMessage()]);

            // Return an error response
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
