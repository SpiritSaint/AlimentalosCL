<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Repositories\UploadsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function Ramsey\Uuid\v4;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $pets = Pet::query()
            ->paginate(
                $request->has('per_page') ?
                    $request->input('per_page') : config('pagination.per_page')
            );

        return response()
            ->json($pets, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $avatar = UploadsRepository::make($request->file('avatar'));

        $pet = Pet::query()
            ->create([
                'avatar' => $avatar,
                'type' => $request->input('type'),
                'name' => $request->input('name'),
                'description' => $request->has('description') ? $request->input('description') : null,
            ]);

        return response()
            ->json($pet, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Pet $pet
     * @return JsonResponse
     */
    public function show(Pet $pet): JsonResponse
    {
        return response()
            ->json($pet, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Pet $pet
     * @return JsonResponse
     */
    public function update(Request $request, Pet $pet) : JsonResponse
    {
        $avatar = $request->has('avatar') ?
            UploadsRepository::make($request->file('avatar')) : $pet->avatar;

        $pet->update([
            'avatar' => $avatar,
            'type' => $request->has('type') ? $request->input('type') : $pet->type,
            'name' => $request->has('name') ? $request->input('name') : $pet->name,
            'description' => $request->has('description') ? $request->input('description') : $pet->description,
        ]);

        return response()
            ->json($pet, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Pet $pet
     * @return JsonResponse
     */
    public function destroy(Pet $pet): JsonResponse
    {
        $pet->delete();

        return response()
            ->json($pet, 200);
    }
}
