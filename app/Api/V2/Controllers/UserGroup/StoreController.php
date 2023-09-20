<?php
/*
 * StoreController.php
 * Copyright (c) 2023 james@firefly-iii.org
 *
 * This file is part of Firefly III (https://github.com/firefly-iii).
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace FireflyIII\Api\V2\Controllers\UserGroup;

use FireflyIII\Api\V2\Controllers\Controller;
use FireflyIII\Api\V2\Request\UserGroup\StoreRequest;
use FireflyIII\Repositories\UserGroup\UserGroupRepositoryInterface;
use FireflyIII\Transformers\V2\UserGroupTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class StoreController
 */
class StoreController extends Controller
{
    private UserGroupRepositoryInterface $repository;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(
            function ($request, $next) {
                $this->repository = app(UserGroupRepositoryInterface::class);

                return $next($request);
            }
        );
    }

    /**
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $all         = $request->getAll();
        $userGroup   = $this->repository->store($all);
        $transformer = new UserGroupTransformer();
        $transformer->setParameters($this->parameters);

        return response()
            ->api($this->jsonApiObject('user-groups', $userGroup, $transformer))
            ->header('Content-Type', self::CONTENT_TYPE);
    }

}
