<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Achievement;
use Zeropingheroes\Lanager\Lan;
use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Requests\StoreLanRequest;
use Zeropingheroes\Lanager\Services\GetGamesPlayedBetweenService;
use Zeropingheroes\Lanager\Venue;

class LanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $lans = Lan::orderBy('start', 'desc')
            ->get();

        $currentLan = Lan::happeningNow()->first()                      // LAN happening now
                   ?? Lan::future()->orderBy('start', 'asc')->first()   // Closest future LAN
                   ?? Lan::past()->orderBy('end', 'desc')->first();     // Most recently ended past LAN

        return View::make('pages.lans.index')
            ->with('lans', $lans)
            ->with('currentLan', $currentLan);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->authorize('create', Lan::class);

        return View::make('pages.lans.create')
            ->with('venues', Venue::orderBy('name')->get())
            ->with('achievements', Achievement::orderBy('name')->get())
            ->with('lan', new Lan);
    }

    /**
     * Display the specified resource.
     *
     * @param Lan $lan
     * @return \Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Lan $lan)
    {
        $this->authorize('view', $lan);

        return redirect()->route('lans.events.index', $lan);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function store(Request $httpRequest)
    {
        $this->authorize('create', Lan::class);

        $input = [
            'name' => $httpRequest->input('name'),
            'description' => $httpRequest->input('description'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'venue_id' => $httpRequest->input('venue_id'),
            'achievement_id' => $httpRequest->input('achievement_id'),
            'published' => $httpRequest->has('published'),
        ];

        $request = new StoreLanRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $lan = Lan::create($input);

        return redirect()
            ->route('lans.show', $lan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Lan $lan
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Lan $lan)
    {
        $this->authorize('update', $lan);

        return View::make('pages.lans.edit')
            ->with('venues', Venue::orderBy('name')->get())
            ->with('achievements', Achievement::orderBy('name')->get())
            ->with('lan', $lan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $httpRequest
     * @param  \Zeropingheroes\Lanager\Lan $lan
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function update(Request $httpRequest, Lan $lan)
    {
        $this->authorize('update', $lan);

        $input = [
            'name' => $httpRequest->input('name'),
            'description' => $httpRequest->input('description'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'venue_id' => $httpRequest->input('venue_id'),
            'achievement_id' => $httpRequest->input('achievement_id'),
            'published' => $httpRequest->has('published'),
            'id' => $lan->id,
        ];

        $request = new StoreLanRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $lan->update($input);

        return redirect()
            ->route('lans.show', $lan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\Lan $lan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lan $lan)
    {
        $this->authorize('delete', $lan);

        Lan::destroy($lan->id);

        return redirect()
            ->route('lans.index')
            ->withSuccess(__('phrase.item-name-deleted', ['item' => __('title.lan'), 'name' => $lan->name]));

    }
}
