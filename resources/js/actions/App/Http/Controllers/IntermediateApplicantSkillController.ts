import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::index
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:13
 * @route '/intermediate/applicants/{applicantId}/skills'
 */
export const index = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/intermediate/applicants/{applicantId}/skills',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::index
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:13
 * @route '/intermediate/applicants/{applicantId}/skills'
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
* @see \App\Http\Controllers\IntermediateApplicantSkillController::index
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:13
 * @route '/intermediate/applicants/{applicantId}/skills'
 */
index.get = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::index
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:13
 * @route '/intermediate/applicants/{applicantId}/skills'
 */
index.head = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::index
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:13
 * @route '/intermediate/applicants/{applicantId}/skills'
 */
    const indexForm = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::index
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:13
 * @route '/intermediate/applicants/{applicantId}/skills'
 */
        indexForm.get = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::index
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:13
 * @route '/intermediate/applicants/{applicantId}/skills'
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
* @see \App\Http\Controllers\IntermediateApplicantSkillController::store
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:20
 * @route '/intermediate/applicants/{applicantId}/skills'
 */
export const store = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/intermediate/applicants/{applicantId}/skills',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::store
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:20
 * @route '/intermediate/applicants/{applicantId}/skills'
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
* @see \App\Http\Controllers\IntermediateApplicantSkillController::store
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:20
 * @route '/intermediate/applicants/{applicantId}/skills'
 */
store.post = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::store
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:20
 * @route '/intermediate/applicants/{applicantId}/skills'
 */
    const storeForm = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::store
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:20
 * @route '/intermediate/applicants/{applicantId}/skills'
 */
        storeForm.post = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::update
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:33
 * @route '/intermediate/applicants/{applicantId}/skills/{skillId}'
 */
export const update = (args: { applicantId: string | number, skillId: string | number } | [applicantId: string | number, skillId: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/intermediate/applicants/{applicantId}/skills/{skillId}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::update
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:33
 * @route '/intermediate/applicants/{applicantId}/skills/{skillId}'
 */
update.url = (args: { applicantId: string | number, skillId: string | number } | [applicantId: string | number, skillId: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    applicantId: args[0],
                    skillId: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicantId: args.applicantId,
                                skillId: args.skillId,
                }

    return update.definition.url
            .replace('{applicantId}', parsedArgs.applicantId.toString())
            .replace('{skillId}', parsedArgs.skillId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::update
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:33
 * @route '/intermediate/applicants/{applicantId}/skills/{skillId}'
 */
update.put = (args: { applicantId: string | number, skillId: string | number } | [applicantId: string | number, skillId: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::update
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:33
 * @route '/intermediate/applicants/{applicantId}/skills/{skillId}'
 */
    const updateForm = (args: { applicantId: string | number, skillId: string | number } | [applicantId: string | number, skillId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::update
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:33
 * @route '/intermediate/applicants/{applicantId}/skills/{skillId}'
 */
        updateForm.put = (args: { applicantId: string | number, skillId: string | number } | [applicantId: string | number, skillId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\IntermediateApplicantSkillController::destroy
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:51
 * @route '/intermediate/applicants/{applicantId}/skills/{skillId}'
 */
export const destroy = (args: { applicantId: string | number, skillId: string | number } | [applicantId: string | number, skillId: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/intermediate/applicants/{applicantId}/skills/{skillId}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::destroy
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:51
 * @route '/intermediate/applicants/{applicantId}/skills/{skillId}'
 */
destroy.url = (args: { applicantId: string | number, skillId: string | number } | [applicantId: string | number, skillId: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    applicantId: args[0],
                    skillId: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicantId: args.applicantId,
                                skillId: args.skillId,
                }

    return destroy.definition.url
            .replace('{applicantId}', parsedArgs.applicantId.toString())
            .replace('{skillId}', parsedArgs.skillId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::destroy
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:51
 * @route '/intermediate/applicants/{applicantId}/skills/{skillId}'
 */
destroy.delete = (args: { applicantId: string | number, skillId: string | number } | [applicantId: string | number, skillId: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::destroy
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:51
 * @route '/intermediate/applicants/{applicantId}/skills/{skillId}'
 */
    const destroyForm = (args: { applicantId: string | number, skillId: string | number } | [applicantId: string | number, skillId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::destroy
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:51
 * @route '/intermediate/applicants/{applicantId}/skills/{skillId}'
 */
        destroyForm.delete = (args: { applicantId: string | number, skillId: string | number } | [applicantId: string | number, skillId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\IntermediateApplicantSkillController::bulkDelete
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:65
 * @route '/intermediate/applicants/{applicantId}/skills/bulk-delete'
 */
export const bulkDelete = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkDelete.url(args, options),
    method: 'post',
})

bulkDelete.definition = {
    methods: ["post"],
    url: '/intermediate/applicants/{applicantId}/skills/bulk-delete',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::bulkDelete
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:65
 * @route '/intermediate/applicants/{applicantId}/skills/bulk-delete'
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
* @see \App\Http\Controllers\IntermediateApplicantSkillController::bulkDelete
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:65
 * @route '/intermediate/applicants/{applicantId}/skills/bulk-delete'
 */
bulkDelete.post = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkDelete.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::bulkDelete
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:65
 * @route '/intermediate/applicants/{applicantId}/skills/bulk-delete'
 */
    const bulkDeleteForm = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkDelete.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantSkillController::bulkDelete
 * @see app/Http/Controllers/IntermediateApplicantSkillController.php:65
 * @route '/intermediate/applicants/{applicantId}/skills/bulk-delete'
 */
        bulkDeleteForm.post = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkDelete.url(args, options),
            method: 'post',
        })
    
    bulkDelete.form = bulkDeleteForm
const IntermediateApplicantSkillController = { index, store, update, destroy, bulkDelete }

export default IntermediateApplicantSkillController