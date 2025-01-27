<?php

namespace App\Http\Controllers;

use App\Models\TravelRequest;
use Illuminate\Routing\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TravelRequestStatusChanged;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TravelRequestsController extends Controller
{
    /**
     * Cria uma nova solicitação de viagem.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTravelRequest(Request $request)
    {
        $data = $request->all();
        if (isset($data['departure_date'])) {
            $data['departure_date'] = Carbon::parse($data['departure_date'])->toDateString();
        }
        if (isset($data['return_date'])) {
            $data['return_date'] = Carbon::parse($data['return_date'])->toDateString();
        }
        $travelRequest = TravelRequest::create($data);
        return response()->json($travelRequest, 201);
    }

    /**
     * Atualiza uma solicitação de viagem existente.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTravelRequest($id, Request $request)
    {
        $travelRequest = TravelRequest::findOrFail($id);

        $data = $request->all();
        if (isset($data['departure_date'])) {
            $data['departure_date'] = Carbon::parse($data['departure_date'])->toDateString();
        }
        if (isset($data['return_date'])) {
            $data['return_date'] = Carbon::parse($data['return_date'])->toDateString();
        }

        $travelRequest->update($data);

        if ($request->has('status') && in_array($request->status, ["aprovado", "cancelado"])) {
            $travelRequest->status = $request->status;
            $travelRequest->save();
            Notification::sendNow($travelRequest->user, new TravelRequestStatusChanged($travelRequest));
        }

        return response()->json($travelRequest);
    }

    /**
     * Retorna uma solicitação de viagem específica.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTravelRequest($id)
    {
        $travelRequest = TravelRequest::findOrFail($id);
        return response()->json($travelRequest);
    }

    /**
     * Edita uma solicitação de viagem específica.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function editTravelRequest($id)
    {
        $travelRequest = TravelRequest::findOrFail($id);
        return response()->json($travelRequest);
    }

    /**
     * Lista todas as solicitações de viagem com base nos filtros fornecidos.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listTravelRequests(Request $request)
    {
        $query = TravelRequest::query();

        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        if ($request->has('id') && !empty($request->id)) {
            $query->where('id', $request->id);
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date) && !empty($request->end_date)) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->has('destination') && !empty($request->destination)) {
            $query->where('destination', $request->destination);
        }

        return response()->json($query->get());
    }
}
