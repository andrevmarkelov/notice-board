<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Models\Ad;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AdController extends Controller
{
    /**
     * Display a list of advertisements
     *
     * @param Request $request
     * @param string $slug
     * @param int $limit
     * @return Response
     */
    public function index(Request $request, string $slug = 'limit', int $limit = 0): Response
    {
        try {
            $query = Ad::select((new Ad)->selectable);

            $sortFields = $request->input('sort_fields', ['created_at' => 'desc', 'price' => 'asc']);
            $query->applySorting($sortFields);

            $ads = $limit ? $query->paginate($limit) : $query->get();

            $ads->transform(function ($ad) {
                return [
                    'id' => $ad->id,
                    'title' => $ad->title,
                    'main_photo' => $ad->main_photo,
                    'price' => $ad->price
                ];
            });

            return response(['status' => 'success', 'response' => ['ads' => $ads]]);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'response' => [$e->getMessage()]], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display a single advertisement by ID
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function show(Request $request, int $id): Response
    {
        try {
            $ad = Ad::where('id', $id)->first();

            if (!$ad) {
                return response(['status' => 'error', 'response' => ['message' => 'Ad not found']], HttpResponse::HTTP_NOT_FOUND);
            }

            $response = [
                'title' => $ad->title,
                'price' => $ad->price,
                'main_photo' => $ad->main_photo,
            ];

            if ($request->has('fields')) {
                $fields = explode(',', $request->get('fields'));
                $optionalFields = $ad->getOptionalFields($fields);

                $response = array_merge($response, $optionalFields);
            }

            return response(['status' => 'success', 'response' => ['ad' => $response]]);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'response' => [$e->getMessage()]], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Stores a new advertisement in the database
     *
     * @param StoreAdRequest $request
     * @return Response
     */
    public function store(StoreAdRequest $request): Response
    {
        try {
            $input = $request->input();

            $ads = new Ad();
            $ads->title = $input['title'] ?? null;
            $ads->description = $input['description'] ?? null;
            $ads->price = $input['price'] ?? null;
            $ads->photos = $input['photos'] ?? null;

            if (!$ads->save()) {
                throw new Exception('An error occurred during saving. Please contact technical support.');
            }

            return response(['status' => 'success', 'response' => ['ads' => $ads->id]], 201);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'response' => [$e->getMessage()]], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
