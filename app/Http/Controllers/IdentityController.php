<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdentityRequest;
use App\Services\Media\Base64Service;
use Illuminate\Http\Response;

class IdentityController extends Controller
{
    /**
     * Save user identity.
     *
     * @param IdentityRequest $request
     *
     * @return Response
     */
    public function __invoke(IdentityRequest $request): Response
    {
        $user = $request->user();
        $user->identity =  (new Base64Service())->save(
            "/resources/identity",
            $request->input('identity')
        );
        $user->save();

        return response()->noContent();
    }
}
