import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::index
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:11
 * @route '/action-applicants/{applicantId}/languages'
 */
export const index = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action-applicants/{applicantId}/languages',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::index
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:11
 * @route '/action-applicants/{applicantId}/languages'
 */
index.url = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { applicantId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    applicantId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicantId: args.applicantId,
                }

    return index.definition.url
            .replace('{applicantId}', parsedArgs.applicantId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::index
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:11
 * @route '/action-applicants/{applicantId}/languages'
 */
index.get = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::index
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:11
 * @route '/action-applicants/{applicantId}/languages'
 */
index.head = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::index
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:11
 * @route '/action-applicants/{applicantId}/languages'
 */
    const indexForm = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::index
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:11
 * @route '/action-applicants/{applicantId}/languages'
 */
        indexForm.get = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::index
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:11
 * @route '/action-applicants/{applicantId}/languages'
 */
        indexForm.head = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::store
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:19
 * @route '/action-applicants/{applicantId}/languages'
 */
export const store = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/action-applicants/{applicantId}/languages',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::store
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:19
 * @route '/action-applicants/{applicantId}/languages'
 */
store.url = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { applicantId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    applicantId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicantId: args.applicantId,
                }

    return store.definition.url
            .replace('{applicantId}', parsedArgs.applicantId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::store
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:19
 * @route '/action-applicants/{applicantId}/languages'
 */
store.post = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::store
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:19
 * @route '/action-applicants/{applicantId}/languages'
 */
    const storeForm = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::store
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:19
 * @route '/action-applicants/{applicantId}/languages'
 */
        storeForm.post = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::update
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:40
 * @route '/action-applicants/{applicantId}/languages/{langId}'
 */
export const update = (args: { applicantId: string | number, langId: string | number } | [applicantId: string | number, langId: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/action-applicants/{applicantId}/languages/{langId}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::update
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:40
 * @route '/action-applicants/{applicantId}/languages/{langId}'
 */
update.url = (args: { applicantId: string | number, langId: string | number } | [applicantId: string | number, langId: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    applicantId: args[0],
                    langId: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicantId: args.applicantId,
                                langId: args.langId,
                }

    return update.definition.url
            .replace('{applicantId}', parsedArgs.applicantId.toString())
            .replace('{langId}', parsedArgs.langId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::update
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:40
 * @route '/action-applicants/{applicantId}/languages/{langId}'
 */
update.put = (args: { applicantId: string | number, langId: string | number } | [applicantId: string | number, langId: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::update
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:40
 * @route '/action-applicants/{applicantId}/languages/{langId}'
 */
    const updateForm = (args: { applicantId: string | number, langId: string | number } | [applicantId: string | number, langId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::update
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:40
 * @route '/action-applicants/{applicantId}/languages/{langId}'
 */
        updateForm.put = (args: { applicantId: string | number, langId: string | number } | [applicantId: string | number, langId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::destroy
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:58
 * @route '/action-applicants/{applicantId}/languages/{langId}'
 */
export const destroy = (args: { applicantId: string | number, langId: string | number } | [applicantId: string | number, langId: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/action-applicants/{applicantId}/languages/{langId}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::destroy
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:58
 * @route '/action-applicants/{applicantId}/languages/{langId}'
 */
destroy.url = (args: { applicantId: string | number, langId: string | number } | [applicantId: string | number, langId: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    applicantId: args[0],
                    langId: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicantId: args.applicantId,
                                langId: args.langId,
                }

    return destroy.definition.url
            .replace('{applicantId}', parsedArgs.applicantId.toString())
            .replace('{langId}', parsedArgs.langId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::destroy
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:58
 * @route '/action-applicants/{applicantId}/languages/{langId}'
 */
destroy.delete = (args: { applicantId: string | number, langId: string | number } | [applicantId: string | number, langId: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::destroy
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:58
 * @route '/action-applicants/{applicantId}/languages/{langId}'
 */
    const destroyForm = (args: { applicantId: string | number, langId: string | number } | [applicantId: string | number, langId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::destroy
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:58
 * @route '/action-applicants/{applicantId}/languages/{langId}'
 */
        destroyForm.delete = (args: { applicantId: string | number, langId: string | number } | [applicantId: string | number, langId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::bulkDelete
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:67
 * @route '/action-applicants/{applicantId}/languages/bulk-delete'
 */
export const bulkDelete = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkDelete.url(args, options),
    method: 'post',
})

bulkDelete.definition = {
    methods: ["post"],
    url: '/action-applicants/{applicantId}/languages/bulk-delete',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::bulkDelete
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:67
 * @route '/action-applicants/{applicantId}/languages/bulk-delete'
 */
bulkDelete.url = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { applicantId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    applicantId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicantId: args.applicantId,
                }

    return bulkDelete.definition.url
            .replace('{applicantId}', parsedArgs.applicantId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::bulkDelete
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:67
 * @route '/action-applicants/{applicantId}/languages/bulk-delete'
 */
bulkDelete.post = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkDelete.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::bulkDelete
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:67
 * @route '/action-applicants/{applicantId}/languages/bulk-delete'
 */
    const bulkDeleteForm = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkDelete.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantProgrammingLanguageController::bulkDelete
 * @see app/Http/Controllers/ActionApplicantProgrammingLanguageController.php:67
 * @route '/action-applicants/{applicantId}/languages/bulk-delete'
 */
        bulkDeleteForm.post = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkDelete.url(args, options),
            method: 'post',
        })
    
    bulkDelete.form = bulkDeleteForm
const ActionApplicantProgrammingLanguageController = { index, store, update, destroy, bulkDelete }

export default ActionApplicantProgrammingLanguageController