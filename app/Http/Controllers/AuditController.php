<?php

namespace App\Http\Controllers;

use OwenIt\Auditing\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $models = Audit::query()->select('auditable_type')->distinct()->get()->map(function ($item){
            return $item->auditable_type;
        })->toArray();
        $selectedModel = str_replace('\\\\', '\\', $request->model);
        $selectedEvent = $request->event;
        $selectedStartDate = $request->data_inicio;
        $selectedEndDate = $request->data_fim;
        $audits = Audit::when($request->model, function ($query) use ($selectedModel) {
            $query->where('auditable_type', $selectedModel);
        })->when($request->event, function ($query) use ($request) {
            $query->where('event', $request->event);
        })->when($request->data_inicio, function ($query) use ($request) {
            $query->where('created_at', '>=' ,$request->data_inicio);
        })->when($request->data_fim, function ($query) use ($request) {
            $query->where('created_at', '<=' ,$request->data_fim);
        })->latest()->paginate(10);

        $audits->appends(
            [
                'selectedModel' => $selectedModel,
                'selectedEvent' => $selectedEvent,
                'selectedStartDate' => $selectedStartDate,
                'selectedEndDate' => $selectedEndDate
            ]
        );

        return view(
            'audits.index',
            compact('audits',
                'models',
                'selectedModel',
                'selectedEvent',
                'selectedStartDate',
                'selectedEndDate'
            )
        );
    }
}
