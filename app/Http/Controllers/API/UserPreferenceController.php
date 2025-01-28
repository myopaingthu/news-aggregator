<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\UserPreference;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\UserPreferenceRequest;
use App\Http\Resources\UserPreferenceResource;
use App\Repositories\UserPreference\UserPreferenceRepositoryInterface;

class UserPreferenceController extends Controller
{
    /**
     * The user preference repository instance.
     *
     * @var \App\Repositories\UserPreference\UserPreferenceRepositoryInterface
     */
    protected $userPreferenceRepository;

    /**
     * Initialize the repository instance.
     *
     * @param \App\Repositories\UserPreference\UserPreferenceRepositoryInterface $userPreferenceRepository
     */
    public function __construct(UserPreferenceRepositoryInterface $userPreferenceRepository)
    {
        $this->userPreferenceRepository = $userPreferenceRepository;
    }

    /**
     * Display a list of user preferences.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $preferences = $this->userPreferenceRepository->getByUser(auth('sanctum')->id());
        return UserPreferenceResource::collection($preferences);
    }

    /**
     * Store a newly created user preference.
     *
     * @param \App\Http\Requests\UserPreferenceRequest $request
     * @return \App\Http\Resources\UserPreferenceResource
     */
    public function store(UserPreferenceRequest $request)
    {
        $preferences = $this->userPreferenceRepository->create(auth('sanctum')->id(), $request->validated());
        return new UserPreferenceResource($preferences);
    }

    /**
     * Update the specified user preference.
     *
     * @param \App\Http\Requests\UserPreferenceRequest $request
     * @param \App\Models\UserPreference $user_preference
     * @return \App\Http\Resources\UserPreferenceResource
     */
    public function update(UserPreferenceRequest $request, UserPreference $user_preference)
    {
        Gate::authorize('update', $user_preference);
        $preferences = $this->userPreferenceRepository->update($user_preference->id, auth('sanctum')->id(), $request->validated());
        return new UserPreferenceResource($preferences);
    }

    /**
     * Remove the specified user preference.
     *
     * @param \App\Models\UserPreference $user_preference
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserPreference $user_preference)
    {
        Gate::authorize('delete', $user_preference);
        $this->userPreferenceRepository->delete($user_preference->id, auth('sanctum')->id());
        return Response::success(Response::HTTP_OK, [], 'Deleted Successfully.');
    }
}
