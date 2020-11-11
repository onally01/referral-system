<?php

namespace App\Http\Controllers;

use App\Http\Requests\WithdrawalRequest;
use App\Models\Withdrawal;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class WithdrawalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Process withdrawal
     *
     * @param WithdrawalRequest $request
     * @return JsonResponse
     */
    public function create(WithdrawalRequest $request): JsonResponse
    {
        Withdrawal::create($request->validated());

        return response()->json('', Response::HTTP_OK);
    }
}
