import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
import interviews from './interviews'
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
 * @see app/Http/Controllers/ActionApplicationController.php:57
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
 * @see app/Http/Controllers/ActionApplicationController.php:57
 * @route '/action/applications'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:57
 * @route '/action/applications'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:57
 * @route '/action/applications'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:57
 * @route '/action/applications'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:97
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
 * @see app/Http/Controllers/ActionApplicationController.php:97
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
 * @see app/Http/Controllers/ActionApplicationController.php:97
 * @route '/action/applications/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:97
 * @route '/action/applications/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:97
 * @route '/action/applications/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:97
 * @route '/action/applications/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:97
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
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:53
 * @route '/action/applications/import'
 */
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/action/applications/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:53
 * @route '/action/applications/import'
 */
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:53
 * @route '/action/applications/import'
 */
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:53
 * @route '/action/applications/import'
 */
    const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: importMethod.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:53
 * @route '/action/applications/import'
 */
        importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: importMethod.url(options),
            method: 'post',
        })
    
    importMethod.form = importMethodForm
/**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:145
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
 * @see app/Http/Controllers/ActionApplicationController.php:145
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
 * @see app/Http/Controllers/ActionApplicationController.php:145
 * @route '/action/applications/{id}/edit'
 */
edit.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:145
 * @route '/action/applications/{id}/edit'
 */
edit.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:145
 * @route '/action/applications/{id}/edit'
 */
    const editForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:145
 * @route '/action/applications/{id}/edit'
 */
        editForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:145
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
 * @see app/Http/Controllers/ActionApplicationController.php:176
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
 * @see app/Http/Controllers/ActionApplicationController.php:176
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
 * @see app/Http/Controllers/ActionApplicationController.php:176
 * @route '/action/applications/{id}'
 */
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::update
 * @see app/Http/Controllers/ActionApplicationController.php:176
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
 * @see app/Http/Controllers/ActionApplicationController.php:176
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
* @see \App\Http\Controllers\ActionApplicationController::eligibleApplicants
 * @see app/Http/Controllers/ActionApplicationController.php:820
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
export const eligibleApplicants = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: eligibleApplicants.url(args, options),
    method: 'get',
})

eligibleApplicants.definition = {
    methods: ["get","head"],
    url: '/action/applications/eligible-applicants/{batchId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::eligibleApplicants
 * @see app/Http/Controllers/ActionApplicationController.php:820
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
eligibleApplicants.url = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return eligibleApplicants.definition.url
            .replace('{batchId}', parsedArgs.batchId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::eligibleApplicants
 * @see app/Http/Controllers/ActionApplicationController.php:820
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
eligibleApplicants.get = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: eligibleApplicants.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::eligibleApplicants
 * @see app/Http/Controllers/ActionApplicationController.php:820
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
eligibleApplicants.head = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: eligibleApplicants.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::eligibleApplicants
 * @see app/Http/Controllers/ActionApplicationController.php:820
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
    const eligibleApplicantsForm = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: eligibleApplicants.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::eligibleApplicants
 * @see app/Http/Controllers/ActionApplicationController.php:820
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
        eligibleApplicantsForm.get = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: eligibleApplicants.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::eligibleApplicants
 * @see app/Http/Controllers/ActionApplicationController.php:820
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
        eligibleApplicantsForm.head = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: eligibleApplicants.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    eligibleApplicants.form = eligibleApplicantsForm
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
* @see \App\Http\Controllers\ActionApplicationController::sendNotification
 * @see app/Http/Controllers/ActionApplicationController.php:310
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
 * @see app/Http/Controllers/ActionApplicationController.php:310
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
 * @see app/Http/Controllers/ActionApplicationController.php:310
 * @route '/action/applications/{application}/send-notification'
 */
sendNotification.post = (args: { application: string | number } | [application: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendNotification.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::sendNotification
 * @see app/Http/Controllers/ActionApplicationController.php:310
 * @route '/action/applications/{application}/send-notification'
 */
    const sendNotificationForm = (args: { application: string | number } | [application: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: sendNotification.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::sendNotification
 * @see app/Http/Controllers/ActionApplicationController.php:310
 * @route '/action/applications/{application}/send-notification'
 */
        sendNotificationForm.post = (args: { application: string | number } | [application: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: sendNotification.url(args, options),
            method: 'post',
        })
    
    sendNotification.form = sendNotificationForm
/**
* @see \App\Http\Controllers\ActionApplicationController::print
 * @see app/Http/Controllers/ActionApplicationController.php:286
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
 * @see app/Http/Controllers/ActionApplicationController.php:286
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
 * @see app/Http/Controllers/ActionApplicationController.php:286
 * @route '/action/applications/{id}/print'
 */
print.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: print.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::print
 * @see app/Http/Controllers/ActionApplicationController.php:286
 * @route '/action/applications/{id}/print'
 */
print.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: print.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::print
 * @see app/Http/Controllers/ActionApplicationController.php:286
 * @route '/action/applications/{id}/print'
 */
    const printForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: print.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::print
 * @see app/Http/Controllers/ActionApplicationController.php:286
 * @route '/action/applications/{id}/print'
 */
        printForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: print.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::print
 * @see app/Http/Controllers/ActionApplicationController.php:286
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
const applications = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
show: Object.assign(show, show),
import: Object.assign(importMethod, importMethod),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
eligibleApplicants: Object.assign(eligibleApplicants, eligibleApplicants),
checkEligibility: Object.assign(checkEligibility, checkEligibility),
interviews: Object.assign(interviews, interviews),
sendNotification: Object.assign(sendNotification, sendNotification),
print: Object.assign(print, print),
}

export default applications