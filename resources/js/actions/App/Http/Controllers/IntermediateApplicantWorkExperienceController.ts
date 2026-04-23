import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::index
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:13
 * @route '/intermediate/applicants/{applicantId}/work-experiences'
 */
export const index = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/intermediate/applicants/{applicantId}/work-experiences',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::index
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:13
 * @route '/intermediate/applicants/{applicantId}/work-experiences'
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
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::index
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:13
 * @route '/intermediate/applicants/{applicantId}/work-experiences'
 */
index.get = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::index
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:13
 * @route '/intermediate/applicants/{applicantId}/work-experiences'
 */
index.head = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::index
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:13
 * @route '/intermediate/applicants/{applicantId}/work-experiences'
 */
    const indexForm = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::index
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:13
 * @route '/intermediate/applicants/{applicantId}/work-experiences'
 */
        indexForm.get = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::index
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:13
 * @route '/intermediate/applicants/{applicantId}/work-experiences'
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
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::store
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:20
 * @route '/intermediate/applicants/{applicantId}/work-experiences'
 */
export const store = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/intermediate/applicants/{applicantId}/work-experiences',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::store
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:20
 * @route '/intermediate/applicants/{applicantId}/work-experiences'
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
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::store
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:20
 * @route '/intermediate/applicants/{applicantId}/work-experiences'
 */
store.post = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::store
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:20
 * @route '/intermediate/applicants/{applicantId}/work-experiences'
 */
    const storeForm = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::store
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:20
 * @route '/intermediate/applicants/{applicantId}/work-experiences'
 */
        storeForm.post = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::update
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:36
 * @route '/intermediate/applicants/{applicantId}/work-experiences/{workId}'
 */
export const update = (args: { applicantId: string | number, workId: string | number } | [applicantId: string | number, workId: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/intermediate/applicants/{applicantId}/work-experiences/{workId}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::update
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:36
 * @route '/intermediate/applicants/{applicantId}/work-experiences/{workId}'
 */
update.url = (args: { applicantId: string | number, workId: string | number } | [applicantId: string | number, workId: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    applicantId: args[0],
                    workId: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicantId: args.applicantId,
                                workId: args.workId,
                }

    return update.definition.url
            .replace('{applicantId}', parsedArgs.applicantId.toString())
            .replace('{workId}', parsedArgs.workId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::update
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:36
 * @route '/intermediate/applicants/{applicantId}/work-experiences/{workId}'
 */
update.put = (args: { applicantId: string | number, workId: string | number } | [applicantId: string | number, workId: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::update
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:36
 * @route '/intermediate/applicants/{applicantId}/work-experiences/{workId}'
 */
    const updateForm = (args: { applicantId: string | number, workId: string | number } | [applicantId: string | number, workId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::update
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:36
 * @route '/intermediate/applicants/{applicantId}/work-experiences/{workId}'
 */
        updateForm.put = (args: { applicantId: string | number, workId: string | number } | [applicantId: string | number, workId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::destroy
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:78
 * @route '/intermediate/applicants/{applicantId}/work-experiences/{workId}'
 */
export const destroy = (args: { applicantId: string | number, workId: string | number } | [applicantId: string | number, workId: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/intermediate/applicants/{applicantId}/work-experiences/{workId}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::destroy
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:78
 * @route '/intermediate/applicants/{applicantId}/work-experiences/{workId}'
 */
destroy.url = (args: { applicantId: string | number, workId: string | number } | [applicantId: string | number, workId: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    applicantId: args[0],
                    workId: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicantId: args.applicantId,
                                workId: args.workId,
                }

    return destroy.definition.url
            .replace('{applicantId}', parsedArgs.applicantId.toString())
            .replace('{workId}', parsedArgs.workId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::destroy
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:78
 * @route '/intermediate/applicants/{applicantId}/work-experiences/{workId}'
 */
destroy.delete = (args: { applicantId: string | number, workId: string | number } | [applicantId: string | number, workId: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::destroy
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:78
 * @route '/intermediate/applicants/{applicantId}/work-experiences/{workId}'
 */
    const destroyForm = (args: { applicantId: string | number, workId: string | number } | [applicantId: string | number, workId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::destroy
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:78
 * @route '/intermediate/applicants/{applicantId}/work-experiences/{workId}'
 */
        destroyForm.delete = (args: { applicantId: string | number, workId: string | number } | [applicantId: string | number, workId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::bulkDelete
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:103
 * @route '/intermediate/applicants/{applicantId}/work-experiences/bulk-delete'
 */
export const bulkDelete = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkDelete.url(args, options),
    method: 'post',
})

bulkDelete.definition = {
    methods: ["post"],
    url: '/intermediate/applicants/{applicantId}/work-experiences/bulk-delete',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::bulkDelete
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:103
 * @route '/intermediate/applicants/{applicantId}/work-experiences/bulk-delete'
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
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::bulkDelete
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:103
 * @route '/intermediate/applicants/{applicantId}/work-experiences/bulk-delete'
 */
bulkDelete.post = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkDelete.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::bulkDelete
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:103
 * @route '/intermediate/applicants/{applicantId}/work-experiences/bulk-delete'
 */
    const bulkDeleteForm = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkDelete.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantWorkExperienceController::bulkDelete
 * @see app/Http/Controllers/IntermediateApplicantWorkExperienceController.php:103
 * @route '/intermediate/applicants/{applicantId}/work-experiences/bulk-delete'
 */
        bulkDeleteForm.post = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkDelete.url(args, options),
            method: 'post',
        })
    
    bulkDelete.form = bulkDeleteForm
const IntermediateApplicantWorkExperienceController = { index, store, update, destroy, bulkDelete }

export default IntermediateApplicantWorkExperienceController