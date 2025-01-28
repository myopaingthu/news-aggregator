<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SourceResource;
use App\Repositories\Source\SourceRepositoryInterface;

class SourceController extends Controller
{
    /**
     * The source repository instance.
     *
     * @var \App\Repositories\Source\SourceRepositoryInterface
     */
    protected $sourceRepository;

    /**
     * Initialize the repository instance.
     *
     * @param \App\Repositories\Source\SourceRepositoryInterface $sourceRepository
     */
    public function __construct(SourceRepositoryInterface $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
    }

    /**
     * Display a list of user preferences.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sources = $this->sourceRepository->getAll();
        return SourceResource::collection($sources);
    }
}
