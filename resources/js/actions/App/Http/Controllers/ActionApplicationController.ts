import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:28
 * @route '/action/applications'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action/applications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:28
 * @route '/action/applications'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:28
 * @route '/action/applications'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:28
 * @route '/action/applications'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:28
 * @route '/action/applications'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:28
 * @route '/action/applications'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:28
 * @route '/action/applications'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:43
 * @route '/action/applications/register'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/action/applications/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:43
 * @route '/action/applications/register'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:43
 * @route '/action/applications/register'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:43
 * @route '/action/applications/register'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:43
 * @route '/action/applications/register'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:43
 * @route '/action/applications/register'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:43
 * @route '/action/applications/register'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:58
 * @route '/action/applications'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/action/applications',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:58
 * @route '/action/applications'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:58
 * @route '/action/applications'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:58
 * @route '/action/applications'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:58
 * @route '/action/applications'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:107
 * @route '/action/applications/{id}'
 */
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/action/applications/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:107
 * @route '/action/applications/{id}'
 */
show.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        id: args.id,
                }

    return show.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:107
 * @route '/action/applications/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:107
 * @route '/action/applications/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:107
 * @route '/action/applications/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:107
 * @route '/action/applications/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:107
 * @route '/action/applications/{id}'
 */
        showForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:167
 * @route '/action/applications/{id}/edit'
 */
export const edit = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/action/applications/{id}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:167
 * @route '/action/applications/{id}/edit'
 */
edit.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        id: args.id,
                }

    return edit.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:167
 * @route '/action/applications/{id}/edit'
 */
edit.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:167
 * @route '/action/applications/{id}/edit'
 */
edit.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:167
 * @route '/action/applications/{id}/edit'
 */
    const editForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:167
 * @route '/action/applications/{id}/edit'
 */
        editForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:167
 * @route '/action/applications/{id}/edit'
 */
        editForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Http\Controllers\ActionApplicationController::update
 * @see app/Http/Controllers/ActionApplicationController.php:216
 * @route '/action/applications/{id}'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/action/applications/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::update
 * @see app/Http/Controllers/ActionApplicationController.php:216
 * @route '/action/applications/{id}'
 */
update.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        id: args.id,
                }

    return update.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::update
 * @see app/Http/Controllers/ActionApplicationController.php:216
 * @route '/action/applications/{id}'
 */
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::update
 * @see app/Http/Controllers/ActionApplicationController.php:216
 * @route '/action/applications/{id}'
 */
    const updateForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::update
 * @see app/Http/Controllers/ActionApplicationController.php:216
 * @route '/action/applications/{id}'
 */
        updateForm.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\ActionApplicationController::getApplicantsForBatch
 * @see app/Http/Controllers/ActionApplicationController.php:952
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
export const getApplicantsForBatch = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getApplicantsForBatch.url(args, options),
    method: 'get',
})

getApplicantsForBatch.definition = {
    methods: ["get","head"],
    url: '/action/applications/eligible-applicants/{batchId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::getApplicantsForBatch
 * @see app/Http/Controllers/ActionApplicationController.php:952
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
getApplicantsForBatch.url = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { batchId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    batchId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        batchId: args.batchId,
                }

    return getApplicantsForBatch.definition.url
            .replace('{batchId}', parsedArgs.batchId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::getApplicantsForBatch
 * @see app/Http/Controllers/ActionApplicationController.php:952
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
getApplicantsForBatch.get = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getApplicantsForBatch.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::getApplicantsForBatch
 * @see app/Http/Controllers/ActionApplicationController.php:952
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
getApplicantsForBatch.head = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getApplicantsForBatch.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::getApplicantsForBatch
 * @see app/Http/Controllers/ActionApplicationController.php:952
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
    const getApplicantsForBatchForm = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getApplicantsForBatch.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::getApplicantsForBatch
 * @see app/Http/Controllers/ActionApplicationController.php:952
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
        getApplicantsForBatchForm.get = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getApplicantsForBatch.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::getApplicantsForBatch
 * @see app/Http/Controllers/ActionApplicationController.php:952
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
        getApplicantsForBatchForm.head = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getApplicantsForBatch.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getApplicantsForBatch.form = getApplicantsForBatchForm
/**
* @see \App\Http\Controllers\ActionApplicationController::checkEligibility
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
export const checkEligibility = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkEligibility.url(options),
    method: 'post',
})

checkEligibility.definition = {
    methods: ["post"],
    url: '/action/applications/check-eligibility',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::checkEligibility
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
checkEligibility.url = (options?: RouteQueryOptions) => {
    return checkEligibility.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::checkEligibility
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
checkEligibility.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkEligibility.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::checkEligibility
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
    const checkEligibilityForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkEligibility.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::checkEligibility
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
        checkEligibilityForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkEligibility.url(options),
            method: 'post',
        })
    
    checkEligibility.form = checkEligibilityForm
/**
* @see \App\Http\Controllers\ActionApplicationController::bulkAddInterviews
 * @see app/Http/Controllers/ActionApplicationController.php:724
 * @route '/action/applications/{id}/interviews/bulk-add'
 */
export const bulkAddInterviews = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkAddInterviews.url(args, options),
    method: 'post',
})

bulkAddInterviews.definition = {
    methods: ["post"],
    url: '/action/applications/{id}/interviews/bulk-add',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::bulkAddInterviews
 * @see app/Http/Controllers/ActionApplicationController.php:724
 * @route '/action/applications/{id}/interviews/bulk-add'
 */
bulkAddInterviews.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        id: args.id,
                }

    return bulkAddInterviews.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::bulkAddInterviews
 * @see app/Http/Controllers/ActionApplicationController.php:724
 * @route '/action/applications/{id}/interviews/bulk-add'
 */
bulkAddInterviews.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkAddInterviews.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::bulkAddInterviews
 * @see app/Http/Controllers/ActionApplicationController.php:724
 * @route '/action/applications/{id}/interviews/bulk-add'
 */
    const bulkAddInterviewsForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkAddInterviews.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::bulkAddInterviews
 * @see app/Http/Controllers/ActionApplicationController.php:724
 * @route '/action/applications/{id}/interviews/bulk-add'
 */
        bulkAddInterviewsForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkAddInterviews.url(args, options),
            method: 'post',
        })
    
    bulkAddInterviews.form = bulkAddInterviewsForm
/**
* @see \App\Http\Controllers\ActionApplicationController::bulkDeleteInterviews
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{applicationId}/interviews/bulk-delete'
 */
export const bulkDeleteInterviews = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkDeleteInterviews.url(args, options),
    method: 'post',
})

bulkDeleteInterviews.definition = {
    methods: ["post"],
    url: '/action/applications/{applicationId}/interviews/bulk-delete',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::bulkDeleteInterviews
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{applicationId}/interviews/bulk-delete'
 */
bulkDeleteInterviews.url = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { applicationId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    applicationId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicationId: args.applicationId,
                }

    return bulkDeleteInterviews.definition.url
            .replace('{applicationId}', parsedArgs.applicationId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::bulkDeleteInterviews
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{applicationId}/interviews/bulk-delete'
 */
bulkDeleteInterviews.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkDeleteInterviews.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::bulkDeleteInterviews
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{applicationId}/interviews/bulk-delete'
 */
    const bulkDeleteInterviewsForm = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkDeleteInterviews.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::bulkDeleteInterviews
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{applicationId}/interviews/bulk-delete'
 */
        bulkDeleteInterviewsForm.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkDeleteInterviews.url(args, options),
            method: 'post',
        })
    
    bulkDeleteInterviews.form = bulkDeleteInterviewsForm
/**
* @see \App\Http\Controllers\ActionApplicationController::submitInterviewDecision
 * @see app/Http/Controllers/ActionApplicationController.php:863
 * @route '/action/applications/{applicationId}/interviews/{interviewId}/decision'
 */
export const submitInterviewDecision = (args: { applicationId: string | number, interviewId: string | number } | [applicationId: string | number, interviewId: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: submitInterviewDecision.url(args, options),
    method: 'post',
})

submitInterviewDecision.definition = {
    methods: ["post"],
    url: '/action/applications/{applicationId}/interviews/{interviewId}/decision',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::submitInterviewDecision
 * @see app/Http/Controllers/ActionApplicationController.php:863
 * @route '/action/applications/{applicationId}/interviews/{interviewId}/decision'
 */
submitInterviewDecision.url = (args: { applicationId: string | number, interviewId: string | number } | [applicationId: string | number, interviewId: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    applicationId: args[0],
                    interviewId: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicationId: args.applicationId,
                                interviewId: args.interviewId,
                }

    return submitInterviewDecision.definition.url
            .replace('{applicationId}', parsedArgs.applicationId.toString())
            .replace('{interviewId}', parsedArgs.interviewId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::submitInterviewDecision
 * @see app/Http/Controllers/ActionApplicationController.php:863
 * @route '/action/applications/{applicationId}/interviews/{interviewId}/decision'
 */
submitInterviewDecision.post = (args: { applicationId: string | number, interviewId: string | number } | [applicationId: string | number, interviewId: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: submitInterviewDecision.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::submitInterviewDecision
 * @see app/Http/Controllers/ActionApplicationController.php:863
 * @route '/action/applications/{applicationId}/interviews/{interviewId}/decision'
 */
    const submitInterviewDecisionForm = (args: { applicationId: string | number, interviewId: string | number } | [applicationId: string | number, interviewId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: submitInterviewDecision.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::submitInterviewDecision
 * @see app/Http/Controllers/ActionApplicationController.php:863
 * @route '/action/applications/{applicationId}/interviews/{interviewId}/decision'
 */
        submitInterviewDecisionForm.post = (args: { applicationId: string | number, interviewId: string | number } | [applicationId: string | number, interviewId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: submitInterviewDecision.url(args, options),
            method: 'post',
        })
    
    submitInterviewDecision.form = submitInterviewDecisionForm
/**
* @see \App\Http\Controllers\ActionApplicationController::bulkUpdateInterviewSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:613
 * @route '/action/applications/{applicationId}/interviews/bulk-update-schedule'
 */
export const bulkUpdateInterviewSchedule = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkUpdateInterviewSchedule.url(args, options),
    method: 'post',
})

bulkUpdateInterviewSchedule.definition = {
    methods: ["post"],
    url: '/action/applications/{applicationId}/interviews/bulk-update-schedule',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::bulkUpdateInterviewSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:613
 * @route '/action/applications/{applicationId}/interviews/bulk-update-schedule'
 */
bulkUpdateInterviewSchedule.url = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { applicationId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    applicationId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicationId: args.applicationId,
                }

    return bulkUpdateInterviewSchedule.definition.url
            .replace('{applicationId}', parsedArgs.applicationId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::bulkUpdateInterviewSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:613
 * @route '/action/applications/{applicationId}/interviews/bulk-update-schedule'
 */
bulkUpdateInterviewSchedule.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkUpdateInterviewSchedule.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::bulkUpdateInterviewSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:613
 * @route '/action/applications/{applicationId}/interviews/bulk-update-schedule'
 */
    const bulkUpdateInterviewScheduleForm = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkUpdateInterviewSchedule.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::bulkUpdateInterviewSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:613
 * @route '/action/applications/{applicationId}/interviews/bulk-update-schedule'
 */
        bulkUpdateInterviewScheduleForm.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkUpdateInterviewSchedule.url(args, options),
            method: 'post',
        })
    
    bulkUpdateInterviewSchedule.form = bulkUpdateInterviewScheduleForm
/**
* @see \App\Http\Controllers\ActionApplicationController::sendNotification
 * @see app/Http/Controllers/ActionApplicationController.php:412
 * @route '/action/applications/{application}/send-notification'
 */
export const sendNotification = (args: { application: string | number } | [application: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendNotification.url(args, options),
    method: 'post',
})

sendNotification.definition = {
    methods: ["post"],
    url: '/action/applications/{application}/send-notification',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::sendNotification
 * @see app/Http/Controllers/ActionApplicationController.php:412
 * @route '/action/applications/{application}/send-notification'
 */
sendNotification.url = (args: { application: string | number } | [application: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { application: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    application: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        application: args.application,
                }

    return sendNotification.definition.url
            .replace('{application}', parsedArgs.application.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::sendNotification
 * @see app/Http/Controllers/ActionApplicationController.php:412
 * @route '/action/applications/{application}/send-notification'
 */
sendNotification.post = (args: { application: string | number } | [application: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendNotification.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::sendNotification
 * @see app/Http/Controllers/ActionApplicationController.php:412
 * @route '/action/applications/{application}/send-notification'
 */
    const sendNotificationForm = (args: { application: string | number } | [application: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: sendNotification.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::sendNotification
 * @see app/Http/Controllers/ActionApplicationController.php:412
 * @route '/action/applications/{application}/send-notification'
 */
        sendNotificationForm.post = (args: { application: string | number } | [application: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: sendNotification.url(args, options),
            method: 'post',
        })
    
    sendNotification.form = sendNotificationForm
/**
* @see \App\Http\Controllers\ActionApplicationController::print
 * @see app/Http/Controllers/ActionApplicationController.php:383
 * @route '/action/applications/{id}/print'
 */
export const print = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: print.url(args, options),
    method: 'get',
})

print.definition = {
    methods: ["get","head"],
    url: '/action/applications/{id}/print',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::print
 * @see app/Http/Controllers/ActionApplicationController.php:383
 * @route '/action/applications/{id}/print'
 */
print.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        id: args.id,
                }

    return print.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::print
 * @see app/Http/Controllers/ActionApplicationController.php:383
 * @route '/action/applications/{id}/print'
 */
print.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: print.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::print
 * @see app/Http/Controllers/ActionApplicationController.php:383
 * @route '/action/applications/{id}/print'
 */
print.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: print.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::print
 * @see app/Http/Controllers/ActionApplicationController.php:383
 * @route '/action/applications/{id}/print'
 */
    const printForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: print.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::print
 * @see app/Http/Controllers/ActionApplicationController.php:383
 * @route '/action/applications/{id}/print'
 */
        printForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: print.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::print
 * @see app/Http/Controllers/ActionApplicationController.php:383
 * @route '/action/applications/{id}/print'
 */
        printForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: print.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    print.form = printForm
/**
* @see \App\Http\Controllers\ActionApplicationController::getBatchResourceSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:98
 * @route '/action/applications/batches/{batchId}/resource-schedule'
 */
export const getBatchResourceSchedule = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getBatchResourceSchedule.url(args, options),
    method: 'get',
})

getBatchResourceSchedule.definition = {
    methods: ["get","head"],
    url: '/action/applications/batches/{batchId}/resource-schedule',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::getBatchResourceSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:98
 * @route '/action/applications/batches/{batchId}/resource-schedule'
 */
getBatchResourceSchedule.url = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { batchId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    batchId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        batchId: args.batchId,
                }

    return getBatchResourceSchedule.definition.url
            .replace('{batchId}', parsedArgs.batchId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::getBatchResourceSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:98
 * @route '/action/applications/batches/{batchId}/resource-schedule'
 */
getBatchResourceSchedule.get = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getBatchResourceSchedule.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::getBatchResourceSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:98
 * @route '/action/applications/batches/{batchId}/resource-schedule'
 */
getBatchResourceSchedule.head = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getBatchResourceSchedule.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::getBatchResourceSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:98
 * @route '/action/applications/batches/{batchId}/resource-schedule'
 */
    const getBatchResourceScheduleForm = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getBatchResourceSchedule.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::getBatchResourceSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:98
 * @route '/action/applications/batches/{batchId}/resource-schedule'
 */
        getBatchResourceScheduleForm.get = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getBatchResourceSchedule.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::getBatchResourceSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:98
 * @route '/action/applications/batches/{batchId}/resource-schedule'
 */
        getBatchResourceScheduleForm.head = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getBatchResourceSchedule.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getBatchResourceSchedule.form = getBatchResourceScheduleForm
const ActionApplicationController = { index, create, store, show, edit, update, getApplicantsForBatch, checkEligibility, bulkAddInterviews, bulkDeleteInterviews, submitInterviewDecision, bulkUpdateInterviewSchedule, sendNotification, print, getBatchResourceSchedule }

export default ActionApplicationController