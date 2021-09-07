<?php

namespace App\Http\Controllers;

use App\Exceptions\InstagramParserException;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Service;
use App\Services\InstaParser\InstaParser;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

class OrderController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param CreateOrderRequest $request
     * @param InstaParser $parser
     * @return Factory|View|RedirectResponse|Response|null
     */
    public function create(CreateOrderRequest $request, InstaParser $parser)
    {
        try {
            $profile = $parser->getProfile($request->get('url'));
        } catch (InstagramParserException $e) {
            return redirect()->back()->withErrors(['url' => $e->getUserMessage()]);
        }

        $services = Service::all();

        return view('orders.create', compact('profile', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrderRequest $request
     * @return RedirectResponse|Response|Redirector|null
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $service = Service::query()->find($request->get('service_id'));

        $data['charge'] = $data['quantity'] * $service->getRatePerOne();

        $order = Order::query()->create($data);

        return redirect(route('orders.show', ['order' => $order->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Factory|View|Response|null
     */
    public function show($id)
    {
        $order = Order::query()->with(['profile', 'service'])->find($id);
        return view('orders.show', compact('order'));
    }
}
